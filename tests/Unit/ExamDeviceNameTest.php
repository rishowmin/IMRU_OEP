<?php

namespace Tests\Unit;

use App\Support\ExamDeviceName;
use PHPUnit\Framework\TestCase;

class ExamDeviceNameTest extends TestCase
{
    public function test_it_returns_a_concise_windows_device_description(): void
    {
        $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36';

        $this->assertSame('Windows PC - Chrome 157', ExamDeviceName::fromUserAgent($userAgent));
    }

    public function test_it_includes_android_model_when_the_browser_reports_it(): void
    {
        $userAgent = 'Mozilla/5.0 (Linux; Android 13; SM-G991B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36';

        $this->assertSame('SM-G991B - Chrome 120', ExamDeviceName::fromUserAgent($userAgent));
    }

    public function test_it_does_not_expose_raw_user_agents_as_device_names(): void
    {
        $this->assertSame(
            'Windows PC - Chrome 157',
            ExamDeviceName::fromUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/157.0.0.0')
        );
    }
}
