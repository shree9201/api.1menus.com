<?php
require __DIR__ . '/vendor/autoload.php';

use Google\Auth\Credentials\ServiceAccountCredentials;

class PushNotification
{
    private $projectId;
    private $serviceAccountPath;
    private $tokenCacheFile;

    public function __construct()
    {
        $this->projectId = 'krushisetu-1908c';
        $this->serviceAccountPath = __DIR__ . '/secure/service-account.json';
        $this->tokenCacheFile = __DIR__ . '/cache/fcm_token.json';
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
        list($code, $result) = $push->sendFcm($input['token'], $input['title'], $input['body']);
        http_response_code($code);
        echo json_encode($result);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array('error' => $e->getMessage()));
    }
}
