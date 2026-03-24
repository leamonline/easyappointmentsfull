<?php defined('BASEPATH') or exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * Smarter Dog - Online Appointment Scheduler
 *
 * @package     SmarterDog
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://easyappointments.org
 * @since       v1.0.0
 * ---------------------------------------------------------------------------- */

/**
 * Validate a date time value.
 *
 * @param string $value Validation value.
 *
 * @return bool Returns the validation result.
 */
if (!function_exists('validate_datetime')) {
    function validate_datetime(string $value): bool
    {
        $date_time = DateTime::createFromFormat('Y-m-d H:i:s', $value);

        return (bool) $date_time;
    }
}

/**
 * Parse a free-form pet age string into months.
 *
 * Handles patterns like "4 months", "2 years", "1.5 years", "6 weeks",
 * "puppy", "8 wks", "1yr", "18mo".
 *
 * @param string|null $age Free-form age text.
 *
 * @return float|null Estimated age in months, or null if unparseable.
 */
if (!function_exists('parse_pet_age_months')) {
    function parse_pet_age_months(?string $age): ?float
    {
        if ($age === null || trim($age) === '') {
            return null;
        }

        $age = trim($age);

        if (preg_match('/(\d+(?:\.\d+)?)\s*(years?|yrs?|y|months?|mos?|m|weeks?|wks?|w)\b/i', $age, $matches)) {
            $value = (float) $matches[1];
            $unit = strtolower($matches[2]);

            if (str_starts_with($unit, 'y')) {
                return $value * 12;
            }

            if (str_starts_with($unit, 'w')) {
                return $value / 4.33;
            }

            // month variants
            return $value;
        }

        // If the string contains "puppy" treat as newborn.
        if (stripos($age, 'puppy') !== false) {
            return 0.0;
        }

        return null;
    }
}
