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

/**
 * Salon capacity library.
 *
 * Handles the 2-2-1 dynamic capacity rule, large dog slot restrictions,
 * and daily dog count limits for the Smarter Dog grooming salon.
 *
 * @package Libraries
 */
class Salon_capacity
{
    /**
     * @var EA_Controller|CI_Controller
     */
    protected EA_Controller|CI_Controller $CI;

    /**
     * Salon_capacity constructor.
     */
    public function __construct()
    {
        $this->CI = &get_instance();

        $this->CI->load->model('appointments_model');
        $this->CI->load->model('pets_model');
    }

    /**
     * Check if salon mode is enabled.
     *
     * @return bool
     */
    public function is_enabled(): bool
    {
        return (bool) setting('salon_mode');
    }

    /**
     * Get all slot times for the salon day.
     *
     * @return array<int, string> Returns an array of slot time strings (e.g. ['08:30', '09:00', ...]).
     */
    public function get_all_slots(): array
    {
        $start = setting('salon_start_time') ?: '08:30';
        $last_booking = setting('salon_last_booking_time') ?: '13:00';
        $duration = (int) (setting('salon_slot_duration') ?: 30);

        $slots = [];
        $current = new DateTime('2000-01-01 ' . $start);
        $end = new DateTime('2000-01-01 ' . $last_booking);

        while ($current <= $end) {
            $slots[] = $current->format('H:i');
            $current->add(new DateInterval('PT' . $duration . 'M'));
        }

        return $slots;
    }

    /**
     * Get the seat occupancy for a specific slot on a given date.
     *
     * @param string $date Date (Y-m-d).
     * @param string $time Slot time (H:i).
     * @param int|null $exclude_appointment_id Appointment ID to exclude (for editing).
     *
     * @return int Returns the total seats used in this slot.
     */
    public function get_slot_occupancy(string $date, string $time, ?int $exclude_appointment_id = null): int
    {
        $slot_start = $date . ' ' . $time . ':00';
        $duration = (int) (setting('salon_slot_duration') ?: 30);
        $slot_end_dt = new DateTime($slot_start);
        $slot_end_dt->add(new DateInterval('PT' . $duration . 'M'));
        $slot_end = $slot_end_dt->format('Y-m-d H:i:s');

        $this->CI->db
            ->select('COALESCE(SUM(appointments.seats_required), 0) AS total_seats')
            ->from('appointments')
            ->join('services', 'services.id = appointments.id_services', 'left')
            ->where('appointments.start_datetime >=', $slot_start)
            ->where('appointments.start_datetime <', $slot_end)
            ->where('appointments.is_unavailability', 0)
            ->group_start()
            ->where('services.is_walkin IS NULL')
            ->or_where('services.is_walkin', 0)
            ->group_end();

        if ($exclude_appointment_id) {
            $this->CI->db->where('appointments.id !=', $exclude_appointment_id);
        }

        $result = $this->CI->db->get()->row_array();

        return (int) $result['total_seats'];
    }

    /**
     * Get the occupancy map for all slots on a given date.
     *
     * @param string $date Date (Y-m-d).
     * @param int|null $exclude_appointment_id Appointment ID to exclude.
     *
     * @return array<string, int> Associative array of slot_time => seats_used.
     */
    public function get_day_occupancy(string $date, ?int $exclude_appointment_id = null): array
    {
        $slots = $this->get_all_slots();
        $occupancy = [];

        foreach ($slots as $slot) {
            $occupancy[$slot] = $this->get_slot_occupancy($date, $slot, $exclude_appointment_id);
        }

        return $occupancy;
    }

    /**
     * Get the effective capacity for a slot after applying the 2-2-1 rule.
     *
     * The rule: never 3 consecutive slots all at 2 seats. After two consecutive
     * doubles, the next slot is capped at 1. Works in both directions.
     * Empty slots count as "1" for chain purposes (don't reset the count).
     *
     * @param string $date Date (Y-m-d).
     * @param string $time Slot time (H:i).
     * @param int|null $exclude_appointment_id Appointment ID to exclude.
     *
     * @return int Returns the maximum seats available for this slot (1 or 2).
     */
    public function get_effective_capacity(string $date, string $time, ?int $exclude_appointment_id = null): int
    {
        $max_seats = (int) (setting('salon_max_seats_per_slot') ?: 2);
        $slots = $this->get_all_slots();
        $occupancy = $this->get_day_occupancy($date, $exclude_appointment_id);

        $slot_index = array_search($time, $slots);

        if ($slot_index === false) {
            return 0;
        }

        // Check if filling this slot to 2 would create 3 consecutive doubles.
        // We need to check both directions.

        // A slot counts as "double" if it has 2 seats used.
        // An empty slot (0 seats) counts as "1" for chain purposes.

        // Check backwards: how many consecutive doubles before this slot?
        $consecutive_before = 0;
        for ($i = $slot_index - 1; $i >= 0; $i--) {
            $occ = $occupancy[$slots[$i]];
            if ($occ >= $max_seats) {
                $consecutive_before++;
            } else {
                break;
            }
        }

        // Check forwards: how many consecutive doubles after this slot?
        $consecutive_after = 0;
        for ($i = $slot_index + 1; $i < count($slots); $i++) {
            $occ = $occupancy[$slots[$i]];
            if ($occ >= $max_seats) {
                $consecutive_after++;
            } else {
                break;
            }
        }

        // If this slot were set to 2, would there be 3 consecutive doubles?
        // This slot at 2 + consecutive_before >= 2 OR consecutive_after >= 2
        // means we'd have 3+ in a row.
        // Also check: consecutive_before >= 1 AND consecutive_after >= 1
        // (this slot would be in the middle of 3).

        if ($consecutive_before >= 2 || $consecutive_after >= 2) {
            return 1;
        }

        if ($consecutive_before >= 1 && $consecutive_after >= 1) {
            return 1;
        }

        return $max_seats;
    }

    /**
     * Get the total number of dogs booked for a specific date (excluding walk-ins).
     *
     * @param string $date Date (Y-m-d).
     * @param int|null $exclude_appointment_id Appointment ID to exclude.
     *
     * @return int Returns the total dog count.
     */
    public function get_daily_dog_count(string $date, ?int $exclude_appointment_id = null): int
    {
        $this->CI->db
            ->select('COUNT(*) AS dog_count')
            ->from('appointments')
            ->join('services', 'services.id = appointments.id_services', 'left')
            ->where('DATE(appointments.start_datetime)', $date)
            ->where('appointments.is_unavailability', 0)
            ->group_start()
            ->where('services.is_walkin IS NULL')
            ->or_where('services.is_walkin', 0)
            ->group_end();

        if ($exclude_appointment_id) {
            $this->CI->db->where('appointments.id !=', $exclude_appointment_id);
        }

        $result = $this->CI->db->get()->row_array();

        return (int) $result['dog_count'];
    }

    /**
     * Validate large dog booking rules.
     *
     * Large dogs at 8:30: 1 seat, can share.
     * Large dogs at 9:00: 1 seat, only if 8:30 free AND 10:00 has ≤1 seats. Closes 8:30.
     * Large dogs at 12:00/12:30/13:00: 2 seats, no sharing.
     * Any other slot: requires owner approval.
     *
     * @param string $date Date (Y-m-d).
     * @param string $time Slot time (H:i).
     * @param string $pet_size Pet size ('small', 'medium', 'large').
     * @param int|null $exclude_appointment_id Appointment ID to exclude.
     *
     * @return array{allowed: bool, requires_approval: bool, seats_required: int, reason: string} Returns the validation result.
     */
    public function validate_large_dog(
        string $date,
        string $time,
        string $pet_size,
        ?int $exclude_appointment_id = null,
    ): array {
        if ($pet_size !== 'large') {
            return [
                'allowed' => true,
                'requires_approval' => false,
                'seats_required' => 1,
                'reason' => '',
            ];
        }

        $occupancy = $this->get_day_occupancy($date, $exclude_appointment_id);

        switch ($time) {
            case '08:30':
                return [
                    'allowed' => true,
                    'requires_approval' => false,
                    'seats_required' => 1,
                    'reason' => 'Large dog at 8:30 takes 1 seat, can share.',
                ];

            case '09:00':
                $eight_thirty_occ = $occupancy['08:30'] ?? 0;
                $ten_hundred_occ = $occupancy['10:00'] ?? 0;

                if ($eight_thirty_occ > 0) {
                    return [
                        'allowed' => false,
                        'requires_approval' => true,
                        'seats_required' => 1,
                        'reason' => 'Large dog at 9:00 requires 8:30 to be completely free. 8:30 has ' . $eight_thirty_occ . ' booking(s).',
                    ];
                }

                if ($ten_hundred_occ > 1) {
                    return [
                        'allowed' => false,
                        'requires_approval' => true,
                        'seats_required' => 1,
                        'reason' => 'Large dog at 9:00 requires 10:00 to have 0 or 1 seats booked. 10:00 has ' . $ten_hundred_occ . '.',
                    ];
                }

                return [
                    'allowed' => true,
                    'requires_approval' => false,
                    'seats_required' => 1,
                    'reason' => 'Large dog at 9:00 conditional slot approved. 8:30 will be closed.',
                ];

            case '12:00':
            case '12:30':
            case '13:00':
                return [
                    'allowed' => true,
                    'requires_approval' => false,
                    'seats_required' => 2,
                    'reason' => 'Large dog at ' . $time . ' takes 2 seats, no sharing.',
                ];

            default:
                return [
                    'allowed' => false,
                    'requires_approval' => true,
                    'seats_required' => 2,
                    'reason' => 'Large dogs in slot ' . $time . ' require owner approval.',
                ];
        }
    }

    /**
     * Calculate the seats required for an appointment based on pet size and time.
     *
     * @param string $time Slot time (H:i).
     * @param string $pet_size Pet size ('small', 'medium', 'large').
     *
     * @return int Returns the number of seats required.
     */
    public function calculate_seats_required(string $time, string $pet_size): int
    {
        if ($pet_size !== 'large') {
            return 1;
        }

        // Large dog at 8:30 or 9:00 (conditional) takes 1 seat.
        if (in_array($time, ['08:30', '09:00'])) {
            return 1;
        }

        // Large dog at all other times takes 2 seats.
        return 2;
    }

    /**
     * Check if a slot is available for a specific pet size.
     *
     * Combines large-dog validation, seat calculation, capacity check, 2-2-1 rule,
     * and daily maximum into a single call. When is_admin is true, slots that would
     * normally require owner approval for large dogs are allowed.
     *
     * @param string $date Date (Y-m-d).
     * @param string $time Slot time (H:i).
     * @param string $pet_size Pet size ('small', 'medium', 'large').
     * @param bool $is_admin Whether the caller is an admin.
     * @param int|null $exclude_appointment_id Appointment ID to exclude.
     *
     * @return array{available: bool, reason: string} Returns the availability result.
     */
    public function is_slot_available_for_pet(
        string $date,
        string $time,
        string $pet_size = 'small',
        bool $is_admin = false,
        ?int $exclude_appointment_id = null,
    ): array {
        // For large dogs, check the large-dog placement rules first.
        if ($pet_size === 'large') {
            $large_dog_result = $this->validate_large_dog($date, $time, $pet_size, $exclude_appointment_id);

            if (!$large_dog_result['allowed']) {
                // Admins can override slots that require approval.
                if ($is_admin && $large_dog_result['requires_approval']) {
                    // Allow but still use the seats_required from the validation.
                } else {
                    return [
                        'available' => false,
                        'reason' => $large_dog_result['reason'],
                    ];
                }
            }

            $seats_required = $large_dog_result['seats_required'];
        } else {
            $seats_required = 1;
        }

        // Now check basic slot availability with the correct seat count.
        return $this->is_slot_available($date, $time, $seats_required, $exclude_appointment_id);
    }

    /**
     * Check if a slot is available for booking.
     *
     * Combines capacity check, 2-2-1 rule, and daily maximum.
     *
     * @param string $date Date (Y-m-d).
     * @param string $time Slot time (H:i).
     * @param int $seats_required Number of seats needed.
     * @param int|null $exclude_appointment_id Appointment ID to exclude.
     *
     * @return array{available: bool, reason: string} Returns the availability result.
     */
    public function is_slot_available(
        string $date,
        string $time,
        int $seats_required = 1,
        ?int $exclude_appointment_id = null,
    ): array {
        $max_dogs = (int) (setting('salon_max_dogs_per_day') ?: 16);

        // Check daily dog limit.
        $daily_count = $this->get_daily_dog_count($date, $exclude_appointment_id);

        if ($daily_count >= $max_dogs) {
            return [
                'available' => false,
                'reason' => 'Daily maximum of ' . $max_dogs . ' dogs reached.',
            ];
        }

        // Check slot capacity with 2-2-1 rule.
        $current_occupancy = $this->get_slot_occupancy($date, $time, $exclude_appointment_id);
        $effective_capacity = $this->get_effective_capacity($date, $time, $exclude_appointment_id);

        if ($current_occupancy + $seats_required > $effective_capacity) {
            return [
                'available' => false,
                'reason' => 'Slot ' . $time . ' is at capacity (' . $current_occupancy . '/' . $effective_capacity . ' seats used, need ' . $seats_required . ').',
            ];
        }

        return [
            'available' => true,
            'reason' => '',
        ];
    }

    /**
     * Get alternative available slots when the requested slot is full.
     *
     * @param string $date Date (Y-m-d).
     * @param string $requested_time Requested slot time.
     * @param int $seats_required Seats needed.
     * @param int $max_alternatives Maximum number of alternatives to return.
     * @param int|null $exclude_appointment_id Appointment ID to exclude.
     * @param string|null $pet_size Pet size for pet-aware filtering.
     * @param bool $is_admin Whether the caller is an admin.
     *
     * @return array<int, string> Returns an array of available slot times.
     */
    public function get_alternative_slots(
        string $date,
        string $requested_time,
        int $seats_required = 1,
        int $max_alternatives = 3,
        ?int $exclude_appointment_id = null,
        ?string $pet_size = null,
        bool $is_admin = false,
    ): array {
        $all_slots = $this->get_all_slots();
        $alternatives = [];

        // Sort slots by distance from requested time.
        $requested_index = array_search($requested_time, $all_slots);
        if ($requested_index === false) {
            $requested_index = 0;
        }

        $sorted_slots = $all_slots;
        usort($sorted_slots, function ($a, $b) use ($all_slots, $requested_index) {
            $index_a = array_search($a, $all_slots);
            $index_b = array_search($b, $all_slots);
            return abs($index_a - $requested_index) - abs($index_b - $requested_index);
        });

        foreach ($sorted_slots as $slot) {
            if ($slot === $requested_time) {
                continue;
            }

            if ($pet_size) {
                $availability = $this->is_slot_available_for_pet($date, $slot, $pet_size, $is_admin, $exclude_appointment_id);
            } else {
                $availability = $this->is_slot_available($date, $slot, $seats_required, $exclude_appointment_id);
            }

            if ($availability['available']) {
                $alternatives[] = $slot;

                if (count($alternatives) >= $max_alternatives) {
                    break;
                }
            }
        }

        return $alternatives;
    }

    /**
     * Check if a date is a valid salon working day.
     *
     * @param string $date Date (Y-m-d).
     *
     * @return bool
     */
    public function is_working_day(string $date): bool
    {
        $working_days_str = setting('salon_working_days') ?: 'monday,tuesday,wednesday';
        $working_days = array_map('trim', explode(',', $working_days_str));
        $day_name = strtolower(date('l', strtotime($date)));

        return in_array($day_name, $working_days);
    }
}
