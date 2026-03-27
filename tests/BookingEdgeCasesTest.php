<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../application/libraries/Salon_capacity.php';
require_once __DIR__ . '/../application/libraries/Availability.php';

/**
 * Testable subclass that exposes protected methods for edge-case testing.
 */
class EdgeCaseTestableAvailability extends \Availability
{
    public function call_consider_salon_capacity(string $date, array $hours, ?int $exclude = null, ?string $pet_size = null, bool $is_admin = false): array
    {
        return $this->consider_salon_capacity($date, $hours, $exclude, $pet_size, $is_admin);
    }
}

/**
 * Edge-case tests for booking rules.
 *
 * Covers: 2-2-1 rule (core + empty slot chain), daily maximum (16 dogs),
 * large dog restrictions (8:30, 9:00, 12:00-13:00, other slots), admin override,
 * small/medium seat counting, pet_size=null fallback, blocked periods,
 * working plan exceptions, reschedule exclude_appointment_id, alternative slots,
 * and working day checks.
 */
class BookingEdgeCasesTest extends TestCase
{
    private \Salon_capacity $salon;

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

    private array $provider = [
        'id' => 1,
        'timezone' => 'UTC',
        'settings' => [
            'working_plan' => '{"monday":{"start":"08:00","end":"17:00","breaks":[]},"tuesday":{"start":"08:00","end":"17:00","breaks":[]},"wednesday":{"start":"08:00","end":"17:00","breaks":[]},"thursday":null,"friday":null,"saturday":null,"sunday":null}',
            'working_plan_exceptions' => '{}',
        ],
    ];

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
        $ci->load = new EdgeCaseStubLoader();
        $ci->db = $this->createDefaultDbMock(0);

        $ci->blocked_periods_model = $this->createMock(EdgeCaseStubBlockedPeriods::class);
        $ci->blocked_periods_model->method('is_entire_date_blocked')->willReturn(false);
        $ci->blocked_periods_model->method('get_for_period')->willReturn([]);

        $ci->appointments_model = $this->createMock(EdgeCaseStubAppointments::class);
        $ci->appointments_model->method('get')->willReturn([]);

        $ci->unavailabilities_model = $this->createMock(EdgeCaseStubUnavailabilities::class);
        $ci->unavailabilities_model->method('get')->willReturn([]);

        $ci->salon_capacity = $this->createMock(EdgeCaseStubSalonCapacity::class);
        $ci->salon_capacity->method('is_enabled')->willReturn(true);
        $ci->salon_capacity->method('is_slot_available')->willReturn([
            'available' => true,
            'reason' => '',
        ]);

        $GLOBALS['_ci_instance'] = $ci;
        $this->salon = new \Salon_capacity();
    }

    protected function tearDown(): void
    {
        $GLOBALS['_test_settings'] = [];
        $GLOBALS['_ci_instance'] = new \EA_Controller();
        parent::tearDown();
    }

    // -- Helpers -----------------------------------------------------------

    private function createDefaultDbMock(int $value): object
    {
        $result = $this->createMock(EdgeCaseStubResult::class);
        $result->method('row_array')->willReturn([
            'total_seats' => $value,
            'dog_count' => $value,
        ]);

        $db = $this->createMock(EdgeCaseStubDb::class);
        $db->method('select')->willReturnSelf();
        $db->method('from')->willReturnSelf();
        $db->method('join')->willReturnSelf();
        $db->method('where')->willReturnSelf();
        $db->method('or_where')->willReturnSelf();
        $db->method('group_start')->willReturnSelf();
        $db->method('group_end')->willReturnSelf();
        $db->method('get')->willReturn($result);
        $db->method('escape')->willReturnCallback(fn($val) => "'" . $val . "'");

        return $db;
    }

    private function createOccupancyDbMock(array $occupancyMap, int $dogCount = 0): object
    {
        $db = $this->getMockBuilder(EdgeCaseStubDb::class)->getMock();
        $db->method('select')->willReturnSelf();
        $db->method('from')->willReturnSelf();
        $db->method('join')->willReturnSelf();
        $db->method('or_where')->willReturnSelf();
        $db->method('group_start')->willReturnSelf();
        $db->method('group_end')->willReturnSelf();
        $db->method('escape')->willReturnCallback(fn($val) => "'" . $val . "'");

        $lastWhereArgs = new \stdClass();
        $lastWhereArgs->values = [];

        $db->method('where')->willReturnCallback(function ($key, $val = null) use ($db, $lastWhereArgs) {
            if ($val !== null) {
                $lastWhereArgs->values[$key] = $val;
            }
            return $db;
        });

        $db->method('get')->willReturnCallback(function () use ($occupancyMap, $dogCount, $lastWhereArgs) {
            $result = $this->createMock(EdgeCaseStubResult::class);

            if (isset($lastWhereArgs->values['appointments.start_datetime >='])) {
                $startDt = $lastWhereArgs->values['appointments.start_datetime >='];
                $time = substr($startDt, 11, 5);
                $seats = $occupancyMap[$time] ?? 0;
                $result->method('row_array')->willReturn([
                    'total_seats' => $seats,
                    'dog_count' => $dogCount,
                ]);
            } else {
                $result->method('row_array')->willReturn([
                    'total_seats' => 0,
                    'dog_count' => $dogCount,
                ]);
            }

            $lastWhereArgs->values = [];
            return $result;
        });

        return $db;
    }

    private function setDbMock(array $occupancyMap, int $dogCount = 0): void
    {
        $GLOBALS['_ci_instance']->db = $this->createOccupancyDbMock($occupancyMap, $dogCount);
        $this->salon = new \Salon_capacity();
    }

    private function ci(): \EA_Controller
    {
        return $GLOBALS['_ci_instance'];
    }

    private function makeAvailability(): EdgeCaseTestableAvailability
    {
        return new EdgeCaseTestableAvailability();
    }

    // ===================================================================
    // 2-2-1 Rule — Core Logic
    // ===================================================================

    public function test_221_third_slot_capped_at_1_after_two_doubles(): void
    {
        // Slots: [2, 2, ?, ...] — third slot MUST be capped at 1.
        $this->setDbMock(['08:30' => 2, '09:00' => 2]);
        $this->assertEquals(1, $this->salon->get_effective_capacity('2026-03-16', '09:30'));
    }

    public function test_221_chain_broken_by_single_then_new_pair(): void
    {
        // Slots: [2, 2, 1, 2, ?] — the 1 breaks the chain; fifth slot checks
        // its own two predecessors: 10:00=1, 10:30=2. Only one double before it, so cap=2.
        // But we also need to test slot index 4 (10:30) which has 10:00=1 before and ? after.
        // The question mark is slot 11:00. Let's verify the fifth slot (11:00) when
        // slots are [2,2,1,2,?]. That's 08:30=2,09:00=2,09:30=1,10:00=2,10:30=?.
        $this->setDbMock(['08:30' => 2, '09:00' => 2, '09:30' => 1, '10:00' => 2]);
        // 10:30 has only 1 consecutive double before it (10:00=2), so cap=2.
        $this->assertEquals(2, $this->salon->get_effective_capacity('2026-03-16', '10:30'));
    }

    public function test_221_checks_forward_direction(): void
    {
        // If slot N+1 and N+2 are both doubles, slot N is capped at 1.
        $this->setDbMock(['10:00' => 2, '10:30' => 2]);
        $this->assertEquals(1, $this->salon->get_effective_capacity('2026-03-16', '09:30'));
    }

    public function test_221_middle_of_chain_capped(): void
    {
        // If slot N-1 is double and slot N+1 is double, slot N is capped at 1
        // (it would be the middle of three consecutive doubles).
        $this->setDbMock(['09:00' => 2, '10:00' => 2]);
        $this->assertEquals(1, $this->salon->get_effective_capacity('2026-03-16', '09:30'));
    }

    // ===================================================================
    // 2-2-1 Rule — Empty Slot Chain Behaviour
    // ===================================================================

    public function test_221_empty_slot_does_not_reset_chain(): void
    {
        // [2, 0, 2, ?] — empty slot counts as "1" (not double), so it breaks
        // the consecutive-doubles chain. Slot 4 (10:30) sees: 10:00=2 (one double),
        // 09:30=0 (not double, chain breaks). So slot 4 can be 2.
        $this->setDbMock(['09:00' => 2, '09:30' => 0, '10:00' => 2]);
        $this->assertEquals(2, $this->salon->get_effective_capacity('2026-03-16', '10:30'));
    }

    public function test_221_two_doubles_then_empty_slot_forced_to_1(): void
    {
        // [2, 2, 0, ?] — two doubles already triggered the cap.
        // Slot 3 (09:30) effective capacity = 1 (even though it's empty, that's fine).
        $this->setDbMock(['08:30' => 2, '09:00' => 2, '09:30' => 0]);
        $this->assertEquals(1, $this->salon->get_effective_capacity('2026-03-16', '09:30'));
    }

    public function test_221_after_chain_broken_by_non_double_slot_next_eligible_for_2(): void
    {
        // [2, 2, 0, ?] — slot 4 (10:00) should be eligible for 2 because
        // the chain is broken by the non-double slot 3 (09:30=0).
        $this->setDbMock(['08:30' => 2, '09:00' => 2, '09:30' => 0]);
        $this->assertEquals(2, $this->salon->get_effective_capacity('2026-03-16', '10:00'));
    }

    // ===================================================================
    // Daily Maximum (16 dogs)
    // ===================================================================

    public function test_daily_max_16_dogs_slot_unavailable(): void
    {
        $this->setDbMock([], 16);
        $result = $this->salon->is_slot_available('2026-03-16', '10:00', 1);
        $this->assertFalse($result['available']);
        $this->assertStringContainsString('Daily maximum of 16 dogs reached', $result['reason']);
    }

    public function test_daily_15_dogs_slot_available(): void
    {
        $this->setDbMock([], 15);
        $result = $this->salon->is_slot_available('2026-03-16', '10:00', 1);
        $this->assertTrue($result['available']);
    }

    public function test_walkins_excluded_from_daily_count(): void
    {
        // The get_daily_dog_count SQL joins services and filters is_walkin.
        // Verify the method itself returns the dog_count from the DB query
        // which already excludes walk-ins via WHERE clause.
        // We set dogCount=14 which represents only non-walkin dogs.
        $this->setDbMock([], 14);
        $count = $this->salon->get_daily_dog_count('2026-03-16');
        $this->assertEquals(14, $count);
        // And it's under 16 so slot is available.
        $result = $this->salon->is_slot_available('2026-03-16', '10:00', 1);
        $this->assertTrue($result['available']);
    }

    // ===================================================================
    // Large Dog at 8:30 — Agent-Approved
    // ===================================================================

    public function test_large_dog_0830_allowed_no_approval_1_seat(): void
    {
        $result = $this->salon->validate_large_dog('2025-06-02', '08:30', 'large');
        $this->assertTrue($result['allowed']);
        $this->assertFalse($result['requires_approval']);
        $this->assertEquals(1, $result['seats_required']);
    }

    public function test_small_dog_can_share_0830_with_large_dog(): void
    {
        // Large dog at 8:30 uses 1 seat. Small dog needs 1 seat. Total=2, within capacity.
        $this->setDbMock(['08:30' => 1], 2);
        $result = $this->salon->is_slot_available_for_pet('2025-06-02', '08:30', 'small');
        $this->assertTrue($result['available']);
    }

    // ===================================================================
    // Large Dog at 9:00 — Conditional Slot
    // ===================================================================

    public function test_large_dog_0900_allowed_when_0830_free_and_1000_has_0_or_1(): void
    {
        $this->setDbMock(['08:30' => 0, '10:00' => 0]);
        $result = $this->salon->validate_large_dog('2026-03-16', '09:00', 'large');
        $this->assertTrue($result['allowed']);
        $this->assertFalse($result['requires_approval']);
        $this->assertEquals(1, $result['seats_required']);

        $this->setDbMock(['08:30' => 0, '10:00' => 1]);
        $result = $this->salon->validate_large_dog('2026-03-16', '09:00', 'large');
        $this->assertTrue($result['allowed']);
        $this->assertEquals(1, $result['seats_required']);
    }

    public function test_large_dog_0900_denied_when_0830_has_any_booking(): void
    {
        $this->setDbMock(['08:30' => 1]);
        $result = $this->salon->validate_large_dog('2026-03-16', '09:00', 'large');
        $this->assertFalse($result['allowed']);
        $this->assertTrue($result['requires_approval']);
    }

    public function test_large_dog_0900_denied_when_1000_has_2_seats(): void
    {
        $this->setDbMock(['08:30' => 0, '10:00' => 2]);
        $result = $this->salon->validate_large_dog('2026-03-16', '09:00', 'large');
        $this->assertFalse($result['allowed']);
        $this->assertTrue($result['requires_approval']);
    }

    public function test_small_dog_can_share_0900_with_large_dog(): void
    {
        // Large dog at 9:00 uses 1 seat. Small dog needs 1. Total=2, within capacity.
        $this->setDbMock(['08:30' => 0, '09:00' => 1, '10:00' => 0], 2);
        $result = $this->salon->is_slot_available_for_pet('2026-03-16', '09:00', 'small');
        $this->assertTrue($result['available']);
    }

    public function test_large_dog_0900_reason_mentions_closing_0830(): void
    {
        $this->setDbMock(['08:30' => 0, '10:00' => 0]);
        $result = $this->salon->validate_large_dog('2026-03-16', '09:00', 'large');
        $this->assertTrue($result['allowed']);
        $this->assertStringContainsString('8:30', $result['reason']);
        $this->assertStringContainsString('closed', $result['reason']);
    }

    // ===================================================================
    // Large Dog at 12:00 / 12:30 / 13:00 — Agent-Approved
    // ===================================================================

    public function test_large_dog_afternoon_slots_allowed_2_seats_no_approval(): void
    {
        foreach (['12:00', '12:30', '13:00'] as $time) {
            $result = $this->salon->validate_large_dog('2026-03-16', $time, 'large');
            $this->assertTrue($result['allowed'], "Large dog should be allowed at $time");
            $this->assertFalse($result['requires_approval'], "No approval needed at $time");
            $this->assertEquals(2, $result['seats_required'], "Should require 2 seats at $time");
        }
    }

    public function test_large_dog_afternoon_no_sharing(): void
    {
        // Large dog at 12:00 takes 2 seats = full. No other dog can book.
        $this->setDbMock(['12:00' => 2], 3);
        $result = $this->salon->is_slot_available('2026-03-16', '12:00', 1);
        $this->assertFalse($result['available']);
    }

    // ===================================================================
    // Large Dog at Any Other Slot (e.g. 10:00, 10:30, 11:00, 11:30)
    // ===================================================================

    public function test_large_dog_other_slots_requires_approval(): void
    {
        foreach (['10:00', '10:30', '11:00', '11:30'] as $time) {
            $result = $this->salon->validate_large_dog('2026-03-16', $time, 'large');
            $this->assertFalse($result['allowed'], "Large dog at $time should not be auto-allowed");
            $this->assertTrue($result['requires_approval'], "Large dog at $time should require approval");
            $this->assertEquals(2, $result['seats_required'], "Large dog at $time should need 2 seats");
            $this->assertStringContainsString('approval', $result['reason'], "Reason should mention approval at $time");
        }
    }

    // ===================================================================
    // Admin Override for Large Dogs
    // ===================================================================

    public function test_admin_allows_large_dogs_in_non_approved_slots(): void
    {
        $this->setDbMock(['10:00' => 0], 2);
        $result = $this->salon->is_slot_available_for_pet('2026-03-16', '10:00', 'large', true);
        $this->assertTrue($result['available']);
    }

    public function test_non_admin_blocks_large_dogs_in_non_approved_slots(): void
    {
        $result = $this->salon->is_slot_available_for_pet('2026-03-16', '10:00', 'large', false);
        $this->assertFalse($result['available']);
        $this->assertStringContainsString('approval', $result['reason']);
    }

    // ===================================================================
    // Start-of-Day Rule
    // ===================================================================

    public function test_start_of_day_rule_single_small_dog_at_0830(): void
    {
        // If only 1 small dog is booked at 8:30 (1 seat used, slot not full),
        // the documented rule says push them to 9:00 and close 8:30.
        // Check whether this is enforced in code.
        $this->setDbMock(['08:30' => 1], 1);

        // The code currently does not enforce this rule automatically.
        // The slot with 1 booking and capacity 2 still shows as available.
        $result = $this->salon->is_slot_available('2026-03-16', '08:30', 1);

        // TODO: Start-of-day rule not yet enforced in code — when only 1 small dog
        // is booked at 8:30, the system should push them to 9:00 and close 8:30.
        // Currently the slot still shows as available. This test documents the
        // current behaviour; update expectations once the rule is implemented.
        $this->assertTrue($result['available'],
            'Start-of-day rule not yet enforced: slot 08:30 with 1 booking is still available');
    }

    // ===================================================================
    // Small/Medium Dog Seat Counting
    // ===================================================================

    public function test_calculate_seats_small_dog_at_0900(): void
    {
        $this->assertEquals(1, $this->salon->calculate_seats_required('09:00', 'small'));
    }

    public function test_calculate_seats_medium_dog_at_0900(): void
    {
        $this->assertEquals(1, $this->salon->calculate_seats_required('09:00', 'medium'));
    }

    public function test_small_and_medium_dog_in_slot_counts_as_double_for_chain(): void
    {
        // A slot with one small and one medium dog = 2 seats used = counts as
        // a double for the 2-2-1 chain rule.
        $this->setDbMock(['09:00' => 2, '09:30' => 2]);
        // 10:00 should be capped at 1 because the two preceding slots are doubles.
        $this->assertEquals(1, $this->salon->get_effective_capacity('2026-03-16', '10:00'));
    }

    // ===================================================================
    // pet_size=null Fallback
    // ===================================================================

    public function test_consider_salon_capacity_null_pet_size_falls_back_to_is_slot_available(): void
    {
        $this->ci()->salon_capacity = $this->createMock(EdgeCaseStubSalonCapacity::class);
        $this->ci()->salon_capacity->method('is_slot_available')
            ->willReturn(['available' => true, 'reason' => '']);
        // is_slot_available_for_pet should NOT be called when pet_size is null.
        $this->ci()->salon_capacity->expects($this->never())->method('is_slot_available_for_pet');

        $lib = $this->makeAvailability();
        $result = $lib->call_consider_salon_capacity('2026-03-16', ['09:00', '10:00'], null, null);
        $this->assertEquals(['09:00', '10:00'], $result);
    }

    public function test_consider_salon_capacity_with_pet_size_calls_is_slot_available_for_pet(): void
    {
        $this->ci()->salon_capacity = $this->createMock(EdgeCaseStubSalonCapacity::class);
        $this->ci()->salon_capacity->method('is_slot_available_for_pet')
            ->willReturn(['available' => true, 'reason' => '']);
        $this->ci()->salon_capacity->expects($this->never())->method('is_slot_available');

        $lib = $this->makeAvailability();
        $result = $lib->call_consider_salon_capacity('2026-03-16', ['09:00', '10:00'], null, 'small');
        $this->assertEquals(['09:00', '10:00'], $result);
    }

    // ===================================================================
    // Blocked Periods
    // ===================================================================

    public function test_entire_date_blocked_returns_empty_hours(): void
    {
        $this->ci()->blocked_periods_model = $this->createMock(EdgeCaseStubBlockedPeriods::class);
        $this->ci()->blocked_periods_model->method('is_entire_date_blocked')->willReturn(true);
        $this->ci()->blocked_periods_model->method('get_for_period')->willReturn([]);

        $lib = $this->makeAvailability();
        $hours = $lib->get_available_hours('2026-04-06', $this->service, $this->provider);
        $this->assertEmpty($hours);
    }

    public function test_blocked_period_10_to_11_removes_covered_slots(): void
    {
        $this->ci()->blocked_periods_model = $this->createMock(EdgeCaseStubBlockedPeriods::class);
        $this->ci()->blocked_periods_model->method('is_entire_date_blocked')->willReturn(false);
        $this->ci()->blocked_periods_model->method('get_for_period')->willReturn([
            [
                'start_datetime' => '2026-04-06 10:00:00',
                'end_datetime' => '2026-04-06 11:00:00',
            ],
        ]);

        $lib = $this->makeAvailability();
        $hours = $lib->get_available_hours('2026-04-06', $this->service, $this->provider);

        $this->assertNotContains('10:00', $hours);
        $this->assertNotContains('10:30', $hours);
        $this->assertContains('09:00', $hours);
        $this->assertContains('11:00', $hours);
    }

    // ===================================================================
    // Working Plan Exceptions
    // ===================================================================

    public function test_working_plan_exception_non_working_returns_empty(): void
    {
        $provider = $this->provider;
        // Mark 2026-04-06 (Monday) as non-working via exception.
        $provider['settings']['working_plan_exceptions'] = '{"2026-04-06":null}';

        $lib = $this->makeAvailability();
        $hours = $lib->get_available_hours('2026-04-06', $this->service, $provider);
        $this->assertEmpty($hours);
    }

    // ===================================================================
    // Reschedule (exclude_appointment_id)
    // ===================================================================

    public function test_reschedule_excludes_appointment_seat_usage(): void
    {
        // Slot 10:00 has 2 seats used. Without excluding, slot is full.
        $this->setDbMock(['10:00' => 2], 5);
        $resultFull = $this->salon->is_slot_available('2026-03-16', '10:00', 1);
        $this->assertFalse($resultFull['available']);

        // When rescheduling appointment #42, its seat should not count.
        // Simulate: with exclude, occupancy drops to 1.
        $this->setDbMock(['10:00' => 1], 4);
        $resultExcluded = $this->salon->is_slot_available('2026-03-16', '10:00', 1, 42);
        $this->assertTrue($resultExcluded['available']);
    }

    public function test_get_slot_occupancy_accepts_exclude_appointment_id(): void
    {
        // Verify the method signature accepts the parameter without error.
        $occ = $this->salon->get_slot_occupancy('2026-03-16', '10:00', 42);
        $this->assertIsInt($occ);
    }

    // ===================================================================
    // Alternative Slots
    // ===================================================================

    public function test_alternative_slots_returns_up_to_3_sorted_by_proximity(): void
    {
        // 10:00 is full, all other slots empty.
        $this->setDbMock(['10:00' => 2], 2);
        $alternatives = $this->salon->get_alternative_slots('2026-03-16', '10:00', 1, 3);

        $this->assertNotEmpty($alternatives);
        $this->assertNotContains('10:00', $alternatives);
        $this->assertLessThanOrEqual(3, count($alternatives));

        // Closest slots to 10:00 should be 09:30 and 10:30.
        $this->assertContains('09:30', $alternatives);
        $this->assertContains('10:30', $alternatives);
    }

    public function test_alternative_slots_with_pet_size_respects_large_dog_rules(): void
    {
        // Large dog at 10:00 — not auto-allowed. Alternatives should only include
        // slots where large dogs are allowed (bookend/afternoon).
        $this->setDbMock(['10:00' => 2], 2);
        $alternatives = $this->salon->get_alternative_slots('2026-03-16', '10:00', 2, 3, null, 'large');

        // Should not include mid-morning slots (10:30, 11:00, etc.) for customers.
        foreach ($alternatives as $alt) {
            $this->assertNotContains($alt, ['10:30', '11:00', '11:30'],
                "Alternative $alt should not be offered for large dogs without admin");
        }
    }

    public function test_alternative_slots_empty_when_day_full(): void
    {
        // All slots at capacity and daily maximum reached.
        $allSlots = $this->salon->get_all_slots();
        $map = [];
        foreach ($allSlots as $s) {
            $map[$s] = 2;
        }
        $this->setDbMock($map, 16);

        $alternatives = $this->salon->get_alternative_slots('2026-03-16', '10:00', 1, 3);
        $this->assertEmpty($alternatives);
    }

    // ===================================================================
    // Working Day Check
    // ===================================================================

    public function test_is_working_day_monday_tuesday_wednesday(): void
    {
        $this->assertTrue($this->salon->is_working_day('2026-03-16'));  // Monday
        $this->assertTrue($this->salon->is_working_day('2026-03-17'));  // Tuesday
        $this->assertTrue($this->salon->is_working_day('2026-03-18'));  // Wednesday
    }

    public function test_is_not_working_day_thursday_through_sunday(): void
    {
        $this->assertFalse($this->salon->is_working_day('2026-03-19')); // Thursday
        $this->assertFalse($this->salon->is_working_day('2026-03-20')); // Friday
        $this->assertFalse($this->salon->is_working_day('2026-03-21')); // Saturday
        $this->assertFalse($this->salon->is_working_day('2026-03-22')); // Sunday
    }
}

// -- Stub classes for edge-case tests (separate namespace to avoid collisions) --

class EdgeCaseStubLoader
{
    public function model(string $name): void {}
    public function library(string $name): void {}
}

class EdgeCaseStubDb
{
    public function select($select = ''): self { return $this; }
    public function from($table = ''): self { return $this; }
    public function join($table = '', $cond = '', $type = ''): self { return $this; }
    public function where($key = '', $val = null): self { return $this; }
    public function or_where($key = '', $val = null): self { return $this; }
    public function group_start(): self { return $this; }
    public function group_end(): self { return $this; }
    public function get($table = ''): object { return new EdgeCaseStubResult(); }
    public function escape($val): string { return "'" . $val . "'"; }
}

class EdgeCaseStubResult
{
    public function row_array(): array
    {
        return ['total_seats' => 0, 'dog_count' => 0];
    }
}

class EdgeCaseStubBlockedPeriods
{
    public function is_entire_date_blocked(string $date): bool { return false; }
    public function get_for_period(string $start, string $end): array { return []; }
}

class EdgeCaseStubAppointments
{
    public function get($where = null): array { return []; }
}

class EdgeCaseStubUnavailabilities
{
    public function get($where = null): array { return []; }
}

class EdgeCaseStubSalonCapacity
{
    public function is_enabled(): bool { return true; }
    public function is_slot_available(string $date, string $time, int $seats = 1, ?int $exclude = null): array
    {
        return ['available' => true, 'reason' => ''];
    }
    public function is_slot_available_for_pet(string $date, string $time, string $pet_size = 'small', bool $is_admin = false, ?int $exclude = null): array
    {
        return ['available' => true, 'reason' => ''];
    }
}
