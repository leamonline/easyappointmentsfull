<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2026-03-14 11:22:28 --> JSON exception: {"success":false,"message":"Table 'easyappointments.ea_pets' doesn't exist","trace":"array (\n  0 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/drivers\/mysqli\/mysqli_driver.php',\n    'line' => 301,\n    'function' => 'query',\n    'class' => 'mysqli',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  1 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 746,\n    'function' => '_execute',\n    'class' => 'CI_DB_mysqli_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  2 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 636,\n    'function' => 'simple_query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  3 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_query_builder.php',\n    'line' => 1455,\n    'function' => 'query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  4 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 197,\n    'function' => 'get',\n    'class' => 'CI_DB_query_builder',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'pets',\n      1 => NULL,\n      2 => NULL,\n    ),\n  ),\n  5 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 215,\n    'function' => 'get',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => \n      array (\n        'id_users_customer' => 3,\n      ),\n    ),\n  ),\n  6 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/controllers\/Calendar.php',\n    'line' => 781,\n    'function' => 'get_by_customer',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 3,\n    ),\n  ),\n  7 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    'line' => 481,\n    'function' => 'get_calendar_appointments',\n    'class' => 'Calendar',\n    'type' => '->',\n    'args' => \n    array (\n    ),\n  ),\n  8 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/index.php',\n    'line' => 331,\n    'args' => \n    array (\n      0 => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    ),\n    'function' => 'require_once',\n  ),\n)"} Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/helpers/http_helper.php',
    'line' => 142,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'JSON exception: {"success":false,"message":"Table \'easyappointments.ea_pets\' doesn\'t exist","trace":"array (\\n  0 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/drivers\\/mysqli\\/mysqli_driver.php\',\\n    \'line\' => 301,\\n    \'function\' => \'query\',\\n    \'class\' => \'mysqli\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  1 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 746,\\n    \'function\' => \'_execute\',\\n    \'class\' => \'CI_DB_mysqli_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  2 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 636,\\n    \'function\' => \'simple_query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  3 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_query_builder.php\',\\n    \'line\' => 1455,\\n    \'function\' => \'query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  4 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 197,\\n    \'function\' => \'get\',\\n    \'class\' => \'CI_DB_query_builder\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'pets\',\\n      1 => NULL,\\n      2 => NULL,\\n    ),\\n  ),\\n  5 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 215,\\n    \'function\' => \'get\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \\n      array (\\n        \'id_users_customer\' => 3,\\n      ),\\n    ),\\n  ),\\n  6 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/controllers\\/Calendar.php\',\\n    \'line\' => 781,\\n    \'function\' => \'get_by_customer\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => 3,\\n    ),\\n  ),\\n  7 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    \'line\' => 481,\\n    \'function\' => \'get_calendar_appointments\',\\n    \'class\' => \'Calendar\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n    ),\\n  ),\\n  8 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/index.php\',\\n    \'line\' => 331,\\n    \'args\' => \\n    array (\\n      0 => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    ),\\n    \'function\' => \'require_once\',\\n  ),\\n)"}',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
    'line' => 875,
    'function' => 'json_exception',
    'args' => 
    array (
      0 => 
      \mysqli_sql_exception::__set_state(array(
         'message' => 'Table \'easyappointments.ea_pets\' doesn\'t exist',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          3 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/database/DB_query_builder.php',
            'line' => 1455,
            'function' => 'query',
            'class' => 'CI_DB_driver',
            'type' => '->',
            'args' => 
            array (
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          4 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 197,
            'function' => 'get',
            'class' => 'CI_DB_query_builder',
            'type' => '->',
            'args' => 
            array (
              0 => 'pets',
              1 => NULL,
              2 => NULL,
            ),
          ),
          5 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 215,
            'function' => 'get',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 
              array (
                'id_users_customer' => 3,
              ),
            ),
          ),
          6 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
            'line' => 781,
            'function' => 'get_by_customer',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 3,
            ),
          ),
          7 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 481,
            'function' => 'get_calendar_appointments',
            'class' => 'Calendar',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          8 => 
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
  2 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
    'line' => 481,
    'function' => 'get_calendar_appointments',
    'class' => 'Calendar',
    'type' => '->',
    'args' => 
    array (
    ),
  ),
)
ERROR - 2026-03-14 11:23:28 --> JSON exception: {"success":false,"message":"Table 'easyappointments.ea_pets' doesn't exist","trace":"array (\n  0 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/drivers\/mysqli\/mysqli_driver.php',\n    'line' => 301,\n    'function' => 'query',\n    'class' => 'mysqli',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  1 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 746,\n    'function' => '_execute',\n    'class' => 'CI_DB_mysqli_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  2 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 636,\n    'function' => 'simple_query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  3 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_query_builder.php',\n    'line' => 1455,\n    'function' => 'query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  4 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 197,\n    'function' => 'get',\n    'class' => 'CI_DB_query_builder',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'pets',\n      1 => NULL,\n      2 => NULL,\n    ),\n  ),\n  5 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 215,\n    'function' => 'get',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => \n      array (\n        'id_users_customer' => 3,\n      ),\n    ),\n  ),\n  6 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/controllers\/Calendar.php',\n    'line' => 781,\n    'function' => 'get_by_customer',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 3,\n    ),\n  ),\n  7 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    'line' => 481,\n    'function' => 'get_calendar_appointments',\n    'class' => 'Calendar',\n    'type' => '->',\n    'args' => \n    array (\n    ),\n  ),\n  8 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/index.php',\n    'line' => 331,\n    'args' => \n    array (\n      0 => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    ),\n    'function' => 'require_once',\n  ),\n)"} Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/helpers/http_helper.php',
    'line' => 142,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'JSON exception: {"success":false,"message":"Table \'easyappointments.ea_pets\' doesn\'t exist","trace":"array (\\n  0 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/drivers\\/mysqli\\/mysqli_driver.php\',\\n    \'line\' => 301,\\n    \'function\' => \'query\',\\n    \'class\' => \'mysqli\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  1 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 746,\\n    \'function\' => \'_execute\',\\n    \'class\' => \'CI_DB_mysqli_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  2 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 636,\\n    \'function\' => \'simple_query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  3 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_query_builder.php\',\\n    \'line\' => 1455,\\n    \'function\' => \'query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  4 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 197,\\n    \'function\' => \'get\',\\n    \'class\' => \'CI_DB_query_builder\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'pets\',\\n      1 => NULL,\\n      2 => NULL,\\n    ),\\n  ),\\n  5 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 215,\\n    \'function\' => \'get\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \\n      array (\\n        \'id_users_customer\' => 3,\\n      ),\\n    ),\\n  ),\\n  6 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/controllers\\/Calendar.php\',\\n    \'line\' => 781,\\n    \'function\' => \'get_by_customer\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => 3,\\n    ),\\n  ),\\n  7 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    \'line\' => 481,\\n    \'function\' => \'get_calendar_appointments\',\\n    \'class\' => \'Calendar\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n    ),\\n  ),\\n  8 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/index.php\',\\n    \'line\' => 331,\\n    \'args\' => \\n    array (\\n      0 => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    ),\\n    \'function\' => \'require_once\',\\n  ),\\n)"}',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
    'line' => 875,
    'function' => 'json_exception',
    'args' => 
    array (
      0 => 
      \mysqli_sql_exception::__set_state(array(
         'message' => 'Table \'easyappointments.ea_pets\' doesn\'t exist',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          3 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/database/DB_query_builder.php',
            'line' => 1455,
            'function' => 'query',
            'class' => 'CI_DB_driver',
            'type' => '->',
            'args' => 
            array (
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          4 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 197,
            'function' => 'get',
            'class' => 'CI_DB_query_builder',
            'type' => '->',
            'args' => 
            array (
              0 => 'pets',
              1 => NULL,
              2 => NULL,
            ),
          ),
          5 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 215,
            'function' => 'get',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 
              array (
                'id_users_customer' => 3,
              ),
            ),
          ),
          6 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
            'line' => 781,
            'function' => 'get_by_customer',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 3,
            ),
          ),
          7 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 481,
            'function' => 'get_calendar_appointments',
            'class' => 'Calendar',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          8 => 
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
  2 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
    'line' => 481,
    'function' => 'get_calendar_appointments',
    'class' => 'Calendar',
    'type' => '->',
    'args' => 
    array (
    ),
  ),
)
ERROR - 2026-03-14 11:24:28 --> JSON exception: {"success":false,"message":"Table 'easyappointments.ea_pets' doesn't exist","trace":"array (\n  0 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/drivers\/mysqli\/mysqli_driver.php',\n    'line' => 301,\n    'function' => 'query',\n    'class' => 'mysqli',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  1 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 746,\n    'function' => '_execute',\n    'class' => 'CI_DB_mysqli_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  2 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 636,\n    'function' => 'simple_query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  3 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_query_builder.php',\n    'line' => 1455,\n    'function' => 'query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  4 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 197,\n    'function' => 'get',\n    'class' => 'CI_DB_query_builder',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'pets',\n      1 => NULL,\n      2 => NULL,\n    ),\n  ),\n  5 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 215,\n    'function' => 'get',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => \n      array (\n        'id_users_customer' => 3,\n      ),\n    ),\n  ),\n  6 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/controllers\/Calendar.php',\n    'line' => 781,\n    'function' => 'get_by_customer',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 3,\n    ),\n  ),\n  7 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    'line' => 481,\n    'function' => 'get_calendar_appointments',\n    'class' => 'Calendar',\n    'type' => '->',\n    'args' => \n    array (\n    ),\n  ),\n  8 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/index.php',\n    'line' => 331,\n    'args' => \n    array (\n      0 => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    ),\n    'function' => 'require_once',\n  ),\n)"} Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/helpers/http_helper.php',
    'line' => 142,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'JSON exception: {"success":false,"message":"Table \'easyappointments.ea_pets\' doesn\'t exist","trace":"array (\\n  0 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/drivers\\/mysqli\\/mysqli_driver.php\',\\n    \'line\' => 301,\\n    \'function\' => \'query\',\\n    \'class\' => \'mysqli\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  1 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 746,\\n    \'function\' => \'_execute\',\\n    \'class\' => \'CI_DB_mysqli_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  2 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 636,\\n    \'function\' => \'simple_query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  3 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_query_builder.php\',\\n    \'line\' => 1455,\\n    \'function\' => \'query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  4 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 197,\\n    \'function\' => \'get\',\\n    \'class\' => \'CI_DB_query_builder\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'pets\',\\n      1 => NULL,\\n      2 => NULL,\\n    ),\\n  ),\\n  5 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 215,\\n    \'function\' => \'get\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \\n      array (\\n        \'id_users_customer\' => 3,\\n      ),\\n    ),\\n  ),\\n  6 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/controllers\\/Calendar.php\',\\n    \'line\' => 781,\\n    \'function\' => \'get_by_customer\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => 3,\\n    ),\\n  ),\\n  7 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    \'line\' => 481,\\n    \'function\' => \'get_calendar_appointments\',\\n    \'class\' => \'Calendar\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n    ),\\n  ),\\n  8 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/index.php\',\\n    \'line\' => 331,\\n    \'args\' => \\n    array (\\n      0 => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    ),\\n    \'function\' => \'require_once\',\\n  ),\\n)"}',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
    'line' => 875,
    'function' => 'json_exception',
    'args' => 
    array (
      0 => 
      \mysqli_sql_exception::__set_state(array(
         'message' => 'Table \'easyappointments.ea_pets\' doesn\'t exist',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          3 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/database/DB_query_builder.php',
            'line' => 1455,
            'function' => 'query',
            'class' => 'CI_DB_driver',
            'type' => '->',
            'args' => 
            array (
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          4 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 197,
            'function' => 'get',
            'class' => 'CI_DB_query_builder',
            'type' => '->',
            'args' => 
            array (
              0 => 'pets',
              1 => NULL,
              2 => NULL,
            ),
          ),
          5 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 215,
            'function' => 'get',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 
              array (
                'id_users_customer' => 3,
              ),
            ),
          ),
          6 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
            'line' => 781,
            'function' => 'get_by_customer',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 3,
            ),
          ),
          7 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 481,
            'function' => 'get_calendar_appointments',
            'class' => 'Calendar',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          8 => 
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
  2 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
    'line' => 481,
    'function' => 'get_calendar_appointments',
    'class' => 'Calendar',
    'type' => '->',
    'args' => 
    array (
    ),
  ),
)
ERROR - 2026-03-14 11:25:28 --> JSON exception: {"success":false,"message":"Table 'easyappointments.ea_pets' doesn't exist","trace":"array (\n  0 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/drivers\/mysqli\/mysqli_driver.php',\n    'line' => 301,\n    'function' => 'query',\n    'class' => 'mysqli',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  1 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 746,\n    'function' => '_execute',\n    'class' => 'CI_DB_mysqli_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  2 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 636,\n    'function' => 'simple_query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  3 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_query_builder.php',\n    'line' => 1455,\n    'function' => 'query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  4 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 197,\n    'function' => 'get',\n    'class' => 'CI_DB_query_builder',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'pets',\n      1 => NULL,\n      2 => NULL,\n    ),\n  ),\n  5 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 215,\n    'function' => 'get',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => \n      array (\n        'id_users_customer' => 3,\n      ),\n    ),\n  ),\n  6 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/controllers\/Calendar.php',\n    'line' => 781,\n    'function' => 'get_by_customer',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 3,\n    ),\n  ),\n  7 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    'line' => 481,\n    'function' => 'get_calendar_appointments',\n    'class' => 'Calendar',\n    'type' => '->',\n    'args' => \n    array (\n    ),\n  ),\n  8 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/index.php',\n    'line' => 331,\n    'args' => \n    array (\n      0 => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    ),\n    'function' => 'require_once',\n  ),\n)"} Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/helpers/http_helper.php',
    'line' => 142,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'JSON exception: {"success":false,"message":"Table \'easyappointments.ea_pets\' doesn\'t exist","trace":"array (\\n  0 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/drivers\\/mysqli\\/mysqli_driver.php\',\\n    \'line\' => 301,\\n    \'function\' => \'query\',\\n    \'class\' => \'mysqli\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  1 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 746,\\n    \'function\' => \'_execute\',\\n    \'class\' => \'CI_DB_mysqli_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  2 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 636,\\n    \'function\' => \'simple_query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  3 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_query_builder.php\',\\n    \'line\' => 1455,\\n    \'function\' => \'query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  4 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 197,\\n    \'function\' => \'get\',\\n    \'class\' => \'CI_DB_query_builder\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'pets\',\\n      1 => NULL,\\n      2 => NULL,\\n    ),\\n  ),\\n  5 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 215,\\n    \'function\' => \'get\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \\n      array (\\n        \'id_users_customer\' => 3,\\n      ),\\n    ),\\n  ),\\n  6 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/controllers\\/Calendar.php\',\\n    \'line\' => 781,\\n    \'function\' => \'get_by_customer\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => 3,\\n    ),\\n  ),\\n  7 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    \'line\' => 481,\\n    \'function\' => \'get_calendar_appointments\',\\n    \'class\' => \'Calendar\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n    ),\\n  ),\\n  8 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/index.php\',\\n    \'line\' => 331,\\n    \'args\' => \\n    array (\\n      0 => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    ),\\n    \'function\' => \'require_once\',\\n  ),\\n)"}',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
    'line' => 875,
    'function' => 'json_exception',
    'args' => 
    array (
      0 => 
      \mysqli_sql_exception::__set_state(array(
         'message' => 'Table \'easyappointments.ea_pets\' doesn\'t exist',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          3 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/database/DB_query_builder.php',
            'line' => 1455,
            'function' => 'query',
            'class' => 'CI_DB_driver',
            'type' => '->',
            'args' => 
            array (
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          4 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 197,
            'function' => 'get',
            'class' => 'CI_DB_query_builder',
            'type' => '->',
            'args' => 
            array (
              0 => 'pets',
              1 => NULL,
              2 => NULL,
            ),
          ),
          5 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 215,
            'function' => 'get',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 
              array (
                'id_users_customer' => 3,
              ),
            ),
          ),
          6 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
            'line' => 781,
            'function' => 'get_by_customer',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 3,
            ),
          ),
          7 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 481,
            'function' => 'get_calendar_appointments',
            'class' => 'Calendar',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          8 => 
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
  2 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
    'line' => 481,
    'function' => 'get_calendar_appointments',
    'class' => 'Calendar',
    'type' => '->',
    'args' => 
    array (
    ),
  ),
)
ERROR - 2026-03-14 11:26:28 --> JSON exception: {"success":false,"message":"Table 'easyappointments.ea_pets' doesn't exist","trace":"array (\n  0 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/drivers\/mysqli\/mysqli_driver.php',\n    'line' => 301,\n    'function' => 'query',\n    'class' => 'mysqli',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  1 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 746,\n    'function' => '_execute',\n    'class' => 'CI_DB_mysqli_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  2 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 636,\n    'function' => 'simple_query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  3 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_query_builder.php',\n    'line' => 1455,\n    'function' => 'query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  4 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 197,\n    'function' => 'get',\n    'class' => 'CI_DB_query_builder',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'pets',\n      1 => NULL,\n      2 => NULL,\n    ),\n  ),\n  5 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 215,\n    'function' => 'get',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => \n      array (\n        'id_users_customer' => 3,\n      ),\n    ),\n  ),\n  6 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/controllers\/Calendar.php',\n    'line' => 781,\n    'function' => 'get_by_customer',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 3,\n    ),\n  ),\n  7 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    'line' => 481,\n    'function' => 'get_calendar_appointments',\n    'class' => 'Calendar',\n    'type' => '->',\n    'args' => \n    array (\n    ),\n  ),\n  8 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/index.php',\n    'line' => 331,\n    'args' => \n    array (\n      0 => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    ),\n    'function' => 'require_once',\n  ),\n)"} Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/helpers/http_helper.php',
    'line' => 142,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'JSON exception: {"success":false,"message":"Table \'easyappointments.ea_pets\' doesn\'t exist","trace":"array (\\n  0 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/drivers\\/mysqli\\/mysqli_driver.php\',\\n    \'line\' => 301,\\n    \'function\' => \'query\',\\n    \'class\' => \'mysqli\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  1 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 746,\\n    \'function\' => \'_execute\',\\n    \'class\' => \'CI_DB_mysqli_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  2 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 636,\\n    \'function\' => \'simple_query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  3 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_query_builder.php\',\\n    \'line\' => 1455,\\n    \'function\' => \'query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  4 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 197,\\n    \'function\' => \'get\',\\n    \'class\' => \'CI_DB_query_builder\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'pets\',\\n      1 => NULL,\\n      2 => NULL,\\n    ),\\n  ),\\n  5 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 215,\\n    \'function\' => \'get\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \\n      array (\\n        \'id_users_customer\' => 3,\\n      ),\\n    ),\\n  ),\\n  6 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/controllers\\/Calendar.php\',\\n    \'line\' => 781,\\n    \'function\' => \'get_by_customer\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => 3,\\n    ),\\n  ),\\n  7 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    \'line\' => 481,\\n    \'function\' => \'get_calendar_appointments\',\\n    \'class\' => \'Calendar\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n    ),\\n  ),\\n  8 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/index.php\',\\n    \'line\' => 331,\\n    \'args\' => \\n    array (\\n      0 => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    ),\\n    \'function\' => \'require_once\',\\n  ),\\n)"}',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
    'line' => 875,
    'function' => 'json_exception',
    'args' => 
    array (
      0 => 
      \mysqli_sql_exception::__set_state(array(
         'message' => 'Table \'easyappointments.ea_pets\' doesn\'t exist',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          3 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/database/DB_query_builder.php',
            'line' => 1455,
            'function' => 'query',
            'class' => 'CI_DB_driver',
            'type' => '->',
            'args' => 
            array (
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          4 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 197,
            'function' => 'get',
            'class' => 'CI_DB_query_builder',
            'type' => '->',
            'args' => 
            array (
              0 => 'pets',
              1 => NULL,
              2 => NULL,
            ),
          ),
          5 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 215,
            'function' => 'get',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 
              array (
                'id_users_customer' => 3,
              ),
            ),
          ),
          6 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
            'line' => 781,
            'function' => 'get_by_customer',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 3,
            ),
          ),
          7 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 481,
            'function' => 'get_calendar_appointments',
            'class' => 'Calendar',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          8 => 
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
  2 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
    'line' => 481,
    'function' => 'get_calendar_appointments',
    'class' => 'Calendar',
    'type' => '->',
    'args' => 
    array (
    ),
  ),
)
ERROR - 2026-03-14 11:27:28 --> JSON exception: {"success":false,"message":"Table 'easyappointments.ea_pets' doesn't exist","trace":"array (\n  0 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/drivers\/mysqli\/mysqli_driver.php',\n    'line' => 301,\n    'function' => 'query',\n    'class' => 'mysqli',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  1 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 746,\n    'function' => '_execute',\n    'class' => 'CI_DB_mysqli_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  2 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 636,\n    'function' => 'simple_query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  3 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_query_builder.php',\n    'line' => 1455,\n    'function' => 'query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  4 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 197,\n    'function' => 'get',\n    'class' => 'CI_DB_query_builder',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'pets',\n      1 => NULL,\n      2 => NULL,\n    ),\n  ),\n  5 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 215,\n    'function' => 'get',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => \n      array (\n        'id_users_customer' => 3,\n      ),\n    ),\n  ),\n  6 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/controllers\/Calendar.php',\n    'line' => 781,\n    'function' => 'get_by_customer',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 3,\n    ),\n  ),\n  7 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    'line' => 481,\n    'function' => 'get_calendar_appointments',\n    'class' => 'Calendar',\n    'type' => '->',\n    'args' => \n    array (\n    ),\n  ),\n  8 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/index.php',\n    'line' => 331,\n    'args' => \n    array (\n      0 => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    ),\n    'function' => 'require_once',\n  ),\n)"} Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/helpers/http_helper.php',
    'line' => 142,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'JSON exception: {"success":false,"message":"Table \'easyappointments.ea_pets\' doesn\'t exist","trace":"array (\\n  0 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/drivers\\/mysqli\\/mysqli_driver.php\',\\n    \'line\' => 301,\\n    \'function\' => \'query\',\\n    \'class\' => \'mysqli\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  1 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 746,\\n    \'function\' => \'_execute\',\\n    \'class\' => \'CI_DB_mysqli_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  2 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 636,\\n    \'function\' => \'simple_query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  3 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_query_builder.php\',\\n    \'line\' => 1455,\\n    \'function\' => \'query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  4 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 197,\\n    \'function\' => \'get\',\\n    \'class\' => \'CI_DB_query_builder\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'pets\',\\n      1 => NULL,\\n      2 => NULL,\\n    ),\\n  ),\\n  5 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 215,\\n    \'function\' => \'get\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \\n      array (\\n        \'id_users_customer\' => 3,\\n      ),\\n    ),\\n  ),\\n  6 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/controllers\\/Calendar.php\',\\n    \'line\' => 781,\\n    \'function\' => \'get_by_customer\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => 3,\\n    ),\\n  ),\\n  7 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    \'line\' => 481,\\n    \'function\' => \'get_calendar_appointments\',\\n    \'class\' => \'Calendar\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n    ),\\n  ),\\n  8 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/index.php\',\\n    \'line\' => 331,\\n    \'args\' => \\n    array (\\n      0 => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    ),\\n    \'function\' => \'require_once\',\\n  ),\\n)"}',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
    'line' => 875,
    'function' => 'json_exception',
    'args' => 
    array (
      0 => 
      \mysqli_sql_exception::__set_state(array(
         'message' => 'Table \'easyappointments.ea_pets\' doesn\'t exist',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          3 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/database/DB_query_builder.php',
            'line' => 1455,
            'function' => 'query',
            'class' => 'CI_DB_driver',
            'type' => '->',
            'args' => 
            array (
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          4 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 197,
            'function' => 'get',
            'class' => 'CI_DB_query_builder',
            'type' => '->',
            'args' => 
            array (
              0 => 'pets',
              1 => NULL,
              2 => NULL,
            ),
          ),
          5 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 215,
            'function' => 'get',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 
              array (
                'id_users_customer' => 3,
              ),
            ),
          ),
          6 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
            'line' => 781,
            'function' => 'get_by_customer',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 3,
            ),
          ),
          7 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 481,
            'function' => 'get_calendar_appointments',
            'class' => 'Calendar',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          8 => 
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
  2 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
    'line' => 481,
    'function' => 'get_calendar_appointments',
    'class' => 'Calendar',
    'type' => '->',
    'args' => 
    array (
    ),
  ),
)
ERROR - 2026-03-14 11:28:28 --> JSON exception: {"success":false,"message":"Table 'easyappointments.ea_pets' doesn't exist","trace":"array (\n  0 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/drivers\/mysqli\/mysqli_driver.php',\n    'line' => 301,\n    'function' => 'query',\n    'class' => 'mysqli',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  1 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 746,\n    'function' => '_execute',\n    'class' => 'CI_DB_mysqli_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  2 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 636,\n    'function' => 'simple_query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  3 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_query_builder.php',\n    'line' => 1455,\n    'function' => 'query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  4 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 197,\n    'function' => 'get',\n    'class' => 'CI_DB_query_builder',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'pets',\n      1 => NULL,\n      2 => NULL,\n    ),\n  ),\n  5 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 215,\n    'function' => 'get',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => \n      array (\n        'id_users_customer' => 3,\n      ),\n    ),\n  ),\n  6 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/controllers\/Calendar.php',\n    'line' => 781,\n    'function' => 'get_by_customer',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 3,\n    ),\n  ),\n  7 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    'line' => 481,\n    'function' => 'get_calendar_appointments',\n    'class' => 'Calendar',\n    'type' => '->',\n    'args' => \n    array (\n    ),\n  ),\n  8 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/index.php',\n    'line' => 331,\n    'args' => \n    array (\n      0 => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    ),\n    'function' => 'require_once',\n  ),\n)"} Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/helpers/http_helper.php',
    'line' => 142,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'JSON exception: {"success":false,"message":"Table \'easyappointments.ea_pets\' doesn\'t exist","trace":"array (\\n  0 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/drivers\\/mysqli\\/mysqli_driver.php\',\\n    \'line\' => 301,\\n    \'function\' => \'query\',\\n    \'class\' => \'mysqli\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  1 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 746,\\n    \'function\' => \'_execute\',\\n    \'class\' => \'CI_DB_mysqli_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  2 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 636,\\n    \'function\' => \'simple_query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  3 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_query_builder.php\',\\n    \'line\' => 1455,\\n    \'function\' => \'query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  4 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 197,\\n    \'function\' => \'get\',\\n    \'class\' => \'CI_DB_query_builder\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'pets\',\\n      1 => NULL,\\n      2 => NULL,\\n    ),\\n  ),\\n  5 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 215,\\n    \'function\' => \'get\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \\n      array (\\n        \'id_users_customer\' => 3,\\n      ),\\n    ),\\n  ),\\n  6 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/controllers\\/Calendar.php\',\\n    \'line\' => 781,\\n    \'function\' => \'get_by_customer\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => 3,\\n    ),\\n  ),\\n  7 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    \'line\' => 481,\\n    \'function\' => \'get_calendar_appointments\',\\n    \'class\' => \'Calendar\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n    ),\\n  ),\\n  8 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/index.php\',\\n    \'line\' => 331,\\n    \'args\' => \\n    array (\\n      0 => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    ),\\n    \'function\' => \'require_once\',\\n  ),\\n)"}',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
    'line' => 875,
    'function' => 'json_exception',
    'args' => 
    array (
      0 => 
      \mysqli_sql_exception::__set_state(array(
         'message' => 'Table \'easyappointments.ea_pets\' doesn\'t exist',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          3 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/database/DB_query_builder.php',
            'line' => 1455,
            'function' => 'query',
            'class' => 'CI_DB_driver',
            'type' => '->',
            'args' => 
            array (
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          4 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 197,
            'function' => 'get',
            'class' => 'CI_DB_query_builder',
            'type' => '->',
            'args' => 
            array (
              0 => 'pets',
              1 => NULL,
              2 => NULL,
            ),
          ),
          5 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 215,
            'function' => 'get',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 
              array (
                'id_users_customer' => 3,
              ),
            ),
          ),
          6 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
            'line' => 781,
            'function' => 'get_by_customer',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 3,
            ),
          ),
          7 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 481,
            'function' => 'get_calendar_appointments',
            'class' => 'Calendar',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          8 => 
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
  2 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
    'line' => 481,
    'function' => 'get_calendar_appointments',
    'class' => 'Calendar',
    'type' => '->',
    'args' => 
    array (
    ),
  ),
)
ERROR - 2026-03-14 11:29:28 --> JSON exception: {"success":false,"message":"Table 'easyappointments.ea_pets' doesn't exist","trace":"array (\n  0 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/drivers\/mysqli\/mysqli_driver.php',\n    'line' => 301,\n    'function' => 'query',\n    'class' => 'mysqli',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  1 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 746,\n    'function' => '_execute',\n    'class' => 'CI_DB_mysqli_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  2 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 636,\n    'function' => 'simple_query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  3 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_query_builder.php',\n    'line' => 1455,\n    'function' => 'query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  4 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 197,\n    'function' => 'get',\n    'class' => 'CI_DB_query_builder',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'pets',\n      1 => NULL,\n      2 => NULL,\n    ),\n  ),\n  5 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 215,\n    'function' => 'get',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => \n      array (\n        'id_users_customer' => 3,\n      ),\n    ),\n  ),\n  6 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/controllers\/Calendar.php',\n    'line' => 781,\n    'function' => 'get_by_customer',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 3,\n    ),\n  ),\n  7 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    'line' => 481,\n    'function' => 'get_calendar_appointments',\n    'class' => 'Calendar',\n    'type' => '->',\n    'args' => \n    array (\n    ),\n  ),\n  8 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/index.php',\n    'line' => 331,\n    'args' => \n    array (\n      0 => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    ),\n    'function' => 'require_once',\n  ),\n)"} Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/helpers/http_helper.php',
    'line' => 142,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'JSON exception: {"success":false,"message":"Table \'easyappointments.ea_pets\' doesn\'t exist","trace":"array (\\n  0 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/drivers\\/mysqli\\/mysqli_driver.php\',\\n    \'line\' => 301,\\n    \'function\' => \'query\',\\n    \'class\' => \'mysqli\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  1 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 746,\\n    \'function\' => \'_execute\',\\n    \'class\' => \'CI_DB_mysqli_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  2 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 636,\\n    \'function\' => \'simple_query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  3 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_query_builder.php\',\\n    \'line\' => 1455,\\n    \'function\' => \'query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  4 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 197,\\n    \'function\' => \'get\',\\n    \'class\' => \'CI_DB_query_builder\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'pets\',\\n      1 => NULL,\\n      2 => NULL,\\n    ),\\n  ),\\n  5 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 215,\\n    \'function\' => \'get\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \\n      array (\\n        \'id_users_customer\' => 3,\\n      ),\\n    ),\\n  ),\\n  6 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/controllers\\/Calendar.php\',\\n    \'line\' => 781,\\n    \'function\' => \'get_by_customer\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => 3,\\n    ),\\n  ),\\n  7 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    \'line\' => 481,\\n    \'function\' => \'get_calendar_appointments\',\\n    \'class\' => \'Calendar\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n    ),\\n  ),\\n  8 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/index.php\',\\n    \'line\' => 331,\\n    \'args\' => \\n    array (\\n      0 => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    ),\\n    \'function\' => \'require_once\',\\n  ),\\n)"}',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
    'line' => 875,
    'function' => 'json_exception',
    'args' => 
    array (
      0 => 
      \mysqli_sql_exception::__set_state(array(
         'message' => 'Table \'easyappointments.ea_pets\' doesn\'t exist',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          3 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/database/DB_query_builder.php',
            'line' => 1455,
            'function' => 'query',
            'class' => 'CI_DB_driver',
            'type' => '->',
            'args' => 
            array (
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          4 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 197,
            'function' => 'get',
            'class' => 'CI_DB_query_builder',
            'type' => '->',
            'args' => 
            array (
              0 => 'pets',
              1 => NULL,
              2 => NULL,
            ),
          ),
          5 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 215,
            'function' => 'get',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 
              array (
                'id_users_customer' => 3,
              ),
            ),
          ),
          6 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
            'line' => 781,
            'function' => 'get_by_customer',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 3,
            ),
          ),
          7 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 481,
            'function' => 'get_calendar_appointments',
            'class' => 'Calendar',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          8 => 
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
  2 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
    'line' => 481,
    'function' => 'get_calendar_appointments',
    'class' => 'Calendar',
    'type' => '->',
    'args' => 
    array (
    ),
  ),
)
ERROR - 2026-03-14 11:30:28 --> JSON exception: {"success":false,"message":"Table 'easyappointments.ea_pets' doesn't exist","trace":"array (\n  0 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/drivers\/mysqli\/mysqli_driver.php',\n    'line' => 301,\n    'function' => 'query',\n    'class' => 'mysqli',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  1 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 746,\n    'function' => '_execute',\n    'class' => 'CI_DB_mysqli_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  2 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 636,\n    'function' => 'simple_query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  3 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_query_builder.php',\n    'line' => 1455,\n    'function' => 'query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  4 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 197,\n    'function' => 'get',\n    'class' => 'CI_DB_query_builder',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'pets',\n      1 => NULL,\n      2 => NULL,\n    ),\n  ),\n  5 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 215,\n    'function' => 'get',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => \n      array (\n        'id_users_customer' => 3,\n      ),\n    ),\n  ),\n  6 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/controllers\/Calendar.php',\n    'line' => 781,\n    'function' => 'get_by_customer',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 3,\n    ),\n  ),\n  7 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    'line' => 481,\n    'function' => 'get_calendar_appointments',\n    'class' => 'Calendar',\n    'type' => '->',\n    'args' => \n    array (\n    ),\n  ),\n  8 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/index.php',\n    'line' => 331,\n    'args' => \n    array (\n      0 => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    ),\n    'function' => 'require_once',\n  ),\n)"} Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/helpers/http_helper.php',
    'line' => 142,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'JSON exception: {"success":false,"message":"Table \'easyappointments.ea_pets\' doesn\'t exist","trace":"array (\\n  0 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/drivers\\/mysqli\\/mysqli_driver.php\',\\n    \'line\' => 301,\\n    \'function\' => \'query\',\\n    \'class\' => \'mysqli\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  1 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 746,\\n    \'function\' => \'_execute\',\\n    \'class\' => \'CI_DB_mysqli_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  2 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 636,\\n    \'function\' => \'simple_query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  3 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_query_builder.php\',\\n    \'line\' => 1455,\\n    \'function\' => \'query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  4 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 197,\\n    \'function\' => \'get\',\\n    \'class\' => \'CI_DB_query_builder\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'pets\',\\n      1 => NULL,\\n      2 => NULL,\\n    ),\\n  ),\\n  5 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 215,\\n    \'function\' => \'get\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \\n      array (\\n        \'id_users_customer\' => 3,\\n      ),\\n    ),\\n  ),\\n  6 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/controllers\\/Calendar.php\',\\n    \'line\' => 781,\\n    \'function\' => \'get_by_customer\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => 3,\\n    ),\\n  ),\\n  7 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    \'line\' => 481,\\n    \'function\' => \'get_calendar_appointments\',\\n    \'class\' => \'Calendar\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n    ),\\n  ),\\n  8 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/index.php\',\\n    \'line\' => 331,\\n    \'args\' => \\n    array (\\n      0 => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    ),\\n    \'function\' => \'require_once\',\\n  ),\\n)"}',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
    'line' => 875,
    'function' => 'json_exception',
    'args' => 
    array (
      0 => 
      \mysqli_sql_exception::__set_state(array(
         'message' => 'Table \'easyappointments.ea_pets\' doesn\'t exist',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          3 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/database/DB_query_builder.php',
            'line' => 1455,
            'function' => 'query',
            'class' => 'CI_DB_driver',
            'type' => '->',
            'args' => 
            array (
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          4 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 197,
            'function' => 'get',
            'class' => 'CI_DB_query_builder',
            'type' => '->',
            'args' => 
            array (
              0 => 'pets',
              1 => NULL,
              2 => NULL,
            ),
          ),
          5 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 215,
            'function' => 'get',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 
              array (
                'id_users_customer' => 3,
              ),
            ),
          ),
          6 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
            'line' => 781,
            'function' => 'get_by_customer',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 3,
            ),
          ),
          7 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 481,
            'function' => 'get_calendar_appointments',
            'class' => 'Calendar',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          8 => 
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
  2 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
    'line' => 481,
    'function' => 'get_calendar_appointments',
    'class' => 'Calendar',
    'type' => '->',
    'args' => 
    array (
    ),
  ),
)
ERROR - 2026-03-14 11:31:28 --> JSON exception: {"success":false,"message":"Table 'easyappointments.ea_pets' doesn't exist","trace":"array (\n  0 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/drivers\/mysqli\/mysqli_driver.php',\n    'line' => 301,\n    'function' => 'query',\n    'class' => 'mysqli',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  1 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 746,\n    'function' => '_execute',\n    'class' => 'CI_DB_mysqli_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  2 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_driver.php',\n    'line' => 636,\n    'function' => 'simple_query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  3 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/database\/DB_query_builder.php',\n    'line' => 1455,\n    'function' => 'query',\n    'class' => 'CI_DB_driver',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'SELECT *\nFROM `ea_pets`\nWHERE `id_users_customer` = 3',\n    ),\n  ),\n  4 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 197,\n    'function' => 'get',\n    'class' => 'CI_DB_query_builder',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 'pets',\n      1 => NULL,\n      2 => NULL,\n    ),\n  ),\n  5 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/models\/Pets_model.php',\n    'line' => 215,\n    'function' => 'get',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => \n      array (\n        'id_users_customer' => 3,\n      ),\n    ),\n  ),\n  6 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/application\/controllers\/Calendar.php',\n    'line' => 781,\n    'function' => 'get_by_customer',\n    'class' => 'Pets_model',\n    'type' => '->',\n    'args' => \n    array (\n      0 => 3,\n    ),\n  ),\n  7 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    'line' => 481,\n    'function' => 'get_calendar_appointments',\n    'class' => 'Calendar',\n    'type' => '->',\n    'args' => \n    array (\n    ),\n  ),\n  8 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/index.php',\n    'line' => 331,\n    'args' => \n    array (\n      0 => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    ),\n    'function' => 'require_once',\n  ),\n)"} Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/helpers/http_helper.php',
    'line' => 142,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'JSON exception: {"success":false,"message":"Table \'easyappointments.ea_pets\' doesn\'t exist","trace":"array (\\n  0 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/drivers\\/mysqli\\/mysqli_driver.php\',\\n    \'line\' => 301,\\n    \'function\' => \'query\',\\n    \'class\' => \'mysqli\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  1 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 746,\\n    \'function\' => \'_execute\',\\n    \'class\' => \'CI_DB_mysqli_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  2 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_driver.php\',\\n    \'line\' => 636,\\n    \'function\' => \'simple_query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  3 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/database\\/DB_query_builder.php\',\\n    \'line\' => 1455,\\n    \'function\' => \'query\',\\n    \'class\' => \'CI_DB_driver\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'SELECT *\\nFROM `ea_pets`\\nWHERE `id_users_customer` = 3\',\\n    ),\\n  ),\\n  4 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 197,\\n    \'function\' => \'get\',\\n    \'class\' => \'CI_DB_query_builder\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \'pets\',\\n      1 => NULL,\\n      2 => NULL,\\n    ),\\n  ),\\n  5 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/models\\/Pets_model.php\',\\n    \'line\' => 215,\\n    \'function\' => \'get\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => \\n      array (\\n        \'id_users_customer\' => 3,\\n      ),\\n    ),\\n  ),\\n  6 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/application\\/controllers\\/Calendar.php\',\\n    \'line\' => 781,\\n    \'function\' => \'get_by_customer\',\\n    \'class\' => \'Pets_model\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n      0 => 3,\\n    ),\\n  ),\\n  7 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    \'line\' => 481,\\n    \'function\' => \'get_calendar_appointments\',\\n    \'class\' => \'Calendar\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n    ),\\n  ),\\n  8 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/index.php\',\\n    \'line\' => 331,\\n    \'args\' => \\n    array (\\n      0 => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    ),\\n    \'function\' => \'require_once\',\\n  ),\\n)"}',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
    'line' => 875,
    'function' => 'json_exception',
    'args' => 
    array (
      0 => 
      \mysqli_sql_exception::__set_state(array(
         'message' => 'Table \'easyappointments.ea_pets\' doesn\'t exist',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
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
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          3 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/database/DB_query_builder.php',
            'line' => 1455,
            'function' => 'query',
            'class' => 'CI_DB_driver',
            'type' => '->',
            'args' => 
            array (
              0 => 'SELECT *
FROM `ea_pets`
WHERE `id_users_customer` = 3',
            ),
          ),
          4 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 197,
            'function' => 'get',
            'class' => 'CI_DB_query_builder',
            'type' => '->',
            'args' => 
            array (
              0 => 'pets',
              1 => NULL,
              2 => NULL,
            ),
          ),
          5 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/models/Pets_model.php',
            'line' => 215,
            'function' => 'get',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 
              array (
                'id_users_customer' => 3,
              ),
            ),
          ),
          6 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
            'line' => 781,
            'function' => 'get_by_customer',
            'class' => 'Pets_model',
            'type' => '->',
            'args' => 
            array (
              0 => 3,
            ),
          ),
          7 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 481,
            'function' => 'get_calendar_appointments',
            'class' => 'Calendar',
            'type' => '->',
            'args' => 
            array (
            ),
          ),
          8 => 
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
  2 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
    'line' => 481,
    'function' => 'get_calendar_appointments',
    'class' => 'Calendar',
    'type' => '->',
    'args' => 
    array (
    ),
  ),
)
ERROR - 2026-03-14 11:32:02 --> Severity: error --> Exception: Table 'easyappointments.pets' doesn't exist /Users/leam/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php 301 Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Exceptions.php',
    'line' => 104,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'Severity: error --> Exception: Table \'easyappointments.pets\' doesn\'t exist /Users/leam/Desktop/easyappointments/system/database/drivers/mysqli/mysqli_driver.php 301',
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
      1 => 'Exception: Table \'easyappointments.pets\' doesn\'t exist',
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
         'message' => 'Table \'easyappointments.pets\' doesn\'t exist',
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
                ALTER TABLE `pets`
                ADD CONSTRAINT `fk_pets_customers`
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
                ALTER TABLE `pets`
                ADD CONSTRAINT `fk_pets_customers`
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
                ALTER TABLE `pets`
                ADD CONSTRAINT `fk_pets_customers`
                FOREIGN KEY (`id_users_customer`)
                REFERENCES `users` (`id`)
                ON DELETE CASCADE
                ON UPDATE CASCADE
            ',
            ),
          ),
          3 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/application/migrations/061_create_pets_table.php',
            'line' => 77,
            'function' => 'query',
            'class' => 'CI_DB_driver',
            'type' => '->',
            'args' => 
            array (
              0 => '
                ALTER TABLE `pets`
                ADD CONSTRAINT `fk_pets_customers`
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
            'class' => 'Migration_Create_pets_table',
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
              0 => '065',
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
ERROR - 2026-03-14 11:34:05 --> JSON exception: {"success":false,"message":"You do not have the required permissions for this task.","trace":"array (\n  0 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    'line' => 481,\n    'function' => 'get_calendar_appointments_for_table_view',\n    'class' => 'Calendar',\n    'type' => '->',\n    'args' => \n    array (\n    ),\n  ),\n  1 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/index.php',\n    'line' => 331,\n    'args' => \n    array (\n      0 => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    ),\n    'function' => 'require_once',\n  ),\n)"} Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/helpers/http_helper.php',
    'line' => 142,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'JSON exception: {"success":false,"message":"You do not have the required permissions for this task.","trace":"array (\\n  0 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    \'line\' => 481,\\n    \'function\' => \'get_calendar_appointments_for_table_view\',\\n    \'class\' => \'Calendar\',\\n    \'type\' => \'->\',\\n    \'args\' => \\n    array (\\n    ),\\n  ),\\n  1 => \\n  array (\\n    \'file\' => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/index.php\',\\n    \'line\' => 331,\\n    \'args\' => \\n    array (\\n      0 => \'\\/Users\\/leam\\/Desktop\\/easyappointments\\/system\\/core\\/CodeIgniter.php\',\\n    ),\\n    \'function\' => \'require_once\',\\n  ),\\n)"}',
    ),
  ),
  1 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
    'line' => 703,
    'function' => 'json_exception',
    'args' => 
    array (
      0 => 
      \RuntimeException::__set_state(array(
         'message' => 'You do not have the required permissions for this task.',
         'string' => '',
         'code' => 0,
         'file' => '/Users/leam/Desktop/easyappointments/application/controllers/Calendar.php',
         'line' => 616,
         'trace' => 
        array (
          0 => 
          array (
            'file' => '/Users/leam/Desktop/easyappointments/system/core/CodeIgniter.php',
            'line' => 481,
            'function' => 'get_calendar_appointments_for_table_view',
            'class' => 'Calendar',
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
    'function' => 'get_calendar_appointments_for_table_view',
    'class' => 'Calendar',
    'type' => '->',
    'args' => 
    array (
    ),
  ),
)
ERROR - 2026-03-14 14:29:13 --> Severity: Error --> Maximum execution time of 30 seconds exceeded /Users/leam/Desktop/easyappointments/system/core/compat/standard.php 38 Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Exceptions.php',
    'line' => 104,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'Severity: Error --> Maximum execution time of 30 seconds exceeded /Users/leam/Desktop/easyappointments/system/core/compat/standard.php 38',
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
      2 => '/Users/leam/Desktop/easyappointments/system/core/compat/standard.php',
      3 => 38,
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
      2 => '/Users/leam/Desktop/easyappointments/system/core/compat/standard.php',
      3 => 38,
    ),
  ),
)
ERROR - 2026-03-14 14:29:44 --> Severity: Error --> Maximum execution time of 30 seconds exceeded /Users/leam/Desktop/easyappointments/application/views/components/booking_cancellation_frame.php 10 Trace: array (
  0 => 
  array (
    'file' => '/Users/leam/Desktop/easyappointments/system/core/Exceptions.php',
    'line' => 104,
    'function' => 'log_message',
    'args' => 
    array (
      0 => 'error',
      1 => 'Severity: Error --> Maximum execution time of 30 seconds exceeded /Users/leam/Desktop/easyappointments/application/views/components/booking_cancellation_frame.php 10',
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
      2 => '/Users/leam/Desktop/easyappointments/application/views/components/booking_cancellation_frame.php',
      3 => 10,
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
      2 => '/Users/leam/Desktop/easyappointments/application/views/components/booking_cancellation_frame.php',
      3 => 10,
    ),
  ),
)
ERROR - 2026-03-14 14:40:45 --> JSON exception: {"success":false,"message":"Invalid credentials provided, please try again.","trace":"array (\n  0 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    'line' => 481,\n    'function' => 'validate',\n    'class' => 'Login',\n    'type' => '->',\n    'args' => \n    array (\n    ),\n  ),\n  1 => \n  array (\n    'file' => '\/Users\/leam\/Desktop\/easyappointments\/index.php',\n    'line' => 331,\n    'args' => \n    array (\n      0 => '\/Users\/leam\/Desktop\/easyappointments\/system\/core\/CodeIgniter.php',\n    ),\n    'function' => 'require_once',\n  ),\n)"} Trace: array (
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
