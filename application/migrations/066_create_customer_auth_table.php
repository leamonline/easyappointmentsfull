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

class Migration_Create_customer_auth_table extends EA_Migration
{
    /**
     * Upgrade method.
     */
    public function up(): void
    {
        if (!$this->db->table_exists('customer_auth')) {
            $this->dbforge->add_field([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'auto_increment' => true,
                ],
                'id_users_customer' => [
                    'type' => 'INT',
                    'constraint' => 11,
                ],
                'password_hash' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                ],
                'email_verified' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0,
                ],
                'verification_token' => [
                    'type' => 'VARCHAR',
                    'constraint' => '128',
                    'null' => true,
                ],
                'reset_token' => [
                    'type' => 'VARCHAR',
                    'constraint' => '128',
                    'null' => true,
                ],
                'reset_token_expires' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'create_datetime' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'update_datetime' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->dbforge->add_key('id', true);

            $this->dbforge->create_table('customer_auth', true);

            $this->db->query('
                ALTER TABLE `' . $this->db->dbprefix('customer_auth') . '`
                ADD CONSTRAINT `fk_customer_auth_customers`
                FOREIGN KEY (`id_users_customer`)
                REFERENCES `' . $this->db->dbprefix('users') . '` (`id`)
                ON DELETE CASCADE
                ON UPDATE CASCADE
            ');
        }
    }

    /**
     * Downgrade method.
     */
    public function down(): void
    {
        if ($this->db->table_exists('customer_auth')) {
            $this->dbforge->drop_table('customer_auth');
        }
    }
}
