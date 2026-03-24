<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

// Load CI stubs and the library under test.
require_once __DIR__ . '/stubs/ci_stubs.php';
require_once __DIR__ . '/../application/libraries/Salon_capacity.php';

/**
 * Agent Boundary Tests for the Smarter Dog grooming booking system.
 *
 * Verifies that non-admin users (secretary/provider roles) are properly
 * restricted from actions that require admin approval or are reserved
 * for the business owner.
 *
 * Tests are split into two groups:
 * - @group agent-boundary: Tests for rules that ARE implemented in code
 * - @group todo: Tests for rules that are NOT YET implemented (marked incomplete)
 *
 * @group agent-boundary
 */
class AgentBoundaryTest extends TestCase
{
    private \Salon_capacity $lib;

    private array $defaultSettings = [
        'salon_mode' => '1',
        'salon_start_time' => '08:30',
        'salon_last_booking_time' => '13:00',
        'salon_slot_duration' => '30',
        'salon_max_seats_per_slot' => '2',
        'salon_max_dogs_per_day' => '16',
        'salon_working_days' => 'monday,tuesday,wednesday',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $GLOBALS['_test_settings'] = $this->defaultSettings;

        $ci = new \EA_Controller();
        $ci->load = new BoundaryStubLoader();
        $ci->db = $this->createDefaultDbMock(0);

        $GLOBALS['_ci_instance'] = $ci;

        $this->lib = new \Salon_capacity();
    }

    protected function tearDown(): void
    {
        $GLOBALS['_test_settings'] = [];
        $GLOBALS['_ci_instance'] = new \EA_Controller();
        parent::tearDown();
    }

    // -- Helpers -------------------------------------------------------

    private function createDefaultDbMock(int $value): object
    {
        $result = $this->createMock(BoundaryStubResult::class);
        $result->method('row_array')->willReturn([
            'total_seats' => $value,
            'dog_count' => $value,
        ]);

        $db = $this->createMock(BoundaryStubDb::class);
        $db->method('select')->willReturnSelf();
        $db->method('from')->willReturnSelf();
        $db->method('join')->willReturnSelf();
        $db->method('where')->willReturnSelf();
        $db->method('or_where')->willReturnSelf();
        $db->method('group_start')->willReturnSelf();
        $db->method('group_end')->willReturnSelf();
        $db->method('get')->willReturn($result);

        return $db;
    }

    private function createOccupancyDbMock(array $occupancyMap, int $dogCount = 0): object
    {
        $db = $this->getMockBuilder(BoundaryStubDb::class)->getMock();
        $db->method('select')->willReturnSelf();
        $db->method('from')->willReturnSelf();
        $db->method('join')->willReturnSelf();
        $db->method('or_where')->willReturnSelf();
        $db->method('group_start')->willReturnSelf();
        $db->method('group_end')->willReturnSelf();

        $lastWhereArgs = new \stdClass();
        $lastWhereArgs->values = [];

        $db->method('where')->willReturnCallback(function ($key, $val = null) use ($db, $lastWhereArgs) {
            if ($val !== null) {
                $lastWhereArgs->values[$key] = $val;
            }
            return $db;
        });

        $db->method('get')->willReturnCallback(function () use ($occupancyMap, $dogCount, $lastWhereArgs) {
            $result = $this->createMock(BoundaryStubResult::class);

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
        $this->lib = new \Salon_capacity();
    }

    // ===================================================================
    // Large dog slot enforcement — non-admin restrictions
    // ===================================================================

    /**
     * Non-admin MUST NOT book a large dog at 10:00 (requires approval).
     *
     * 10:00 is not in the agent-approved large-dog slots (08:30, 09:00
     * conditional, 12:00-13:00). The library returns requires_approval=true,
     * and since is_admin=false the override does not apply.
     */
    public function test_large_dog_at_non_approved_slot_blocked_for_non_admin(): void
    {
        $this->setDbMock([], 0);

        $result = $this->lib->is_slot_available_for_pet('2025-06-02', '10:00', 'large', false);

        $this->assertFalse($result['available'], 'Large dog at 10:00 must be blocked for non-admin');
        $this->assertStringContainsString('approval', $result['reason']);
    }

    /**
     * Non-admin CAN book a large dog at 12:30 (agent-approved afternoon slot).
     *
     * 12:30 is an approved large-dog slot taking 2 seats. With an empty
     * salon this should succeed regardless of admin status.
     */
    public function test_large_dog_at_approved_afternoon_slot_allowed_for_non_admin(): void
    {
        $this->setDbMock(['12:30' => 0], 0);

        $result = $this->lib->is_slot_available_for_pet('2025-06-02', '12:30', 'large', false);

        $this->assertTrue($result['available'], 'Large dog at 12:30 must be allowed (approved slot)');
    }

    /**
     * Non-admin CAN book a large dog at 08:30 (agent-approved morning slot).
     */
    public function test_large_dog_at_0830_allowed_for_non_admin(): void
    {
        $this->setDbMock(['08:30' => 0], 0);

        $result = $this->lib->is_slot_available_for_pet('2025-06-02', '08:30', 'large', false);

        $this->assertTrue($result['available'], 'Large dog at 08:30 must be allowed (approved slot)');
    }

    /**
     * Non-admin blocked at every mid-morning slot for large dogs.
     *
     * Slots 09:30, 10:00, 10:30, 11:00, 11:30 all require approval.
     */
    public function test_large_dog_blocked_at_all_mid_morning_slots_for_non_admin(): void
    {
        $blockedSlots = ['09:30', '10:00', '10:30', '11:00', '11:30'];

        foreach ($blockedSlots as $time) {
            $this->setDbMock([], 0);
            $result = $this->lib->is_slot_available_for_pet('2025-06-02', $time, 'large', false);

            $this->assertFalse(
                $result['available'],
                "Large dog at {$time} must be blocked for non-admin",
            );
        }
    }

    // ===================================================================
    // Large dog slot enforcement — admin override
    // ===================================================================

    /**
     * Admin CAN override the large-dog restriction at 10:00.
     *
     * When is_admin=true, slots with requires_approval=true are allowed.
     */
    public function test_large_dog_at_non_approved_slot_allowed_for_admin(): void
    {
        $this->setDbMock(['10:00' => 0], 0);

        $result = $this->lib->is_slot_available_for_pet('2025-06-02', '10:00', 'large', true);

        $this->assertTrue($result['available'], 'Admin must be able to override approval-required large dog slots');
    }

    /**
     * Admin CAN override all mid-morning slots for large dogs.
     */
    public function test_large_dog_allowed_at_all_mid_morning_slots_for_admin(): void
    {
        $approvalSlots = ['09:30', '10:00', '10:30', '11:00', '11:30'];

        foreach ($approvalSlots as $time) {
            $this->setDbMock([$time => 0], 0);
            $result = $this->lib->is_slot_available_for_pet('2025-06-02', $time, 'large', true);

            $this->assertTrue(
                $result['available'],
                "Admin must be able to book large dog at {$time}",
            );
        }
    }

    // ===================================================================
    // 2-2-1 capacity rule — cannot be overridden
    // ===================================================================

    /**
     * The 2-2-1 capacity rule CANNOT be overridden even by admin.
     *
     * When two consecutive slots are at max capacity (2), the next is capped
     * to 1. If that slot already has 1 booking, even an admin cannot add more.
     */
    public function test_221_capacity_rule_cannot_be_overridden_by_admin(): void
    {
        // 09:00=2, 09:30=2 triggers 2-2-1 → 10:00 capped to 1 seat.
        // 10:00 already has 1 booking — full.
        $this->setDbMock(['09:00' => 2, '09:30' => 2, '10:00' => 1], 5);

        $result = $this->lib->is_slot_available_for_pet('2025-06-02', '10:00', 'small', true);

        $this->assertFalse($result['available'], '2-2-1 rule is a hard capacity limit; admin cannot override');
    }

    /**
     * 2-2-1 blocks admin large dog needing 2 seats in a slot capped to 1.
     *
     * Even though admin bypasses the approval gate, capacity still applies.
     */
    public function test_221_blocks_admin_large_dog_needing_2_seats_when_capped_to_1(): void
    {
        // 09:00=2, 09:30=2 → 10:00 capped to 1.
        // Large dog needs 2 seats. 0 + 2 > 1 → blocked.
        $this->setDbMock(['09:00' => 2, '09:30' => 2, '10:00' => 0], 2);

        $result = $this->lib->is_slot_available_for_pet('2025-06-02', '10:00', 'large', true);

        $this->assertFalse($result['available'], 'Large dog needing 2 seats cannot fit in slot capped to 1 by 2-2-1 rule');
    }

    // ===================================================================
    // Small/medium dogs unaffected by large-dog gates
    // ===================================================================

    /**
     * Small dogs are not subject to large-dog approval gates.
     *
     * A non-admin booking a small dog at 10:00 (a restricted slot for
     * large dogs) should succeed if capacity allows.
     */
    public function test_small_dog_not_subject_to_large_dog_gates(): void
    {
        $this->setDbMock(['10:00' => 0], 2);

        $result = $this->lib->is_slot_available_for_pet('2025-06-02', '10:00', 'small', false);

        $this->assertTrue($result['available'], 'Small dogs should not be affected by large-dog slot restrictions');
    }

    // ===================================================================
    // @group todo — NOT YET IMPLEMENTED
    // ===================================================================

    /**
     * Same-day booking by non-admin should require admin approval.
     *
     * CURRENT STATE: No code checks whether start_datetime is today
     * relative to the user's role. All same-day bookings pass through.
     *
     * @group todo
     */
    public function test_same_day_booking_blocked_for_non_admin(): void
    {
        $this->markTestIncomplete(
            'Requirement: Same-day bookings by non-admin users (secretary/provider) '
            . 'should be rejected or marked tentative. No date-vs-role check exists in '
            . 'Calendar::save_appointment() or Booking::register(). '
            . 'Implementation needed in: application/controllers/Calendar.php and '
            . 'application/controllers/Booking.php.',
        );
    }

    /**
     * Same-day booking by admin should succeed without restriction.
     *
     * @group todo
     */
    public function test_same_day_booking_allowed_for_admin(): void
    {
        $this->markTestIncomplete(
            'Requirement: Same-day bookings by admin should succeed without restriction. '
            . 'Cannot test until same-day gate is implemented for non-admin users.',
        );
    }

    /**
     * Booking a pet with behavioural_notes containing "aggressive" should
     * be flagged for admin review.
     *
     * CURRENT STATE: No code inspects behavioural_notes during booking
     * validation. The notes are stored but never gate the booking flow.
     *
     * @group todo
     */
    public function test_aggressive_dog_gate_blocks_non_admin(): void
    {
        $this->markTestIncomplete(
            'Requirement: Pets with behavioural_notes containing "aggressive", "reactive", '
            . '"nervous", or "bites" should require admin approval when booked by non-admin. '
            . 'No code inspects behavioural_notes during validation. '
            . 'Implementation needed: a new method (e.g. Salon_capacity::validate_behavioural_risk()) '
            . 'called from Calendar::save_appointment() and Booking::register().',
        );
    }

    /**
     * "reactive" keyword in behavioural_notes should trigger the same gate.
     *
     * @group todo
     */
    public function test_reactive_dog_gate_blocks_non_admin(): void
    {
        $this->markTestIncomplete(
            'Requirement: "reactive" keyword in behavioural_notes should trigger the same '
            . 'approval gate as "aggressive". See test_aggressive_dog_gate_blocks_non_admin.',
        );
    }

    /**
     * Non-admin users should not be able to change appointment pricing.
     *
     * CURRENT STATE: No pricing validation exists in Calendar::save_appointment().
     * The price is accepted as-is from the request payload.
     *
     * @group todo
     */
    public function test_non_admin_cannot_change_pricing(): void
    {
        $this->markTestIncomplete(
            'Requirement: Non-admin users must not override service pricing. '
            . 'Calendar::save_appointment() accepts price from request without '
            . 'validating against the service record or checking user role.',
        );
    }

    /**
     * Non-admin users should not be able to apply discounts.
     *
     * @group todo
     */
    public function test_non_admin_cannot_apply_discounts(): void
    {
        $this->markTestIncomplete(
            'Requirement: Non-admin users must not apply discounts. '
            . 'No discount system exists in the codebase yet. When implemented, '
            . 'discount application must be gated by admin role.',
        );
    }

    /**
     * Non-admin users should not be able to issue refunds.
     *
     * @group todo
     */
    public function test_non_admin_cannot_issue_refunds(): void
    {
        $this->markTestIncomplete(
            'Requirement: Non-admin users must not issue refunds. '
            . 'No refund functionality exists. When implemented, '
            . 'it must require admin role.',
        );
    }

    /**
     * BUG: Calendar::save_appointment() always passes is_admin=true to
     * is_slot_available_for_pet(), regardless of actual user role.
     *
     * Location: application/controllers/Calendar.php line 311
     * Fix: replace hardcoded true with session('role_slug') === DB_SLUG_ADMIN
     *
     * @group todo
     */
    public function test_calendar_save_appointment_should_check_actual_role(): void
    {
        $this->markTestIncomplete(
            'BUG: Calendar::save_appointment() at line 311 always passes '
            . 'is_admin=true to is_slot_available_for_pet(). This gives secretary '
            . 'and provider users admin-level override for large-dog approval slots. '
            . 'Fix: replace hardcoded true with session(\'role_slug\') === DB_SLUG_ADMIN.',
        );
    }

    /**
     * BUG: Booking::register() allows requires_approval bookings through
     * without marking them as tentative.
     *
     * Location: application/controllers/Booking.php lines 470-472
     * Fix: when requires_approval=true, set status to tentative and notify admin.
     *
     * @group todo
     */
    public function test_frontend_booking_should_mark_requires_approval_as_tentative(): void
    {
        $this->markTestIncomplete(
            'BUG: Booking::register() at lines 470-472 only blocks hard failures. '
            . 'When requires_approval=true, the booking proceeds without tentative '
            . 'status or admin notification. Fix: set status to tentative and notify admin.',
        );
    }
}

// -- Stub classes (uniquely named to avoid conflicts with SalonCapacityTest) --

class BoundaryStubLoader
{
    public function model(string $name): void
    {
    }

    public function library(string $name): void
    {
    }
}

class BoundaryStubDb
{
    public function select($select = ''): self
    {
        return $this;
    }

    public function from($table = ''): self
    {
        return $this;
    }

    public function join($table = '', $cond = '', $type = ''): self
    {
        return $this;
    }

    public function where($key = '', $val = null): self
    {
        return $this;
    }

    public function or_where($key = '', $val = null): self
    {
        return $this;
    }

    public function group_start(): self
    {
        return $this;
    }

    public function group_end(): self
    {
        return $this;
    }

    public function get($table = ''): object
    {
        return new BoundaryStubResult();
    }
}

class BoundaryStubResult
{
    public function row_array(): array
    {
        return ['total_seats' => 0, 'dog_count' => 0];
    }
}
