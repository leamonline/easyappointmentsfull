<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2026-03-16 00:17:21 --> Severity: error --> Exception: Connection refused /Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php 203 Trace: array (
  0 => 
  array (
    'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Exceptions.php',
    'line' => 104,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'Severity: error --> Exception: Connection refused /Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php 203',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Common.php',
    'line' => 675,
    'function' => 'log_exception',
    'class' => 'CI_Exceptions',
    'type' => '->',
    'args' => 
    array (
      0 => 'error',
      1 => 'Exception: Connection refused',
      2 => '/Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php',
      3 => 203,
    ),
  ),
  2 => 
  array (
    'function' => '_exception_handler',
    'args' => 
    array (
      0 => 
      \mysqli_sql_exception::__set_state(array(
         'message' => 'Connection refused',
         'string' => '',
         'code' => 2002,
         'file' => '/Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php',
         'line' => 203,
         'trace' => 
        array (
          0 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php',
            'line' => 203,
            'function' => 'real_connect',
            'class' => 'mysqli',
            'type' => '->',
            'args' => 
            array (
              0 => '127.0.0.1',
              1 => 'root',
              2 => 
              \SensitiveParameterValue::__set_state(array(
              )),
              3 => 'easyappointments',
              4 => NULL,
              5 => NULL,
              6 => 0,
            ),
          ),
          1 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/database/DB_driver.php',
            'line' => 419,
            'function' => 'db_connect',
            'class' => 'CI_DB_mysqli_driver',
            'type' => '->',
            'args' => 
            array (
              0 => false,
            ),
          ),
          2 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/database/DB.php',
            'line' => 219,
            'function' => 'initialize',
            'class' => 'CI_DB_driver',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          3 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Loader.php',
            'line' => 417,
            'function' => 'DB',
            'args' => 
            array (
              0 => 
              array (
                'hostname' => '127.0.0.1',
                'username' => 'root',
                'password' => '',
                'database' => 'easyappointments',
                'dbdriver' => 'mysqli',
                'dbprefix' => 'ea_',
                'pconnect' => false,
                'db_debug' => true,
                'cache_on' => false,
                'cachedir' => '',
                'char_set' => 'utf8mb4',
                'dbcollat' => 'utf8mb4_unicode_ci',
                'swap_pre' => '',
                'autoinit' => true,
                'stricton' => false,
              ),
              1 => NULL,
            ),
          ),
          4 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Loader.php',
            'line' => 1361,
            'function' => 'database',
            'class' => 'CI_Loader',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          5 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Loader.php',
            'line' => 174,
            'function' => '_ci_autoloader',
            'class' => 'CI_Loader',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          6 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Controller.php',
            'line' => 103,
            'function' => 'initialize',
            'class' => 'CI_Loader',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          7 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/application/core/EA_Controller.php',
            'line' => 80,
            'function' => '__construct',
            'class' => 'CI_Controller',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          8 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/application/controllers/Booking.php',
            'line' => 66,
            'function' => '__construct',
            'class' => 'EA_Controller',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          9 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 467,
            'function' => '__construct',
            'class' => 'Booking',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          10 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/index.php',
            'line' => 331,
            'args' => 
            array (
              0 => '/Users/leamonline/Desktop/easyappointments/system/core/CodeIgniter.php',
            ),
            'function' => 'require_once',
          ),
        ),
         'previous' => NULL,
         'sqlstate' => 'HY000',
      )),
    ),
  ),
)
ERROR - 2026-03-16 00:17:50 --> Severity: error --> Exception: Connection refused /Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php 203 Trace: array (
  0 => 
  array (
    'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Exceptions.php',
    'line' => 104,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'Severity: error --> Exception: Connection refused /Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php 203',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Common.php',
    'line' => 675,
    'function' => 'log_exception',
    'class' => 'CI_Exceptions',
    'type' => '->',
    'args' => 
    array (
      0 => 'error',
      1 => 'Exception: Connection refused',
      2 => '/Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php',
      3 => 203,
    ),
  ),
  2 => 
  array (
    'function' => '_exception_handler',
    'args' => 
    array (
      0 => 
      \mysqli_sql_exception::__set_state(array(
         'message' => 'Connection refused',
         'string' => '',
         'code' => 2002,
         'file' => '/Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php',
         'line' => 203,
         'trace' => 
        array (
          0 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php',
            'line' => 203,
            'function' => 'real_connect',
            'class' => 'mysqli',
            'type' => '->',
            'args' => 
            array (
              0 => '127.0.0.1',
              1 => 'root',
              2 => 
              \SensitiveParameterValue::__set_state(array(
              )),
              3 => 'easyappointments',
              4 => NULL,
              5 => NULL,
              6 => 0,
            ),
          ),
          1 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/database/DB_driver.php',
            'line' => 419,
            'function' => 'db_connect',
            'class' => 'CI_DB_mysqli_driver',
            'type' => '->',
            'args' => 
            array (
              0 => false,
            ),
          ),
          2 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/database/DB.php',
            'line' => 219,
            'function' => 'initialize',
            'class' => 'CI_DB_driver',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          3 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Loader.php',
            'line' => 417,
            'function' => 'DB',
            'args' => 
            array (
              0 => 
              array (
                'hostname' => '127.0.0.1',
                'username' => 'root',
                'password' => '',
                'database' => 'easyappointments',
                'dbdriver' => 'mysqli',
                'dbprefix' => 'ea_',
                'pconnect' => false,
                'db_debug' => true,
                'cache_on' => false,
                'cachedir' => '',
                'char_set' => 'utf8mb4',
                'dbcollat' => 'utf8mb4_unicode_ci',
                'swap_pre' => '',
                'autoinit' => true,
                'stricton' => false,
              ),
              1 => NULL,
            ),
          ),
          4 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Loader.php',
            'line' => 1361,
            'function' => 'database',
            'class' => 'CI_Loader',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          5 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Loader.php',
            'line' => 174,
            'function' => '_ci_autoloader',
            'class' => 'CI_Loader',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          6 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Controller.php',
            'line' => 103,
            'function' => 'initialize',
            'class' => 'CI_Loader',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          7 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/application/core/EA_Controller.php',
            'line' => 80,
            'function' => '__construct',
            'class' => 'CI_Controller',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          8 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/application/controllers/Booking.php',
            'line' => 66,
            'function' => '__construct',
            'class' => 'EA_Controller',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          9 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 467,
            'function' => '__construct',
            'class' => 'Booking',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          10 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/index.php',
            'line' => 331,
            'args' => 
            array (
              0 => '/Users/leamonline/Desktop/easyappointments/system/core/CodeIgniter.php',
            ),
            'function' => 'require_once',
          ),
        ),
         'previous' => NULL,
         'sqlstate' => 'HY000',
      )),
    ),
  ),
)
ERROR - 2026-03-16 00:17:51 --> Severity: error --> Exception: Connection refused /Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php 203 Trace: array (
  0 => 
  array (
    'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Exceptions.php',
    'line' => 104,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'Severity: error --> Exception: Connection refused /Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php 203',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Common.php',
    'line' => 675,
    'function' => 'log_exception',
    'class' => 'CI_Exceptions',
    'type' => '->',
    'args' => 
    array (
      0 => 'error',
      1 => 'Exception: Connection refused',
      2 => '/Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php',
      3 => 203,
    ),
  ),
  2 => 
  array (
    'function' => '_exception_handler',
    'args' => 
    array (
      0 => 
      \mysqli_sql_exception::__set_state(array(
         'message' => 'Connection refused',
         'string' => '',
         'code' => 2002,
         'file' => '/Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php',
         'line' => 203,
         'trace' => 
        array (
          0 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php',
            'line' => 203,
            'function' => 'real_connect',
            'class' => 'mysqli',
            'type' => '->',
            'args' => 
            array (
              0 => '127.0.0.1',
              1 => 'root',
              2 => 
              \SensitiveParameterValue::__set_state(array(
              )),
              3 => 'easyappointments',
              4 => NULL,
              5 => NULL,
              6 => 0,
            ),
          ),
          1 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/database/DB_driver.php',
            'line' => 419,
            'function' => 'db_connect',
            'class' => 'CI_DB_mysqli_driver',
            'type' => '->',
            'args' => 
            array (
              0 => false,
            ),
          ),
          2 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/database/DB.php',
            'line' => 219,
            'function' => 'initialize',
            'class' => 'CI_DB_driver',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          3 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Loader.php',
            'line' => 417,
            'function' => 'DB',
            'args' => 
            array (
              0 => 
              array (
                'hostname' => '127.0.0.1',
                'username' => 'root',
                'password' => '',
                'database' => 'easyappointments',
                'dbdriver' => 'mysqli',
                'dbprefix' => 'ea_',
                'pconnect' => false,
                'db_debug' => true,
                'cache_on' => false,
                'cachedir' => '',
                'char_set' => 'utf8mb4',
                'dbcollat' => 'utf8mb4_unicode_ci',
                'swap_pre' => '',
                'autoinit' => true,
                'stricton' => false,
              ),
              1 => NULL,
            ),
          ),
          4 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Loader.php',
            'line' => 1361,
            'function' => 'database',
            'class' => 'CI_Loader',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          5 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Loader.php',
            'line' => 174,
            'function' => '_ci_autoloader',
            'class' => 'CI_Loader',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          6 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Controller.php',
            'line' => 103,
            'function' => 'initialize',
            'class' => 'CI_Loader',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          7 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/application/core/EA_Controller.php',
            'line' => 80,
            'function' => '__construct',
            'class' => 'CI_Controller',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          8 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/application/controllers/Booking.php',
            'line' => 66,
            'function' => '__construct',
            'class' => 'EA_Controller',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          9 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 467,
            'function' => '__construct',
            'class' => 'Booking',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          10 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/index.php',
            'line' => 331,
            'args' => 
            array (
              0 => '/Users/leamonline/Desktop/easyappointments/system/core/CodeIgniter.php',
            ),
            'function' => 'require_once',
          ),
        ),
         'previous' => NULL,
         'sqlstate' => 'HY000',
      )),
    ),
  ),
)
ERROR - 2026-03-16 00:17:51 --> Severity: error --> Exception: Connection refused /Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php 203 Trace: array (
  0 => 
  array (
    'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Exceptions.php',
    'line' => 104,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'Severity: error --> Exception: Connection refused /Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php 203',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Common.php',
    'line' => 675,
    'function' => 'log_exception',
    'class' => 'CI_Exceptions',
    'type' => '->',
    'args' => 
    array (
      0 => 'error',
      1 => 'Exception: Connection refused',
      2 => '/Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php',
      3 => 203,
    ),
  ),
  2 => 
  array (
    'function' => '_exception_handler',
    'args' => 
    array (
      0 => 
      \mysqli_sql_exception::__set_state(array(
         'message' => 'Connection refused',
         'string' => '',
         'code' => 2002,
         'file' => '/Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php',
         'line' => 203,
         'trace' => 
        array (
          0 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php',
            'line' => 203,
            'function' => 'real_connect',
            'class' => 'mysqli',
            'type' => '->',
            'args' => 
            array (
              0 => '127.0.0.1',
              1 => 'root',
              2 => 
              \SensitiveParameterValue::__set_state(array(
              )),
              3 => 'easyappointments',
              4 => NULL,
              5 => NULL,
              6 => 0,
            ),
          ),
          1 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/database/DB_driver.php',
            'line' => 419,
            'function' => 'db_connect',
            'class' => 'CI_DB_mysqli_driver',
            'type' => '->',
            'args' => 
            array (
              0 => false,
            ),
          ),
          2 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/database/DB.php',
            'line' => 219,
            'function' => 'initialize',
            'class' => 'CI_DB_driver',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          3 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Loader.php',
            'line' => 417,
            'function' => 'DB',
            'args' => 
            array (
              0 => 
              array (
                'hostname' => '127.0.0.1',
                'username' => 'root',
                'password' => '',
                'database' => 'easyappointments',
                'dbdriver' => 'mysqli',
                'dbprefix' => 'ea_',
                'pconnect' => false,
                'db_debug' => true,
                'cache_on' => false,
                'cachedir' => '',
                'char_set' => 'utf8mb4',
                'dbcollat' => 'utf8mb4_unicode_ci',
                'swap_pre' => '',
                'autoinit' => true,
                'stricton' => false,
              ),
              1 => NULL,
            ),
          ),
          4 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Loader.php',
            'line' => 1361,
            'function' => 'database',
            'class' => 'CI_Loader',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          5 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Loader.php',
            'line' => 174,
            'function' => '_ci_autoloader',
            'class' => 'CI_Loader',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          6 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Controller.php',
            'line' => 103,
            'function' => 'initialize',
            'class' => 'CI_Loader',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          7 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/application/core/EA_Controller.php',
            'line' => 80,
            'function' => '__construct',
            'class' => 'CI_Controller',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          8 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/application/controllers/Booking.php',
            'line' => 66,
            'function' => '__construct',
            'class' => 'EA_Controller',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          9 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 467,
            'function' => '__construct',
            'class' => 'Booking',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          10 => 
          array (
            'file' => '/Users/leamonline/Desktop/easyappointments/index.php',
            'line' => 331,
            'args' => 
            array (
              0 => '/Users/leamonline/Desktop/easyappointments/system/core/CodeIgniter.php',
            ),
            'function' => 'require_once',
          ),
        ),
         'previous' => NULL,
         'sqlstate' => 'HY000',
      )),
    ),
  ),
)
ERROR - 2026-03-16 00:24:27 --> Severity: Error --> Maximum execution time of 30 seconds exceeded /Users/leamonline/Desktop/easyappointments/vendor/guzzlehttp/guzzle/src/Client.php 17 Trace: array (
  0 => 
  array (
    'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Exceptions.php',
    'line' => 104,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'Severity: Error --> Maximum execution time of 30 seconds exceeded /Users/leamonline/Desktop/easyappointments/vendor/guzzlehttp/guzzle/src/Client.php 17',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Common.php',
    'line' => 640,
    'function' => 'log_exception',
    'class' => 'CI_Exceptions',
    'type' => '->',
    'args' => 
    array (
      0 => 'Error',
      1 => 'Maximum execution time of 30 seconds exceeded',
      2 => '/Users/leamonline/Desktop/easyappointments/vendor/guzzlehttp/guzzle/src/Client.php',
      3 => 17,
    ),
  ),
  2 => 
  array (
    'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Common.php',
    'line' => 708,
    'function' => '_error_handler',
    'args' => 
    array (
      0 => 1,
      1 => 'Maximum execution time of 30 seconds exceeded',
      2 => '/Users/leamonline/Desktop/easyappointments/vendor/guzzlehttp/guzzle/src/Client.php',
      3 => 17,
    ),
  ),
)
ERROR - 2026-03-16 00:24:59 --> Severity: Error --> Maximum execution time of 30 seconds exceeded /Users/leamonline/Desktop/easyappointments/application/libraries/Ics_calendar.php 1 Trace: array (
  0 => 
  array (
    'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Exceptions.php',
    'line' => 104,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'Severity: Error --> Maximum execution time of 30 seconds exceeded /Users/leamonline/Desktop/easyappointments/application/libraries/Ics_calendar.php 1',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Common.php',
    'line' => 640,
    'function' => 'log_exception',
    'class' => 'CI_Exceptions',
    'type' => '->',
    'args' => 
    array (
      0 => 'Error',
      1 => 'Maximum execution time of 30 seconds exceeded',
      2 => '/Users/leamonline/Desktop/easyappointments/application/libraries/Ics_calendar.php',
      3 => 1,
    ),
  ),
  2 => 
  array (
    'file' => '/Users/leamonline/Desktop/easyappointments/system/core/Common.php',
    'line' => 708,
    'function' => '_error_handler',
    'args' => 
    array (
      0 => 1,
      1 => 'Maximum execution time of 30 seconds exceeded',
      2 => '/Users/leamonline/Desktop/easyappointments/application/libraries/Ics_calendar.php',
      3 => 1,
    ),
  ),
)
ERROR - 2026-03-16 02:38:28 --> 404 Page Not Found: Robotstxt/index Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Exceptions.php',
    'line' => 134,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => '404 Page Not Found: Robotstxt/index',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Common.php',
    'line' => 439,
    'function' => 'show_404',
    'class' => 'CI_Exceptions',
    'type' => '->',
    'args' => 
    array (
      0 => 'Robotstxt/index',
      1 => true,
    ),
  ),
  2 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
    'line' => 443,
    'function' => 'show_404',
    'args' => 
    array (
      0 => 'Robotstxt/index',
    ),
  ),
)
ERROR - 2026-03-16 02:40:57 --> 404 Page Not Found: Robotstxt/index Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Exceptions.php',
    'line' => 134,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => '404 Page Not Found: Robotstxt/index',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Common.php',
    'line' => 439,
    'function' => 'show_404',
    'class' => 'CI_Exceptions',
    'type' => '->',
    'args' => 
    array (
      0 => 'Robotstxt/index',
      1 => true,
    ),
  ),
  2 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
    'line' => 443,
    'function' => 'show_404',
    'args' => 
    array (
      0 => 'Robotstxt/index',
    ),
  ),
)
ERROR - 2026-03-16 02:41:29 --> 404 Page Not Found: Robotstxt/index Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Exceptions.php',
    'line' => 134,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => '404 Page Not Found: Robotstxt/index',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Common.php',
    'line' => 439,
    'function' => 'show_404',
    'class' => 'CI_Exceptions',
    'type' => '->',
    'args' => 
    array (
      0 => 'Robotstxt/index',
      1 => true,
    ),
  ),
  2 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
    'line' => 443,
    'function' => 'show_404',
    'args' => 
    array (
      0 => 'Robotstxt/index',
    ),
  ),
)
ERROR - 2026-03-16 02:59:46 --> JSON exception: {"success":false,"message":"Invalid credentials provided, please try again.","trace":"array (\n  0 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    'line' => 481,\n    'function' => 'validate',\n    'class' => 'Login',\n    'type' => '->',\n    'args' => \n    array (\n    ),\n  ),\n  1 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/index.php',\n    'line' => 331,\n    'args' => \n    array (\n      0 => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    ),\n    'function' => 'require_once',\n  ),\n)"} Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/helpers/http_helper.php',
    'line' => 142,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'JSON exception: {"success":false,"message":"Invalid credentials provided, please try again.","trace":"array (\\n  0 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    \'line\' => 481,\\n    \'function\' => \'validate\',\\n    \'class\' => \'Login\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n    ),\\n  ),\\n  1 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/index.php\',\\n    \'line\' => 331,\\n    \'args\' => \\n    array (\\n      0 => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    ),\\n    \'function\' => \'require_once\',\\n  ),\\n)"}',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Login.php',
    'line' => 95,
    'function' => 'json_exception',
    'args' => 
    array (
      0 => 
      \InvalidArgumentException::__set_state(array(
         'message' => 'Invalid credentials provided, please try again.',
         'string' => '',
         'code' => 0,
         'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Login.php',
         'line' => 84,
         'trace' => 
        array (
          0 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 481,
            'function' => 'validate',
            'class' => 'Login',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          1 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/index.php',
            'line' => 331,
            'args' => 
            array (
              0 => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
            ),
            'function' => 'require_once',
          ),
        ),
         'previous' => NULL,
      )),
    ),
  ),
  2 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
    'line' => 481,
    'function' => 'validate',
    'class' => 'Login',
    'type' => '->',
    'args' => 
    array (
    ),
  ),
)
ERROR - 2026-03-16 03:24:14 --> 404 Page Not Found: Robotstxt/index Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Exceptions.php',
    'line' => 134,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => '404 Page Not Found: Robotstxt/index',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Common.php',
    'line' => 439,
    'function' => 'show_404',
    'class' => 'CI_Exceptions',
    'type' => '->',
    'args' => 
    array (
      0 => 'Robotstxt/index',
      1 => true,
    ),
  ),
  2 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
    'line' => 443,
    'function' => 'show_404',
    'args' => 
    array (
      0 => 'Robotstxt/index',
    ),
  ),
)
ERROR - 2026-03-16 03:50:16 --> 404 Page Not Found: Robotstxt/index Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Exceptions.php',
    'line' => 134,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => '404 Page Not Found: Robotstxt/index',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Common.php',
    'line' => 439,
    'function' => 'show_404',
    'class' => 'CI_Exceptions',
    'type' => '->',
    'args' => 
    array (
      0 => 'Robotstxt/index',
      1 => true,
    ),
  ),
  2 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
    'line' => 443,
    'function' => 'show_404',
    'args' => 
    array (
      0 => 'Robotstxt/index',
    ),
  ),
)
ERROR - 2026-03-16 19:47:40 --> Severity: Error --> Maximum execution time of 30 seconds exceeded /Users/leam/Desktop/easyappointments/vendor/google/apiclient-services/src/Calendar/Resource/Calendars.php 30 Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Exceptions.php',
    'line' => 104,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'Severity: Error --> Maximum execution time of 30 seconds exceeded /Users/leam/Desktop/easyappointments/vendor/google/apiclient-services/src/Calendar/Resource/Calendars.php 30',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Common.php',
    'line' => 640,
    'function' => 'log_exception',
    'class' => 'CI_Exceptions',
    'type' => '->',
    'args' => 
    array (
      0 => 'Error',
      1 => 'Maximum execution time of 30 seconds exceeded',
      2 => '/Users/leam/Desktop/easyappointments/vendor/google/apiclient-services/src/Calendar/Resource/Calendars.php',
      3 => 30,
    ),
  ),
  2 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Common.php',
    'line' => 708,
    'function' => '_error_handler',
    'args' => 
    array (
      0 => 1,
      1 => 'Maximum execution time of 30 seconds exceeded',
      2 => '/Users/leam/Desktop/easyappointments/vendor/google/apiclient-services/src/Calendar/Resource/Calendars.php',
      3 => 30,
    ),
  ),
)
