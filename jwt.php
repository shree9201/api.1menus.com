<?php
class JWTHandler {
    public static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function base64UrlDecode($data) {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public static function encode(array $payload, $secret, $algo = 'HS256') {
        $header = ['typ' => 'JWT', 'alg' => $algo];
        $segments = [];
        $segments[] = self::base64UrlEncode(json_encode($header));
        $segments[] = self::base64UrlEncode(json_encode($payload));
        $signature = self::sign(implode('.', $segments), $secret, $algo);
        $segments[] = self::base64UrlEncode($signature);
        return implode('.', $segments);
    }

    public static function decode($jwt, $secret, &$payload = null) {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            return false;
        }

        list($encodedHeader, $encodedPayload, $encodedSignature) = $parts;
        $header = json_decode(self::base64UrlDecode($encodedHeader), true);
        $payload = json_decode(self::base64UrlDecode($encodedPayload), true);
        $signature = self::base64UrlDecode($encodedSignature);

        if (empty($header) || empty($payload) || $signature === false || !isset($header['alg'])) {
            return false;
        }

        $expected = self::sign($encodedHeader . '.' . $encodedPayload, $secret, $header['alg']);
        if (!hash_equals($expected, $signature)) {
            return false;
        }

        return $payload;
    }

    private static function sign($message, $secret, $algo) {
        switch ($algo) {
            case 'HS256':
                return hash_hmac('sha256', $message, $secret, true);
            case 'HS384':
                return hash_hmac('sha384', $message, $secret, true);
            case 'HS512':
                return hash_hmac('sha512', $message, $secret, true);
            default:
                throw new Exception('Unsupported JWT signing algorithm: ' . $algo);
        }
    }
}
