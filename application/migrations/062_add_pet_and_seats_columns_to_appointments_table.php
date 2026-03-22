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

class Migration_Add_pet_and_seats_columns_to_appointments_table extends EA_Migration
{
    /**
     * Upgrade method.
     */
    public function up(): void
    {
        if (!$this->db->field_exists('id_pets', 'appointments')) {
            $fields = [
                'id_pets' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true,
                    'after' => 'id_users_customer',
                ],
            ];

            $this->dbforge->add_column('appointments', $fields);

            $this->db->query('
                ALTER TABLE `' . $this->db->dbprefix('appointments') . '`
                ADD CONSTRAINT `fk_appointments_pets`
                FOREIGN KEY (`id_pets`)
                REFERENCES `' . $this->db->dbprefix('pets') . '` (`id`)
                ON DELETE SET NULL
                ON UPDATE CASCADE
            ');
        }

        if (!$this->db->field_exists('seats_required', 'appointments')) {
            $fields = [
                'seats_required' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'default' => 1,
                    'after' => 'id_pets',
                ],
            ];

            $this->dbforge->add_column('appointments', $fields);
        }
    }

    /**
     * Downgrade method.
     */
    public function down(): void
    {
        if ($this->db->field_exists('id_pets', 'appointments')) {
            $this->db->query('ALTER TABLE `' . $this->db->dbprefix('appointments') . '` DROP FOREIGN KEY `fk_appointments_pets`');
            $this->dbforge->drop_column('appointments', 'id_pets');
        }

        if ($this->db->field_exists('seats_required', 'appointments')) {
            $this->dbforge->drop_column('appointments', 'seats_required');
        }
    }
}
