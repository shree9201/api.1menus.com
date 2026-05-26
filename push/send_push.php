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

try {
    $accessToken = getAccessToken($SERVICE_ACCOUNT_PATH, $TOKEN_CACHE_FILE);
    list($code, $res) = sendFcm($PROJECT_ID, $accessToken, $deviceToken, $title, $body);

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