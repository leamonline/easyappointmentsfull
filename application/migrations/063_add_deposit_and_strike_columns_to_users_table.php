<?php defined('BASEPATH') or exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * Smarter Dog - Online Appointment Scheduler
 *
 * @package     SmarterDog
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://easyappointments.org
 * @since       v1.5.0
 * ---------------------------------------------------------------------------- */

class Migration_Add_deposit_and_strike_columns_to_users_table extends EA_Migration
{
    /**
     * Upgrade method.
     */
    public function up(): void
    {
        if (!$this->db->field_exists('deposit_status', 'users')) {
            $fields = [
                'deposit_status' => [
                    'type' => 'VARCHAR',
                    'constraint' => '32',
                    'default' => 'not_required',
                    'after' => 'notes',
                ],
            ];

            $this->dbforge->add_column('users', $fields);
        }

        if (!$this->db->field_exists('strike_count', 'users')) {
            $fields = [
                'strike_count' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => 0,
                    'after' => 'deposit_status',
                ],
            ];

            $this->dbforge->add_column('users', $fields);
        }
    }

    /**
     * Downgrade method.
     */
    public function down(): void
    {
        if ($this->db->field_exists('deposit_status', 'users')) {
            $this->dbforge->drop_column('users', 'deposit_status');
        }

        if ($this->db->field_exists('strike_count', 'users')) {
            $this->dbforge->drop_column('users', 'strike_count');
        }
    }
}
