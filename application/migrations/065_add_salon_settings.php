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

class Migration_Add_salon_settings extends EA_Migration
{
    /**
     * Upgrade method.
     */
    public function up(): void
    {
        $salon_settings = [
            ['name' => 'salon_mode', 'value' => '1'],
            ['name' => 'salon_slot_duration', 'value' => '30'],
            ['name' => 'salon_start_time', 'value' => '08:30'],
            ['name' => 'salon_last_booking_time', 'value' => '13:00'],
            ['name' => 'salon_end_time', 'value' => '15:00'],
            ['name' => 'salon_max_seats_per_slot', 'value' => '2'],
            ['name' => 'salon_max_dogs_per_day', 'value' => '16'],
            ['name' => 'salon_working_days', 'value' => 'monday,tuesday,wednesday'],
            ['name' => 'salon_walkin_start', 'value' => '09:00'],
            ['name' => 'salon_walkin_end', 'value' => '13:00'],
        ];

        foreach ($salon_settings as $setting) {
            $exists = $this->db->get_where('settings', ['name' => $setting['name']])->num_rows();

            if (!$exists) {
                $this->db->insert('settings', $setting);
            }
        }
    }

    /**
     * Downgrade method.
     */
    public function down(): void
    {
        $this->db->where_in('name', [
            'salon_mode',
            'salon_slot_duration',
            'salon_start_time',
            'salon_last_booking_time',
            'salon_end_time',
            'salon_max_seats_per_slot',
            'salon_max_dogs_per_day',
            'salon_working_days',
            'salon_walkin_start',
            'salon_walkin_end',
        ]);

        $this->db->delete('settings');
    }
}
