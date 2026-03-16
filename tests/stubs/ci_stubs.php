<?php

/**
 * Global stubs for CodeIgniter functions and classes used by Salon_capacity tests.
 * These must be in the global namespace since the library calls them unqualified.
 */

// Global settings storage for tests.
if (!isset($GLOBALS['_test_settings'])) {
    $GLOBALS['_test_settings'] = [];
}

// Stub the setting() helper.
if (!function_exists('setting')) {
    function setting(array|string|null $key = null, mixed $default = null): mixed
    {
        return $GLOBALS['_test_settings'][$key] ?? $default;
    }
}

// Stub BASEPATH constant.
if (!defined('BASEPATH')) {
    define('BASEPATH', __DIR__ . '/../../system/');
}

// Minimal CI_Controller stub.
if (!class_exists('CI_Controller')) {
    class CI_Controller
    {
        public $db;
        public $load;
    }
}

// Minimal EA_Controller stub.
if (!class_exists('EA_Controller')) {
    class EA_Controller extends CI_Controller
    {
    }
}

// Global CI instance for get_instance().
if (!isset($GLOBALS['_ci_instance'])) {
    $GLOBALS['_ci_instance'] = null;
}

if (!function_exists('get_instance')) {
    function &get_instance()
    {
        return $GLOBALS['_ci_instance'];
    }
}
