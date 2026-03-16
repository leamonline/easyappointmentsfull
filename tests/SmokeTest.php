<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

/**
 * Smoke test to verify PHPUnit is working correctly.
 */
class SmokeTest extends TestCase
{
    public function test_phpunit_runs(): void
    {
        $this->assertTrue(true);
    }

    public function test_config_class_exists(): void
    {
        $this->assertTrue(class_exists('Config'));
    }
}
