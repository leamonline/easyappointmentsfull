<?php

/**
 * PHPUnit Bootstrap File
 *
 * Sets up the environment for running tests against this CodeIgniter 3 application.
 */

// Define a minimal config class for testing (mirrors config-sample.php)
if (!class_exists('Config')) {
    class Config
    {
        const BASE_URL = 'http://localhost';
        const LANGUAGE = 'english';
        const DEBUG_MODE = false;
        const DB_HOST = 'localhost';
        const DB_NAME = 'easyappointments_test';
        const DB_USERNAME = 'root';
        const DB_PASSWORD = '';
        const GOOGLE_SYNC_FEATURE = false;
        const GOOGLE_CLIENT_ID = '';
        const GOOGLE_CLIENT_SECRET = '';
    }
}

// Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load CI stubs for unit testing (global-namespace functions & classes).
if (getenv('APP_ENV') === 'testing') {
    require_once __DIR__ . '/stubs/ci_stubs.php';
}
