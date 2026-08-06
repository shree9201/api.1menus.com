<?php
require __DIR__ . '/vendor/autoload.php';

use Google\Auth\Credentials\ServiceAccountCredentials;

header('Content-Type: application/json');

// ---- CONFIG ----
$PROJECT_ID = 'krushisetu-1908c';
$SERVICE_ACCOUNT_PATH = __DIR__ . '/secure/service-account.json';
$TOKEN_CACHE_FILE = __DIR__ . '/cache/fcm_token.json';
$API_KEY = 'my_super_secret_123'; // simple auth
// ----------------

// Auth (optional)
$clientKey = isset($_SERVER['HTTP_X_API_KEY']) ? $_SERVER['HTTP_X_API_KEY'] : '';
if ($clientKey !== $API_KEY) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'POST only']);
    exit;
}

// Input
$input = json_decode(file_get_contents('php://input'), true);
if (!$input || empty($input['token']) || empty($input['title']) || empty($input['body'])) {
    http_response_code(400);
    echo json_encode(['error' => 'token, title, body are required']);
    exit;
}

$deviceToken = $input['token'];
$title = $input['title'];
$body = $input['body'];

// Attempt to load OneSignal config from secure/onesignal.json or env
$oneSignalAuth = null;
$oneSignalAppId = null;
$onesignalPath = __DIR__ . '/secure/onesignal.json';
if (file_exists($onesignalPath)) {
    $cfg = json_decode(file_get_contents($onesignalPath), true);
    if (!empty($cfg['auth'])) $oneSignalAuth = $cfg['auth'];
    if (!empty($cfg['app_id'])) $oneSignalAppId = $cfg['app_id'];
}
if (empty($oneSignalAuth) && getenv('ONESIGNAL_AUTH')) $oneSignalAuth = getenv('ONESIGNAL_AUTH');
if (empty($oneSignalAppId) && getenv('ONESIGNAL_APP_ID')) $oneSignalAppId = getenv('ONESIGNAL_APP_ID');

function getAccessToken($serviceAccountPath, $cacheFile) {
    if (file_exists($cacheFile)) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if (!empty($cached['access_token']) && time() < ($cached['expires_at'] - 60)) {
            return $cached['access_token'];
        }
    }

    $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
    $credentials = new ServiceAccountCredentials(
        $scopes,
        json_decode(file_get_contents($serviceAccountPath), true)
    );

    $token = $credentials->fetchAuthToken();
    if (empty($token['access_token'])) {
        throw new Exception('Failed to fetch access token');
    }

    $expiresIn = isset($token['expires_in']) ? $token['expires_in'] : 3600;
    $data = array(
        'access_token' => $token['access_token'],
        'expires_at' => time() + $expiresIn
    );

    if (!is_dir(dirname($cacheFile))) {
        mkdir(dirname($cacheFile), 0755, true);
    }
    file_put_contents($cacheFile, json_encode($data));

    return $token['access_token'];
}

function sendFcm($projectId, $accessToken, $deviceToken, $title, $body) {
    $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

    $payload = [
        'message' => [
            'token' => $deviceToken,
            'data' => [
                'title' => $title,
                'body' => $body
            ],
            'android' => ['priority' => 'HIGH']
        ]
    ];

    $headers = [
        "Authorization: Bearer {$accessToken}",
        "Content-Type: application/json"
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
    ]);

    $response = curl_exec($ch);
    if ($response === false) {
        throw new Exception(curl_error($ch));
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return array($httpCode, json_decode($response, true));
}

function sendOneSignal($auth, $appId, $playerIds, $title, $body) {
    if (empty($auth) || empty($appId)) {
        return array(500, array('error' => 'OneSignal auth/app_id missing'));
    }

    $url = 'https://api.onesignal.com/notifications';
    $payload = array(
        'app_id' => $appId,
        'contents' => array('en' => $body),
        'headings' => array('en' => $title),
    );

    if (!empty($playerIds)) {
        // include both fields to be flexible with client keys
        $payload['include_player_ids'] = array_values($playerIds);
        $payload['include_subscription_ids'] = array_values($playerIds);
    }

    $headers = array(
        'Content-Type: application/json',
        'Authorization: ' . $auth,
    );

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
    ]);

    $response = curl_exec($ch);
    if ($response === false) {
        $err = curl_error($ch);
        curl_close($ch);
        return array(500, array('error' => $err));
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return array($httpCode, json_decode($response, true));
}

try {
    $accessToken = getAccessToken($SERVICE_ACCOUNT_PATH, $TOKEN_CACHE_FILE);
    // If provider is onesignal or onesignal ids provided, use OneSignal
    if ((isset($input['provider']) && strtolower($input['provider']) === 'onesignal') || !empty($input['onesignal_ids']) || !empty($input['onesignal_player_ids'])) {
        $ids = [];
        if (!empty($input['onesignal_player_ids'])) {
            $ids = is_array($input['onesignal_player_ids']) ? $input['onesignal_player_ids'] : array($input['onesignal_player_ids']);
        } elseif (!empty($input['onesignal_ids'])) {
            $ids = is_array($input['onesignal_ids']) ? $input['onesignal_ids'] : array($input['onesignal_ids']);
        } elseif (!empty($input['token'])) {
            $ids = array($input['token']);
        }

        list($code, $res) = sendOneSignal($oneSignalAuth, $oneSignalAppId, $ids, $title, $body);
    } else {
        list($code, $res) = sendFcm($PROJECT_ID, $accessToken, $deviceToken, $title, $body);
    }

    if ($code >= 200 && $code < 300) {
        echo json_encode(array('success' => true, 'fcm' => $res));
    } else {
        http_response_code($code);
        echo json_encode(['success' => false, 'fcm_error' => $res]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}