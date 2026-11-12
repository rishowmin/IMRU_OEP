<?php

namespace App\Support;

class ExamDeviceName
{
    public static function fromUserAgent(?string $userAgent): ?string
    {
        if (!$userAgent) {
            return null;
        }

        $device = 'Unknown device';

        if (preg_match('/Android\s+[\d.]+;\s*([^;)]+)/i', $userAgent, $matches)) {
            $model = trim($matches[1]);
            $device = $model !== '' && !in_array(strtolower($model), ['k', 'wv'], true)
                ? $model
                : 'Android device';
        } elseif (stripos($userAgent, 'iPad') !== false) {
            $device = 'iPad';
        } elseif (stripos($userAgent, 'iPhone') !== false) {
            $device = 'iPhone';
        } elseif (stripos($userAgent, 'iPod') !== false) {
            $device = 'iPod';
        } elseif (stripos($userAgent, 'Windows') !== false) {
            $device = 'Windows PC';
        } elseif (stripos($userAgent, 'Macintosh') !== false || stripos($userAgent, 'Mac OS X') !== false) {
            $device = 'Mac';
        } elseif (stripos($userAgent, 'Linux') !== false) {
            $device = 'Linux PC';
        }

        $browser = self::browserFromUserAgent($userAgent);

        return $browser ? "{$device} - {$browser}" : $device;
    }

    public static function isUserAgent(?string $value): bool
    {
        return $value !== null && preg_match('/Mozilla\/|AppleWebKit\/|Chrome\/|Firefox\//i', $value) === 1;
    }

    private static function browserFromUserAgent(string $userAgent): ?string
    {
        $browsers = [
            'Edge' => '/Edg(?:A|iOS)?\/([\d.]+)/i',
            'Opera' => '/OPR\/([\d.]+)/i',
            'Samsung Internet' => '/SamsungBrowser\/([\d.]+)/i',
            'Firefox' => '/Firefox\/([\d.]+)/i',
            'Chrome' => '/(?:Chrome|CriOS)\/([\d.]+)/i',
            'Safari' => '/Version\/([\d.]+).*Safari/i',
        ];

        foreach ($browsers as $name => $pattern) {
            if (preg_match($pattern, $userAgent, $matches)) {
                return $name . ' ' . explode('.', $matches[1])[0];
            }
        }

        return null;
    }
}
