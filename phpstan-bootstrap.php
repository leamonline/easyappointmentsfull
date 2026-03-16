<?php

// Define constants needed by CodeIgniter files during static analysis.
define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/application/');
define('ENVIRONMENT', 'testing');
define('VIEWPATH', __DIR__ . '/application/views/');
define('CI_VERSION', '3.1.13');
define('FCPATH', __DIR__ . '/');

// Load application constants.
require_once __DIR__ . '/application/config/constants.php';
