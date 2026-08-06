<?php
require __DIR__ . '/vendor/autoload.php';

use Google\Auth\Credentials\ServiceAccountCredentials;

class PushNotification
{
    private $projectId;
    private $serviceAccountPath;
    private $tokenCacheFile;
    private $oneSignalAuth;
    private $oneSignalAppId;

    public function __construct()
    {
        $this->projectId = 'krushisetu-1908c';
        $this->serviceAccountPath = __DIR__ . '/secure/service-account.json';
        $this->tokenCacheFile = __DIR__ . '/cache/fcm_token.json';
        $this->oneSignalAuth = null;
        $this->oneSignalAppId = null;

        // Load OneSignal config from secure/onesignal.json if present, otherwise use env vars
        $onesignalConfigPath = __DIR__ . '/secure/onesignal.json';
        if (file_exists($onesignalConfigPath)) {
            $cfg = json_decode(file_get_contents($onesignalConfigPath), true);
            if (!empty($cfg['auth'])) {
                $this->oneSignalAuth = $cfg['auth'];
            }
            if (!empty($cfg['app_id'])) {
                $this->oneSignalAppId = $cfg['app_id'];
            }
        }

        if (empty($this->oneSignalAuth) && getenv('ONESIGNAL_AUTH')) {
            $this->oneSignalAuth = getenv('ONESIGNAL_AUTH');
        }
        if (empty($this->oneSignalAppId) && getenv('ONESIGNAL_APP_ID')) {
            $this->oneSignalAppId = getenv('ONESIGNAL_APP_ID');
        }
    }

    private function getAccessToken()
    {
        if (file_exists($this->tokenCacheFile)) {
            $cached = json_decode(file_get_contents($this->tokenCacheFile), true);
            if (!empty($cached['access_token']) && time() < ($cached['expires_at'] - 60)) {
                return $cached['access_token'];
            }
        }

        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
        $credentials = new ServiceAccountCredentials(
            $scopes,
            json_decode(file_get_contents($this->serviceAccountPath), true)
        );

        $token = $credentials->fetchAuthToken();
        if (empty($token['access_token'])) {
            throw new Exception('Failed to fetch access token');
        }

        $expiresIn = isset($token['expires_in']) ? $token['expires_in'] : 3600;
        $data = array(
            'access_token' => $token['access_token'],
            'expires_at' => time() + $expiresIn,
        );

        if (!is_dir(dirname($this->tokenCacheFile))) {
            mkdir(dirname($this->tokenCacheFile), 0755, true);
        }
        file_put_contents($this->tokenCacheFile, json_encode($data));

        return $token['access_token'];
    }

    public function sendFcm($deviceToken, $title, $body)
    {
        try {
            $accessToken = $this->getAccessToken();
            $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

            $payload = [
                'message' => [
                    'token' => $deviceToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'image' => 'https://1menus.com/app/b2c/assets/img/whatsapp-transparent-12.png',
                    ],

                    'android' => ['priority' => 'HIGH'],
                ],
            ];

            $headers = [
                "Authorization: Bearer {$accessToken}",
                'Content-Type: application/json',
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
                $error = curl_error($ch);
                curl_close($ch);
                return array(500, array('error' => $error));
            }

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return array($httpCode, json_decode($response, true));
        } catch (Exception $e) {
            return array(500, array('error' => $e->getMessage()));
        }
    }

    public function sendOneSignal(array $playerIds, $title, $body)
    {
        if (empty($this->oneSignalAuth) || empty($this->oneSignalAppId)) {
            return array(500, array('error' => 'OneSignal configuration missing (auth/app_id)'));
        }

        $url = 'https://api.onesignal.com/notifications';

        $payload = array(
            'app_id' => $this->oneSignalAppId,
            'contents' => array('en' => $body),
            'headings' => array('en' => $title),
        );

        // OneSignal accepts several recipient fields. prefer include_player_ids
        if (!empty($playerIds)) {
            $payload['include_player_ids'] = array_values($playerIds);
        }

        $headers = array(
            'Content-Type: application/json',
            'Authorization: ' . $this->oneSignalAuth,
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
            $error = curl_error($ch);
            curl_close($ch);
            return array(500, array('error' => $error));
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return array($httpCode, json_decode($response, true));
    }
}

// Only execute if called directly as HTTP request
if (__FILE__ === $_SERVER['SCRIPT_FILENAME']) {
    header('Content-Type: application/json');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'POST method required']);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || empty($input['token']) || empty($input['title']) || empty($input['body'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'token, title, body are required'));
        exit;
    }

    try {
        $push = new PushNotification();

        // If provider is explicitly OneSignal or onesignal ids are provided, use OneSignal
        if ((isset($input['provider']) && strtolower($input['provider']) === 'onesignal') || !empty($input['onesignal_ids']) || !empty($input['onesignal_player_ids'])) {
            $ids = [];
            if (!empty($input['onesignal_player_ids'])) {
                $ids = is_array($input['onesignal_player_ids']) ? $input['onesignal_player_ids'] : array($input['onesignal_player_ids']);
            } elseif (!empty($input['onesignal_ids'])) {
                $ids = is_array($input['onesignal_ids']) ? $input['onesignal_ids'] : array($input['onesignal_ids']);
            } elseif (!empty($input['token'])) {
                // fallback: treat token as a player id
                $ids = array($input['token']);
            }

            list($code, $result) = $push->sendOneSignal($ids, $input['title'], $input['body']);
            http_response_code($code);
            echo json_encode($result);
            exit;
        }

        // Default: send via FCM
        list($code, $result) = $push->sendFcm($input['token'], $input['title'], $input['body']);
        http_response_code($code);
        echo json_encode($result);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array('error' => $e->getMessage()));
    }
}
