<?php

namespace Tests\Feature\Auth;

use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ThrottleTest extends TestCase
{
    #[Test]
    public function test_throttles_per_second(): void
    {
        Carbon::setTestNow($now = now()->startOfSecond()->toImmutable());

        $this->hitEndpoint('/auth/token/xxx', 5); // 5

        $this->get('/auth/token/xxx')->assertRedirect(); // 6
        $this->get('/auth/token/xxx')->assertTooManyRequests();

        Carbon::setTestNow($now->addSecond(1));

        $this->get('/auth/token/xxx')->assertRedirect();

        Carbon::setTestNow(null);
    }

    #[Test]
    public function test_throttles_per_minute(): void
    {
        Carbon::setTestNow($now = now()->startOfMinute()->toImmutable());

        $this->hitEndpoint('/auth/token/xxx', 6); // 6
        Carbon::setTestNow($now->addSecond(1));
        $this->hitEndpoint('/auth/token/xxx', 6); // 12
        Carbon::setTestNow($now->addSecond(2));
        $this->hitEndpoint('/auth/token/xxx', 6); // 18
        Carbon::setTestNow($now->addSecond(3));
        $this->hitEndpoint('/auth/token/xxx', 6); // 24
        Carbon::setTestNow($now->addSecond(4));
        $this->hitEndpoint('/auth/token/xxx', 6); // 30
        Carbon::setTestNow($now->addSecond(5));
        $this->hitEndpoint('/auth/token/xxx', 6); // 36
        Carbon::setTestNow($now->addSecond(6));
        $this->hitEndpoint('/auth/token/xxx', 3); // 39
        Carbon::setTestNow($now->addSecond(7));

        $this->get('/auth/token/xxx')->assertRedirect(); // 40
        $this->get('/auth/token/xxx')->assertTooManyRequests();

        Carbon::setTestNow($now->addMinute());

        $this->get('/auth/token/xxx')->assertRedirect();

        Carbon::setTestNow(null);
    }

    #[Test]
    public function test_throttles_per_hour(): void
    {
        Carbon::setTestNow($now = now()->startOfHour()->toImmutable());

        $this->hitEndpoint('/auth/token/xxx', 6); // 6
        Carbon::setTestNow($now->addMinutes(1));
        $this->hitEndpoint('/auth/token/xxx', 6); // 12
        Carbon::setTestNow($now->addMinutes(2));
        $this->hitEndpoint('/auth/token/xxx', 6); // 18
        Carbon::setTestNow($now->addMinutes(3));
        $this->hitEndpoint('/auth/token/xxx', 6); // 24
        Carbon::setTestNow($now->addMinutes(4));
        $this->hitEndpoint('/auth/token/xxx', 6); // 30
        Carbon::setTestNow($now->addMinutes(5));
        $this->hitEndpoint('/auth/token/xxx', 6); // 36
        Carbon::setTestNow($now->addMinutes(6));
        $this->hitEndpoint('/auth/token/xxx', 6); // 42
        Carbon::setTestNow($now->addMinutes(7));
        $this->hitEndpoint('/auth/token/xxx', 6); // 48
        Carbon::setTestNow($now->addMinutes(8));
        $this->hitEndpoint('/auth/token/xxx', 6); // 54
        Carbon::setTestNow($now->addMinutes(9));
        $this->hitEndpoint('/auth/token/xxx', 6); // 60
        Carbon::setTestNow($now->addMinutes(10));
        $this->hitEndpoint('/auth/token/xxx', 6); // 66
        Carbon::setTestNow($now->addMinutes(11));
        $this->hitEndpoint('/auth/token/xxx', 6); // 72
        Carbon::setTestNow($now->addMinutes(12));
        $this->hitEndpoint('/auth/token/xxx', 6); // 78
        Carbon::setTestNow($now->addMinutes(13));
        $this->hitEndpoint('/auth/token/xxx', 6); // 84
        Carbon::setTestNow($now->addMinutes(14));
        $this->hitEndpoint('/auth/token/xxx', 6); // 90
        Carbon::setTestNow($now->addMinutes(15));
        $this->hitEndpoint('/auth/token/xxx', 6); // 96
        Carbon::setTestNow($now->addMinutes(16));
        $this->hitEndpoint('/auth/token/xxx', 3); // 99

        $this->get('/auth/token/xxx')->assertRedirect(); // 100
        $this->get('/auth/token/xxx')->assertTooManyRequests();

        Carbon::setTestNow($now->addHour());

        $this->get('/auth/token/xxx')->assertRedirect();

        Carbon::setTestNow(null);
    }

    protected function hitEndpoint(string $endpoint, int $times): void
    {
        for ($i = 0; $i < $times; $i++) {
            $this->get($endpoint);
        }
    }
}
