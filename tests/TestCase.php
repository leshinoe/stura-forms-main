<?php

namespace Tests;

use App\Models\Dticket\DticketConfiguration;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        DticketConfiguration::factory()->sose()->create();
        DticketConfiguration::factory()->wise()->create();
    }
}
