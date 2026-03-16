<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

// Require the libraries under test.
require_once __DIR__ . '/../application/libraries/Salon_capacity.php';
require_once __DIR__ . '/../application/libraries/Availability.php';

/**
 * Testable subclass that exposes protected methods.
 */
class TestableAvailability extends \Availability
{
    public function call_consider_salon_capacity(string $date, array $hours, ?int $exclude = null): array
    {
        return $this->consider_salon_capacity($date, $hours, $exclude);
    }

    public function call_consider_book_advance_timeout(string $date, array $hours, array $provider): array
    {
        return $this->consider_book_advance_timeout($date, $hours, $provider);
    }

    public function call_consider_future_booking_limit(string $date, array $hours, array $provider): array
    {
        return $this->consider_future_booking_limit($date, $hours, $provider);
    }

    public function call_generate_available_hours(string $date, array $service, array $periods): array
    {
        return $this->generate_available_hours($date, $service, $periods);
    }
}

/**
 * Tests for the Availability library.
 *
 * Covers: get_available_hours with salon on/off, consider_salon_capacity filtering,
 * blocked period interaction, and book-advance-timeout logic.
 */
class AvailabilityTest extends TestCase
{
    private TestableAvailability $lib;

    private array $defaultSettings = [
        'salon_mode' => '1',
        'salon_start_time' => '08:30',
        'salon_last_booking_time' => '13:00',
        'salon_slot_duration' => '30',
        'salon_max_seats_per_slot' => '2',
        'salon_max_dogs_per_day' => '16',
        'salon_working_days' => 'monday,tuesday,wednesday',
        'book_advance_timeout' => '0',
        'future_booking_limit' => '365',
    ];

    /** Standard provider for testing. */
    private array $provider = [
        'id' => 1,
        'timezone' => 'UTC',
        'settings' => [
            'working_plan' => '{"monday":{"start":"08:00","end":"17:00","breaks":[]},"tuesday":{"start":"08:00","end":"17:00","breaks":[]},"wednesday":{"start":"08:00","end":"17:00","breaks":[]},"thursday":null,"friday":null,"saturday":null,"sunday":null}',
            'working_plan_exceptions' => '{}',
        ],
    ];

    /** Standard service (30 min, fixed). */
    private array $service = [
        'id' => 1,
        'duration' => '30',
        'attendants_number' => 1,
        'availabilities_type' => 'fixed',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $GLOBALS['_test_settings'] = $this->defaultSettings;

        $ci = new \EA_Controller();
        $ci->load = new AvailStubLoader();

        // DB mock with escape support.
        $ci->db = $this->createDbMock();

        // Model mocks.
        $ci->blocked_periods_model = $this->createMock(AvailStubBlockedPeriods::class);
        $ci->blocked_periods_model->method('is_entire_date_blocked')->willReturn(false);
        $ci->blocked_periods_model->method('get_for_period')->willReturn([]);

        $ci->appointments_model = $this->createMock(AvailStubAppointments::class);
        $ci->appointments_model->method('get')->willReturn([]);

        $ci->unavailabilities_model = $this->createMock(AvailStubUnavailabilities::class);
        $ci->unavailabilities_model->method('get')->willReturn([]);

        // Salon capacity mock — enabled by default, all slots available.
        $ci->salon_capacity = $this->createMock(AvailStubSalonCapacity::class);
        $ci->salon_capacity->method('is_enabled')->willReturn(true);
        $ci->salon_capacity->method('is_slot_available')->willReturn([
            'available' => true,
            'reason' => '',
        ]);

        $GLOBALS['_ci_instance'] = $ci;

        $this->lib = new TestableAvailability();
    }

    protected function tearDown(): void
    {
        $GLOBALS['_test_settings'] = [];
        $GLOBALS['_ci_instance'] = new \EA_Controller();
        parent::tearDown();
    }

    // -- Helpers -------------------------------------------------------

    private function createDbMock(): object
    {
        $db = $this->createMock(AvailStubDb::class);
        $db->method('escape')->willReturnCallback(fn($val) => "'" . $val . "'");
        return $db;
    }

    private function ci(): \EA_Controller
    {
        return $GLOBALS['_ci_instance'];
    }

    // ===================================================================
    // get_available_hours with salon mode ON
    // ===================================================================

    public function test_get_available_hours_returns_hours_from_working_plan(): void
    {
        // Provider works 08:00-17:00, service is 30min fixed.
        // With no appointments/breaks, should get many slots.
        // Use a future Monday to avoid book-advance-timeout filtering.
        $hours = $this->lib->get_available_hours('2026-04-06', $this->service, $this->provider);

        $this->assertNotEmpty($hours);
        $this->assertContains('08:00', $hours);
        $this->assertContains('16:30', $hours);
        // 17:00 itself shouldn't be available (30min service wouldn't fit).
        $this->assertNotContains('17:00', $hours);
    }

    public function test_get_available_hours_salon_on_filters_through_capacity(): void
    {
        // Salon mode enabled, but mark 10:00 as unavailable.
        $this->ci()->salon_capacity = $this->createMock(AvailStubSalonCapacity::class);
        $this->ci()->salon_capacity->method('is_enabled')->willReturn(true);
        $this->ci()->salon_capacity->method('is_slot_available')
            ->willReturnCallback(function ($date, $hour, $seats) {
                if ($hour === '10:00') {
                    return ['available' => false, 'reason' => 'full'];
                }
                return ['available' => true, 'reason' => ''];
            });

        $hours = $this->lib->get_available_hours('2026-04-06', $this->service, $this->provider);

        $this->assertNotContains('10:00', $hours);
        $this->assertContains('09:00', $hours);
        $this->assertContains('11:00', $hours);
    }

    public function test_get_available_hours_salon_on_filters_multiple_slots(): void
    {
        // Block several slots via salon capacity.
        $blocked = ['08:00', '08:30', '09:00'];
        $this->ci()->salon_capacity = $this->createMock(AvailStubSalonCapacity::class);
        $this->ci()->salon_capacity->method('is_enabled')->willReturn(true);
        $this->ci()->salon_capacity->method('is_slot_available')
            ->willReturnCallback(function ($date, $hour) use ($blocked) {
                return [
                    'available' => !in_array($hour, $blocked),
                    'reason' => in_array($hour, $blocked) ? 'full' : '',
                ];
            });

        $hours = $this->lib->get_available_hours('2026-04-06', $this->service, $this->provider);

        foreach ($blocked as $b) {
            $this->assertNotContains($b, $hours, "$b should have been filtered by salon capacity");
        }
        $this->assertContains('09:30', $hours);
    }

    // ===================================================================
    // get_available_hours with salon mode OFF
    // ===================================================================

    public function test_get_available_hours_salon_off_skips_capacity_filter(): void
    {
        // Salon mode disabled — all hours should pass through even if capacity says no.
        $this->ci()->salon_capacity = $this->createMock(AvailStubSalonCapacity::class);
        $this->ci()->salon_capacity->method('is_enabled')->willReturn(false);
        // is_slot_available should never be called, but set it to reject just in case.
        $this->ci()->salon_capacity->method('is_slot_available')->willReturn([
            'available' => false,
            'reason' => 'full',
        ]);

        $hours = $this->lib->get_available_hours('2026-04-06', $this->service, $this->provider);

        $this->assertNotEmpty($hours);
        $this->assertContains('10:00', $hours);
        $this->assertContains('12:00', $hours);
    }

    public function test_get_available_hours_salon_off_preserves_all_generated_slots(): void
    {
        $GLOBALS['_test_settings']['salon_mode'] = '0';
        $this->ci()->salon_capacity = $this->createMock(AvailStubSalonCapacity::class);
        $this->ci()->salon_capacity->method('is_enabled')->willReturn(false);

        $hours = $this->lib->get_available_hours('2026-04-06', $this->service, $this->provider);

        // 08:00 to 16:30 in 30min intervals = 18 slots.
        $this->assertCount(18, $hours);
    }

    // ===================================================================
    // consider_salon_capacity (direct)
    // ===================================================================

    public function test_consider_salon_capacity_passes_all_when_available(): void
    {
        $input = ['08:30', '09:00', '09:30', '10:00'];
        $result = $this->lib->call_consider_salon_capacity('2026-03-16', $input);

        $this->assertEquals($input, $result);
    }

    public function test_consider_salon_capacity_removes_unavailable_slots(): void
    {
        $this->ci()->salon_capacity = $this->createMock(AvailStubSalonCapacity::class);
        $this->ci()->salon_capacity->method('is_slot_available')
            ->willReturnCallback(function ($date, $hour) {
                return [
                    'available' => ($hour !== '09:30'),
                    'reason' => '',
                ];
            });

        $input = ['08:30', '09:00', '09:30', '10:00'];
        $result = $this->lib->call_consider_salon_capacity('2026-03-16', $input);

        $this->assertEquals(['08:30', '09:00', '10:00'], $result);
    }

    public function test_consider_salon_capacity_removes_all_when_all_full(): void
    {
        $this->ci()->salon_capacity = $this->createMock(AvailStubSalonCapacity::class);
        $this->ci()->salon_capacity->method('is_slot_available')
            ->willReturn(['available' => false, 'reason' => 'full']);

        $result = $this->lib->call_consider_salon_capacity('2026-03-16', ['08:30', '09:00']);

        $this->assertEmpty($result);
    }

    public function test_consider_salon_capacity_empty_input_returns_empty(): void
    {
        $result = $this->lib->call_consider_salon_capacity('2026-03-16', []);
        $this->assertEmpty($result);
    }

    public function test_consider_salon_capacity_passes_exclude_id(): void
    {
        $passedArgs = [];
        $this->ci()->salon_capacity = $this->createMock(AvailStubSalonCapacity::class);
        $this->ci()->salon_capacity->method('is_slot_available')
            ->willReturnCallback(function ($date, $hour, $seats, $excludeId) use (&$passedArgs) {
                $passedArgs[] = ['hour' => $hour, 'excludeId' => $excludeId];
                return ['available' => true, 'reason' => ''];
            });

        $this->lib->call_consider_salon_capacity('2026-03-16', ['10:00'], 42);

        $this->assertCount(1, $passedArgs);
        $this->assertEquals(42, $passedArgs[0]['excludeId']);
    }

    // ===================================================================
    // Blocked periods interaction
    // ===================================================================

    public function test_get_available_hours_returns_empty_when_entire_date_blocked(): void
    {
        $this->ci()->blocked_periods_model = $this->createMock(AvailStubBlockedPeriods::class);
        $this->ci()->blocked_periods_model->method('is_entire_date_blocked')->willReturn(true);

        $hours = $this->lib->get_available_hours('2026-04-06', $this->service, $this->provider);

        $this->assertEmpty($hours);
    }

    public function test_get_available_hours_returns_empty_when_date_blocked_even_salon_on(): void
    {
        $this->ci()->blocked_periods_model = $this->createMock(AvailStubBlockedPeriods::class);
        $this->ci()->blocked_periods_model->method('is_entire_date_blocked')->willReturn(true);
        $this->ci()->blocked_periods_model->method('get_for_period')->willReturn([]);

        // Salon capacity should not even be consulted.
        $this->ci()->salon_capacity = $this->createMock(AvailStubSalonCapacity::class);
        $this->ci()->salon_capacity->method('is_enabled')->willReturn(true);
        $this->ci()->salon_capacity->expects($this->never())->method('is_slot_available');

        $hours = $this->lib->get_available_hours('2026-04-06', $this->service, $this->provider);

        $this->assertEmpty($hours);
    }

    public function test_partial_blocked_period_removes_covered_hours(): void
    {
        // Block 10:00-12:00 via blocked_periods_model->get_for_period.
        $this->ci()->blocked_periods_model = $this->createMock(AvailStubBlockedPeriods::class);
        $this->ci()->blocked_periods_model->method('is_entire_date_blocked')->willReturn(false);
        $this->ci()->blocked_periods_model->method('get_for_period')->willReturn([
            [
                'start_datetime' => '2026-04-06 10:00:00',
                'end_datetime' => '2026-04-06 12:00:00',
            ],
        ]);

        $hours = $this->lib->get_available_hours('2026-04-06', $this->service, $this->provider);

        $this->assertContains('08:00', $hours);
        $this->assertContains('09:30', $hours);
        // 10:00-11:30 should be blocked.
        $this->assertNotContains('10:00', $hours);
        $this->assertNotContains('10:30', $hours);
        $this->assertNotContains('11:00', $hours);
        $this->assertNotContains('11:30', $hours);
        // 12:00 onwards should be available.
        $this->assertContains('12:00', $hours);
    }

    // ===================================================================
    // Book advance timeout
    // ===================================================================

    public function test_book_advance_timeout_zero_keeps_all_future_hours(): void
    {
        // Far future date — all hours should survive with timeout=0.
        $hours = ['08:00', '09:00', '10:00', '14:00', '16:00'];
        $result = $this->lib->call_consider_book_advance_timeout('2099-01-01', $hours, $this->provider);

        $this->assertEquals($hours, $result);
    }

    public function test_book_advance_timeout_removes_past_hours_today(): void
    {
        // Use today's date. With a very large timeout, all hours should be removed.
        $GLOBALS['_test_settings']['book_advance_timeout'] = '999999';

        $today = date('Y-m-d');
        $hours = ['08:00', '10:00', '14:00', '23:59'];
        $result = $this->lib->call_consider_book_advance_timeout($today, $hours, $this->provider);

        $this->assertEmpty($result, 'With huge timeout, all hours today should be filtered out');
    }

    public function test_book_advance_timeout_keeps_far_future_hours(): void
    {
        // Moderate timeout, but date is far in the future.
        $GLOBALS['_test_settings']['book_advance_timeout'] = '60';

        $hours = ['08:00', '12:00', '16:00'];
        $result = $this->lib->call_consider_book_advance_timeout('2099-06-15', $hours, $this->provider);

        $this->assertEquals($hours, $result);
    }

    public function test_book_advance_timeout_returns_sorted_hours(): void
    {
        $hours = ['14:00', '08:00', '11:00'];
        $result = $this->lib->call_consider_book_advance_timeout('2099-01-01', $hours, $this->provider);

        $this->assertEquals(['08:00', '11:00', '14:00'], $result);
    }

    public function test_book_advance_timeout_respects_provider_timezone(): void
    {
        // Provider in UTC+12 — "today" there might already be tomorrow here.
        $provider = $this->provider;
        $provider['timezone'] = 'Pacific/Auckland';

        $hours = ['08:00', '12:00'];
        $result = $this->lib->call_consider_book_advance_timeout('2099-01-01', $hours, $provider);

        $this->assertCount(2, $result);
    }

    // ===================================================================
    // Future booking limit
    // ===================================================================

    public function test_future_booking_limit_allows_near_dates(): void
    {
        $GLOBALS['_test_settings']['future_booking_limit'] = '365';
        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        $hours = ['08:00', '09:00'];
        $result = $this->lib->call_consider_future_booking_limit($tomorrow, $hours, $this->provider);

        $this->assertEquals($hours, $result);
    }

    public function test_future_booking_limit_blocks_far_dates(): void
    {
        $GLOBALS['_test_settings']['future_booking_limit'] = '1';

        $hours = ['08:00', '09:00'];
        $result = $this->lib->call_consider_future_booking_limit('2099-01-01', $hours, $this->provider);

        $this->assertEmpty($result);
    }

    // ===================================================================
    // get_available_hours: non-working day
    // ===================================================================

    public function test_get_available_hours_returns_empty_for_non_working_day(): void
    {
        // 2026-04-09 is Thursday — provider doesn't work on Thursday.
        $hours = $this->lib->get_available_hours('2026-04-09', $this->service, $this->provider);

        $this->assertEmpty($hours);
    }

    // ===================================================================
    // generate_available_hours (direct)
    // ===================================================================

    public function test_generate_available_hours_fixed_service(): void
    {
        $periods = [['start' => '09:00', 'end' => '11:00']];
        $service = ['duration' => '30', 'availabilities_type' => 'fixed'];

        $hours = $this->lib->call_generate_available_hours('2026-03-16', $service, $periods);

        $this->assertEquals(['09:00', '09:30', '10:00', '10:30'], $hours);
    }

    public function test_generate_available_hours_flexible_15min_intervals(): void
    {
        $periods = [['start' => '09:00', 'end' => '10:00']];
        $service = ['duration' => '30', 'availabilities_type' => 'flexible'];

        $hours = $this->lib->call_generate_available_hours('2026-03-16', $service, $periods);

        // 15-min intervals: 09:00, 09:15, 09:30
        $this->assertEquals(['09:00', '09:15', '09:30'], $hours);
    }

    public function test_generate_available_hours_empty_periods(): void
    {
        $hours = $this->lib->call_generate_available_hours('2026-03-16', $this->service, []);
        $this->assertEmpty($hours);
    }

    public function test_generate_available_hours_multiple_periods(): void
    {
        $periods = [
            ['start' => '08:00', 'end' => '09:00'],
            ['start' => '11:00', 'end' => '12:00'],
        ];
        $service = ['duration' => '30', 'availabilities_type' => 'fixed'];

        $hours = $this->lib->call_generate_available_hours('2026-03-16', $service, $periods);

        $this->assertContains('08:00', $hours);
        $this->assertContains('08:30', $hours);
        $this->assertContains('11:00', $hours);
        $this->assertContains('11:30', $hours);
        $this->assertNotContains('09:30', $hours);
        $this->assertNotContains('10:30', $hours);
    }

    public function test_generate_available_hours_service_too_long_for_period(): void
    {
        $periods = [['start' => '09:00', 'end' => '09:15']];
        $service = ['duration' => '30', 'availabilities_type' => 'fixed'];

        $hours = $this->lib->call_generate_available_hours('2026-03-16', $service, $periods);

        $this->assertEmpty($hours);
    }
}

// -- Stub classes for Availability tests --

class AvailStubLoader
{
    public function model(string $name): void
    {
    }

    public function library(string $name): void
    {
    }
}

class AvailStubDb
{
    public function escape($val): string
    {
        return "'" . $val . "'";
    }
}

class AvailStubBlockedPeriods
{
    public function is_entire_date_blocked(string $date): bool
    {
        return false;
    }

    public function get_for_period(string $start, string $end): array
    {
        return [];
    }
}

class AvailStubAppointments
{
    public function get($where = null): array
    {
        return [];
    }
}

class AvailStubUnavailabilities
{
    public function get($where = null): array
    {
        return [];
    }
}

class AvailStubSalonCapacity
{
    public function is_enabled(): bool
    {
        return true;
    }

    public function is_slot_available(string $date, string $time, int $seats = 1, ?int $exclude = null): array
    {
        return ['available' => true, 'reason' => ''];
    }
}
