<?php

namespace App\Libraries;

class JwtService
{
    private static function curlRequest(string $method, string $url, array $headers = [], ?array $body = null)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        
        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            log_message('error', "cURL Error on {$url}: {$error}");
            return ['success' => false, 'data' => null, 'code' => $httpCode];
        }

        return ['success' => true, 'data' => json_decode($response, true), 'code' => $httpCode, 'raw' => $response];
    }

    public static function login(string $email, string $password): string|false
    {
        $url = 'https://jwt-auth-eight-neon.vercel.app/login';
        $body = ['email' => $email, 'password' => $password];
        $headers = ['Content-Type: application/json'];

        $result = self::curlRequest('POST', $url, $headers, $body);// post ke API eksternal dengan email & password, dapat response apakah login berhasil dan token jika sukses

        if (!$result['success']) {
            return false;
        }

        if ($result['code'] === 200 && isset($result['data']['refreshToken'])) {
            return $result['data']['refreshToken'];// kembalikan token
        }

        log_message('error', "Login failed. Code: {$result['code']} Response: " . print_r($result['data'], true));
        return false;
    }

    public static function logout(string $token): bool
    {
        $url = 'https://jwt-auth-eight-neon.vercel.app/logout';
        $headers = [
            "Authorization: Bearer {$token}"
        ];

        $result = self::curlRequest('GET', $url, $headers);

        if (!$result['success']) {
            return false;
        }

        if (trim($result['raw']) === 'OK' || $result['code'] === 200) {
            return true;
        }

        log_message('error', "Logout failed. Code: {$result['code']} Response: {$result['raw']}");
        return false;
    }

    public static function getMakul(string $token): array
    {
        $url = 'https://jwt-auth-eight-neon.vercel.app/getMakul';
        $headers = [
            "Authorization: Bearer {$token}"
        ];

        $result = self::curlRequest('GET', $url, $headers);

        if (!$result['success']) {
            return [];
        }

        if ($result['code'] === 200 && isset($result['data']['data']) && is_array($result['data']['data'])) {
            return $result['data']['data'];
        }

        log_message('error', "getMakul failed. Code: {$result['code']} Response: " . print_r($result['data'], true));
        return [];
    }
}
