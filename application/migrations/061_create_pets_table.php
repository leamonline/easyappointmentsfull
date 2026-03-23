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

class Migration_Create_pets_table extends EA_Migration
{
    /**
     * Upgrade method.
     */
    public function up(): void
    {
        if (!$this->db->table_exists('pets')) {
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
                'name' => [
                    'type' => 'VARCHAR',
                    'constraint' => '128',
                ],
                'breed' => [
                    'type' => 'VARCHAR',
                    'constraint' => '128',
                    'null' => true,
                ],
                'size' => [
                    'type' => 'ENUM("small","medium","large")',
                    'default' => 'small',
                ],
                'age' => [
                    'type' => 'VARCHAR',
                    'constraint' => '64',
                    'null' => true,
                ],
                'coat_notes' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'vaccination_status' => [
                    'type' => 'VARCHAR',
                    'constraint' => '64',
                    'default' => 'unknown',
                ],
                'behavioural_notes' => [
                    'type' => 'TEXT',
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

            $this->dbforge->create_table('pets', true);

            $this->db->query('
                ALTER TABLE `' . $this->db->dbprefix('pets') . '`
                ADD CONSTRAINT `fk_pets_customers`
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
        if ($this->db->table_exists('pets')) {
            $this->dbforge->drop_table('pets');
        }
    }
}
