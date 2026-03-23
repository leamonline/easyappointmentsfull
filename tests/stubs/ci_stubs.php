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

// Minimal CI_Controller stub with dynamic property support (models/libraries).
if (!class_exists('CI_Controller')) {
    #[\AllowDynamicProperties]
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

// Minimal CI_Model stub (proxies property access to CI instance).
// Note: do NOT declare $db or $load here — they must remain undeclared
// so that __get() fires and proxies to the CI instance, matching real CI behaviour.
if (!class_exists('CI_Model')) {
    #[\AllowDynamicProperties]
    class CI_Model
    {
        public function __get($key)
        {
            return get_instance()->$key;
        }
    }
}

// Minimal EA_Model stub with cast() and quote_order_by() support.
if (!class_exists('EA_Model')) {
    class EA_Model extends CI_Model
    {
        protected array $casts = [];
        protected array $api_resource = [];

        public function __construct()
        {
        }

        protected function cast(array &$record): void
        {
            foreach ($this->casts as $field => $type) {
                if (!array_key_exists($field, $record)) {
                    continue;
                }

                $record[$field] = match ($type) {
                    'integer' => (int) $record[$field],
                    'float' => (float) $record[$field],
                    'boolean' => (bool) $record[$field],
                    'string' => (string) $record[$field],
                    default => $record[$field],
                };
            }
        }

        protected function quote_order_by(string $order_by): string
        {
            return $order_by;
        }

        public function db_field(string $api_field): ?string
        {
            return $this->api_resource[$api_field] ?? null;
        }
    }
}

// Global CI instance for get_instance().
if (!isset($GLOBALS['_ci_instance'])) {
    $GLOBALS['_ci_instance'] = null;
}

// Application constants.
if (!defined('AVAILABILITIES_TYPE_FIXED')) {
    define('AVAILABILITIES_TYPE_FIXED', 'fixed');
}

if (!defined('EVENT_MINIMUM_DURATION')) {
    define('EVENT_MINIMUM_DURATION', 5);
}

if (!defined('DB_SLUG_PROVIDER')) {
    define('DB_SLUG_PROVIDER', 'provider');
}

if (!defined('DB_SLUG_CUSTOMER')) {
    define('DB_SLUG_CUSTOMER', 'customer');
}

// Stub the validate_datetime() helper.
if (!function_exists('validate_datetime')) {
    function validate_datetime(string $value): bool
    {
        return (bool) DateTime::createFromFormat('Y-m-d H:i:s', $value);
    }
}

if (!function_exists('get_instance')) {
    function &get_instance()
    {
        return $GLOBALS['_ci_instance'];
    }
}
