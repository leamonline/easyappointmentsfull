<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2026-03-17 18:35:43 --> Severity: Error --> Maximum execution time of 30 seconds exceeded /Users/leam/Desktop/easyappointments/application/core/EA_Input.php 1 Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Exceptions.php',
    'line' => 104,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'Severity: Error --> Maximum execution time of 30 seconds exceeded /Users/leam/Desktop/easyappointments/application/core/EA_Input.php 1',
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
      2 => '/Users/leam/Desktop/easyappointments/application/core/EA_Input.php',
      3 => 1,
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
      2 => '/Users/leam/Desktop/easyappointments/application/core/EA_Input.php',
      3 => 1,
    ),
  ),
)
ERROR - 2026-03-17 18:36:14 --> Severity: Error --> Maximum execution time of 30 seconds exceeded /Users/leam/Desktop/easyappointments/vendor/composer/ClassLoader.php 429 Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Exceptions.php',
    'line' => 104,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'Severity: Error --> Maximum execution time of 30 seconds exceeded /Users/leam/Desktop/easyappointments/vendor/composer/ClassLoader.php 429',
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
      2 => '/Users/leam/Desktop/easyappointments/vendor/composer/ClassLoader.php',
      3 => 429,
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
      2 => '/Users/leam/Desktop/easyappointments/vendor/composer/ClassLoader.php',
      3 => 429,
    ),
  ),
)
ERROR - 2026-03-17 20:48:51 --> Not Found: Migrate/index Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Exceptions.php',
    'line' => 134,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'Not Found: Migrate/index',
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
      0 => 'Migrate/index',
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
      0 => 'Migrate/index',
    ),
  ),
)
ERROR - 2026-03-17 20:49:03 --> Severity: error --> Exception: Table 'easyappointments.customer_auth' doesn't exist /Users/leam/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php 301 Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Exceptions.php',
    'line' => 104,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'Severity: error --> Exception: Table \'easyappointments.customer_auth\' doesn\'t exist /Users/leam/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php 301',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Common.php',
    'line' => 675,
    'function' => 'log_exception',
    'class' => 'CI_Exceptions',
    'type' => '->',
    'args' => 
    array (
      0 => 'error',
      1 => 'Exception: Table \'easyappointments.customer_auth\' doesn\'t exist',
      2 => '/Users/leam/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php',
      3 => 301,
    ),
  ),
  2 => 
  array (
    'function' => '_exception_handler',
    'args' => 
    array (
      0 => 
      \mysqli_sql_exception::__set_state(array(
         'message' => 'Table \'easyappointments.customer_auth\' doesn\'t exist',
         'string' => '',
         'code' => 1146,
         'file' => '/Users/leam/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php',
         'line' => 301,
         'trace' => 
        array (
          0 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php',
            'line' => 301,
            'function' => 'query',
            'class' => 'mysqli',
            'type' => '->',
            'args' => 
            array (
              0 => '
                ALTER TABLE `customer_auth`
                ADD CONSTRAINT `fk_customer_auth_customers`
                FOREIGN KEY (`id_users_customer`)
                REFERENCES `users` (`id`)
                ON DELETE CASCADE
                ON UPDATE CASCADE
            ',
            ),
          ),
          1 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/database/DB_driver.php',
            'line' => 746,
            'function' => '_execute',
            'class' => 'CI_DB_mysqli_driver',
            'type' => '->',
            'args' => 
            array (
              0 => '
                ALTER TABLE `customer_auth`
                ADD CONSTRAINT `fk_customer_auth_customers`
                FOREIGN KEY (`id_users_customer`)
                REFERENCES `users` (`id`)
                ON DELETE CASCADE
                ON UPDATE CASCADE
            ',
            ),
          ),
          2 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/database/DB_driver.php',
            'line' => 636,
            'function' => 'simple_query',
            'class' => 'CI_DB_driver',
            'type' => '->',
            'args' => 
            array (
              0 => '
                ALTER TABLE `customer_auth`
                ADD CONSTRAINT `fk_customer_auth_customers`
                FOREIGN KEY (`id_users_customer`)
                REFERENCES `users` (`id`)
                ON DELETE CASCADE
                ON UPDATE CASCADE
            ',
            ),
          ),
          3 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/migrations/066_create_customer_auth_table.php',
            'line' => 69,
            'function' => 'query',
            'class' => 'CI_DB_driver',
            'type' => '->',
            'args' => 
            array (
              0 => '
                ALTER TABLE `customer_auth`
                ADD CONSTRAINT `fk_customer_auth_customers`
                FOREIGN KEY (`id_users_customer`)
                REFERENCES `users` (`id`)
                ON DELETE CASCADE
                ON UPDATE CASCADE
            ',
            ),
          ),
          4 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/libraries/Migration.php',
            'line' => 306,
            'function' => 'up',
            'class' => 'Migration_Create_customer_auth_table',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          5 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/libraries/Migration.php',
            'line' => 344,
            'function' => 'version',
            'class' => 'CI_Migration',
            'type' => '->',
            'args' => 
            array (
              0 => '066',
            ),
          ),
          6 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/libraries/Instance.php',
            'line' => 75,
            'function' => 'latest',
            'class' => 'CI_Migration',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          7 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Console.php',
            'line' => 90,
            'function' => 'migrate',
            'class' => 'Instance',
            'type' => '->',
            'args' => 
            array (
              0 => '',
            ),
          ),
          8 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 481,
            'function' => 'migrate',
            'class' => 'Console',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          9 => 
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
         'sqlstate' => '42S02',
      )),
    ),
  ),
)
ERROR - 2026-03-17 21:06:38 --> JSON exception: {"success":false,"message":"json_response(): Argument #1 ($content) must be of type array, null given, called in \/Users\/leam\/Desktop\/easyappointments\/application\/controllers\/Booking_auth.php on line 149","trace":"array (\n  0 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/controllers\/Booking_auth.php',\n    'line' => 149,\n    'function' => 'json_response',\n    'args' => \n    array (\n      0 => NULL,\n    ),\n  ),\n  1 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    'line' => 481,\n    'function' => 'check_session',\n    'class' => 'Booking_auth',\n    'type' => '->',\n    'args' => \n    array (\n    ),\n  ),\n  2 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/index.php',\n    'line' => 331,\n    'args' => \n    array (\n      0 => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    ),\n    'function' => 'require_once',\n  ),\n)"} Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/helpers/http_helper.php',
    'line' => 142,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'JSON exception: {"success":false,"message":"json_response(): Argument #1 ($content) must be of type array, null given, called in \\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/controllers\\/Booking_auth.php on line 149","trace":"array (\\n  0 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/controllers\\/Booking_auth.php\',\\n    \'line\' => 149,\\n    \'function\' => \'json_response\',\\n    \'args\' => \\n    array (\\n      0 => NULL,\\n    ),\\n  ),\\n  1 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    \'line\' => 481,\\n    \'function\' => \'check_session\',\\n    \'class\' => \'Booking_auth\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n    ),\\n  ),\\n  2 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/index.php\',\\n    \'line\' => 331,\\n    \'args\' => \\n    array (\\n      0 => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    ),\\n    \'function\' => \'require_once\',\\n  ),\\n)"}',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Booking_auth.php',
    'line' => 162,
    'function' => 'json_exception',
    'args' => 
    array (
      0 => 
      \TypeError::__set_state(array(
         'message' => 'json_response(): Argument #1 ($content) must be of type array, null given, called in /Users/leam/Desktop/easyappointments/application/controllers/Booking_auth.php on line 149',
         'string' => '',
         'code' => 0,
         'file' => '/Users/leam/Desktop/easyappointments/application/helpers/http_helper.php',
         'line' => 108,
         'trace' => 
        array (
          0 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Booking_auth.php',
            'line' => 149,
            'function' => 'json_response',
            'args' => 
            array (
              0 => NULL,
            ),
          ),
          1 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 481,
            'function' => 'check_session',
            'class' => 'Booking_auth',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          2 => 
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
    'function' => 'check_session',
    'class' => 'Booking_auth',
    'type' => '->',
    'args' => 
    array (
    ),
  ),
)
ERROR - 2026-03-17 21:07:40 --> JSON exception: {"success":false,"message":"json_response(): Argument #1 ($content) must be of type array, null given, called in \/Users\/leam\/Desktop\/easyappointments\/application\/controllers\/Booking_auth.php on line 149","trace":"array (\n  0 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/controllers\/Booking_auth.php',\n    'line' => 149,\n    'function' => 'json_response',\n    'args' => \n    array (\n      0 => NULL,\n    ),\n  ),\n  1 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    'line' => 481,\n    'function' => 'check_session',\n    'class' => 'Booking_auth',\n    'type' => '->',\n    'args' => \n    array (\n    ),\n  ),\n  2 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/index.php',\n    'line' => 331,\n    'args' => \n    array (\n      0 => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    ),\n    'function' => 'require_once',\n  ),\n)"} Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/helpers/http_helper.php',
    'line' => 142,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'JSON exception: {"success":false,"message":"json_response(): Argument #1 ($content) must be of type array, null given, called in \\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/controllers\\/Booking_auth.php on line 149","trace":"array (\\n  0 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/controllers\\/Booking_auth.php\',\\n    \'line\' => 149,\\n    \'function\' => \'json_response\',\\n    \'args\' => \\n    array (\\n      0 => NULL,\\n    ),\\n  ),\\n  1 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    \'line\' => 481,\\n    \'function\' => \'check_session\',\\n    \'class\' => \'Booking_auth\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n    ),\\n  ),\\n  2 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/index.php\',\\n    \'line\' => 331,\\n    \'args\' => \\n    array (\\n      0 => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    ),\\n    \'function\' => \'require_once\',\\n  ),\\n)"}',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Booking_auth.php',
    'line' => 162,
    'function' => 'json_exception',
    'args' => 
    array (
      0 => 
      \TypeError::__set_state(array(
         'message' => 'json_response(): Argument #1 ($content) must be of type array, null given, called in /Users/leam/Desktop/easyappointments/application/controllers/Booking_auth.php on line 149',
         'string' => '',
         'code' => 0,
         'file' => '/Users/leam/Desktop/easyappointments/application/helpers/http_helper.php',
         'line' => 108,
         'trace' => 
        array (
          0 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Booking_auth.php',
            'line' => 149,
            'function' => 'json_response',
            'args' => 
            array (
              0 => NULL,
            ),
          ),
          1 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 481,
            'function' => 'check_session',
            'class' => 'Booking_auth',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          2 => 
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
    'function' => 'check_session',
    'class' => 'Booking_auth',
    'type' => '->',
    'args' => 
    array (
    ),
  ),
)
ERROR - 2026-03-17 21:16:23 --> JSON exception: {"success":false,"message":"Not all required fields are provided: Array\n(\n    [first_name] => Leam\n    [last_name] => Waddington\n    [email] => Leam@smarterdog.co.uk\n    [phone_number] => \n)\n","trace":"array (\n  0 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Customers_model.php',\n    'line' => 69,\n    'function' => 'validate',\n    'class' => 'Customers_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => \n      array (\n        'first_name' => 'Leam',\n        'last_name' => 'Waddington',\n        'email' => 'Leam@smarterdog.co.uk',\n        'phone_number' => '',\n      ),\n    ),\n  ),\n  1 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/controllers\/Booking_auth.php',\n    'line' => 122,\n    'function' => 'save',\n    'class' => 'Customers_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => \n      array (\n        'first_name' => 'Leam',\n        'last_name' => 'Waddington',\n        'email' => 'Leam@smarterdog.co.uk',\n        'phone_number' => '',\n      ),\n    ),\n  ),\n  2 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    'line' => 481,\n    'function' => 'register',\n    'class' => 'Booking_auth',\n    'type' => '->',\n    'args' => \n    array (\n    ),\n  ),\n  3 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/index.php',\n    'line' => 331,\n    'args' => \n    array (\n      0 => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    ),\n    'function' => 'require_once',\n  ),\n)"} Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/helpers/http_helper.php',
    'line' => 142,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'JSON exception: {"success":false,"message":"Not all required fields are provided: Array\\n(\\n    [first_name] => Leam\\n    [last_name] => Waddington\\n    [email] => Leam@smarterdog.co.uk\\n    [phone_number] => \\n)\\n","trace":"array (\\n  0 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Customers_model.php\',\\n    \'line\' => 69,\\n    \'function\' => \'validate\',\\n    \'class\' => \'Customers_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \\n      array (\\n        \'first_name\' => \'Leam\',\\n        \'last_name\' => \'Waddington\',\\n        \'email\' => \'Leam@smarterdog.co.uk\',\\n        \'phone_number\' => \'\',\\n      ),\\n    ),\\n  ),\\n  1 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/controllers\\/Booking_auth.php\',\\n    \'line\' => 122,\\n    \'function\' => \'save\',\\n    \'class\' => \'Customers_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \\n      array (\\n        \'first_name\' => \'Leam\',\\n        \'last_name\' => \'Waddington\',\\n        \'email\' => \'Leam@smarterdog.co.uk\',\\n        \'phone_number\' => \'\',\\n      ),\\n    ),\\n  ),\\n  2 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    \'line\' => 481,\\n    \'function\' => \'register\',\\n    \'class\' => \'Booking_auth\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n    ),\\n  ),\\n  3 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/index.php\',\\n    \'line\' => 331,\\n    \'args\' => \\n    array (\\n      0 => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    ),\\n    \'function\' => \'require_once\',\\n  ),\\n)"}',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Booking_auth.php',
    'line' => 136,
    'function' => 'json_exception',
    'args' => 
    array (
      0 => 
      \InvalidArgumentException::__set_state(array(
         'message' => 'Not all required fields are provided: Array
(
    [first_name] => Leam
    [last_name] => Waddington
    [email] => Leam@smarterdog.co.uk
    [phone_number] => 
)
',
         'string' => '',
         'code' => 0,
         'file' => '/Users/leam/Desktop/easyappointments/application/models/Customers_model.php',
         'line' => 120,
         'trace' => 
        array (
          0 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Customers_model.php',
            'line' => 69,
            'function' => 'validate',
            'class' => 'Customers_model',
            'type' => '->',
            'args' => 
            array (
              0 => 
              array (
                'first_name' => 'Leam',
                'last_name' => 'Waddington',
                'email' => 'Leam@smarterdog.co.uk',
                'phone_number' => '',
              ),
            ),
          ),
          1 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Booking_auth.php',
            'line' => 122,
            'function' => 'save',
            'class' => 'Customers_model',
            'type' => '->',
            'args' => 
            array (
              0 => 
              array (
                'first_name' => 'Leam',
                'last_name' => 'Waddington',
                'email' => 'Leam@smarterdog.co.uk',
                'phone_number' => '',
              ),
            ),
          ),
          2 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 481,
            'function' => 'register',
            'class' => 'Booking_auth',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          3 => 
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
    'function' => 'register',
    'class' => 'Booking_auth',
    'type' => '->',
    'args' => 
    array (
    ),
  ),
)
