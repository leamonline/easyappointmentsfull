<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

// Require the library under test (it's not autoloaded).
require_once __DIR__ . '/../application/libraries/Salon_capacity.php';

/**
 * Tests for the Salon_capacity library.
 *
 * Covers: 2-2-1 capacity rule, large dog restrictions, daily limits,
 * edge cases around first/last slots, and salon_mode disabled behaviour.
 */
class SalonCapacityTest extends TestCase
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
        $ci->load = new StubLoader();
        $ci->db = $this->createDefaultDbMock(0);

        $GLOBALS['_ci_instance'] = $ci;

        $this->lib = new \Salon_capacity();
    }

    protected function tearDown(): void
    {
        $GLOBALS['_test_settings'] = [];
        // Keep a valid CI instance (typed property cannot be null).
        $GLOBALS['_ci_instance'] = new \EA_Controller();
        parent::tearDown();
    }

    // -- Helpers -------------------------------------------------------

    private function createDefaultDbMock(int $value): object
    {
        $result = $this->createMock(StubResult::class);
        $result->method('row_array')->willReturn([
            'total_seats' => $value,
            'dog_count' => $value,
        ]);

        $db = $this->createMock(StubDb::class);
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
        $db = $this->getMockBuilder(StubDb::class)->getMock();
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
            $result = $this->createMock(StubResult::class);

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

    // -- salon_mode disabled -------------------------------------------

    public function test_is_enabled_returns_true_when_salon_mode_on(): void
    {
        $GLOBALS['_test_settings']['salon_mode'] = '1';
        $this->assertTrue($this->lib->is_enabled());
    }

    public function test_is_enabled_returns_false_when_salon_mode_off(): void
    {
        $GLOBALS['_test_settings']['salon_mode'] = '0';
        $this->assertFalse($this->lib->is_enabled());
    }

    public function test_is_enabled_returns_false_when_salon_mode_null(): void
    {
        unset($GLOBALS['_test_settings']['salon_mode']);
        $this->assertFalse($this->lib->is_enabled());
    }

    // -- get_all_slots -------------------------------------------------

    public function test_get_all_slots_returns_correct_slots(): void
    {
        $expected = ['08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00'];
        $this->assertEquals($expected, $this->lib->get_all_slots());
    }

    public function test_get_all_slots_with_custom_duration(): void
    {
        $GLOBALS['_test_settings']['salon_slot_duration'] = '60';
        $GLOBALS['_test_settings']['salon_start_time'] = '09:00';
        $GLOBALS['_test_settings']['salon_last_booking_time'] = '12:00';
        $this->lib = new \Salon_capacity();

        $this->assertEquals(['09:00', '10:00', '11:00', '12:00'], $this->lib->get_all_slots());
    }

    // -- 2-2-1 capacity enforcement ------------------------------------

    public function test_effective_capacity_is_2_when_no_bookings(): void
    {
        $this->assertEquals(2, $this->lib->get_effective_capacity('2026-03-16', '10:00'));
    }

    public function test_221_rule_caps_third_consecutive_slot_to_1(): void
    {
        $this->setDbMock(['09:00' => 2, '09:30' => 2]);
        $this->assertEquals(1, $this->lib->get_effective_capacity('2026-03-16', '10:00'));
    }

    public function test_221_rule_caps_slot_before_two_consecutive_doubles(): void
    {
        $this->setDbMock(['10:00' => 2, '10:30' => 2]);
        $this->assertEquals(1, $this->lib->get_effective_capacity('2026-03-16', '09:30'));
    }

    public function test_221_rule_caps_middle_slot_between_doubles(): void
    {
        $this->setDbMock(['09:30' => 2, '10:30' => 2]);
        $this->assertEquals(1, $this->lib->get_effective_capacity('2026-03-16', '10:00'));
    }

    public function test_221_rule_allows_2_when_only_one_adjacent_double(): void
    {
        $this->setDbMock(['09:30' => 2]);
        $this->assertEquals(2, $this->lib->get_effective_capacity('2026-03-16', '10:00'));
    }

    public function test_221_rule_long_chain_caps_middle(): void
    {
        $this->setDbMock(['09:30' => 2, '10:00' => 2, '11:00' => 2, '11:30' => 2]);
        $this->assertEquals(1, $this->lib->get_effective_capacity('2026-03-16', '10:30'));
    }

    // -- Edge cases: first and last slots ------------------------------

    public function test_first_slot_capped_when_next_two_are_double(): void
    {
        $this->setDbMock(['09:00' => 2, '09:30' => 2]);
        $this->assertEquals(1, $this->lib->get_effective_capacity('2026-03-16', '08:30'));
    }

    public function test_first_slot_allows_2_with_one_next_double(): void
    {
        $this->setDbMock(['09:00' => 2]);
        $this->assertEquals(2, $this->lib->get_effective_capacity('2026-03-16', '08:30'));
    }

    public function test_last_slot_capped_when_prev_two_are_double(): void
    {
        $this->setDbMock(['12:00' => 2, '12:30' => 2]);
        $this->assertEquals(1, $this->lib->get_effective_capacity('2026-03-16', '13:00'));
    }

    public function test_last_slot_allows_2_normally(): void
    {
        $this->assertEquals(2, $this->lib->get_effective_capacity('2026-03-16', '13:00'));
    }

    public function test_invalid_slot_time_returns_zero_capacity(): void
    {
        $this->assertEquals(0, $this->lib->get_effective_capacity('2026-03-16', '15:00'));
    }

    // -- Large dog restrictions ----------------------------------------

    public function test_non_large_dog_always_allowed(): void
    {
        $result = $this->lib->validate_large_dog('2026-03-16', '10:00', 'small');
        $this->assertTrue($result['allowed']);
        $this->assertFalse($result['requires_approval']);
        $this->assertEquals(1, $result['seats_required']);

        $result = $this->lib->validate_large_dog('2026-03-16', '10:00', 'medium');
        $this->assertTrue($result['allowed']);
    }

    public function test_large_dog_at_0830_allowed_1_seat(): void
    {
        $result = $this->lib->validate_large_dog('2026-03-16', '08:30', 'large');
        $this->assertTrue($result['allowed']);
        $this->assertFalse($result['requires_approval']);
        $this->assertEquals(1, $result['seats_required']);
    }

    public function test_large_dog_at_0900_allowed_when_0830_free(): void
    {
        $this->setDbMock(['08:30' => 0, '10:00' => 1]);
        $result = $this->lib->validate_large_dog('2026-03-16', '09:00', 'large');
        $this->assertTrue($result['allowed']);
        $this->assertEquals(1, $result['seats_required']);
    }

    public function test_large_dog_at_0900_denied_when_0830_occupied(): void
    {
        $this->setDbMock(['08:30' => 1]);
        $result = $this->lib->validate_large_dog('2026-03-16', '09:00', 'large');
        $this->assertFalse($result['allowed']);
        $this->assertTrue($result['requires_approval']);
    }

    public function test_large_dog_at_0900_denied_when_1000_has_2_bookings(): void
    {
        $this->setDbMock(['08:30' => 0, '10:00' => 2]);
        $result = $this->lib->validate_large_dog('2026-03-16', '09:00', 'large');
        $this->assertFalse($result['allowed']);
        $this->assertTrue($result['requires_approval']);
    }

    public function test_large_dog_at_afternoon_slots_takes_2_seats(): void
    {
        foreach (['12:00', '12:30', '13:00'] as $time) {
            $result = $this->lib->validate_large_dog('2026-03-16', $time, 'large');
            $this->assertTrue($result['allowed'], "Large dog should be allowed at $time");
            $this->assertEquals(2, $result['seats_required'], "Large dog at $time should take 2 seats");
            $this->assertFalse($result['requires_approval']);
        }
    }

    public function test_large_dog_at_other_slots_requires_approval(): void
    {
        foreach (['10:00', '10:30', '11:00', '11:30'] as $time) {
            $result = $this->lib->validate_large_dog('2026-03-16', $time, 'large');
            $this->assertFalse($result['allowed'], "Large dog at $time should not be auto-allowed");
            $this->assertTrue($result['requires_approval'], "Large dog at $time should require approval");
            $this->assertEquals(2, $result['seats_required']);
        }
    }

    // -- calculate_seats_required --------------------------------------

    public function test_small_dog_always_1_seat(): void
    {
        $this->assertEquals(1, $this->lib->calculate_seats_required('10:00', 'small'));
        $this->assertEquals(1, $this->lib->calculate_seats_required('12:00', 'small'));
    }

    public function test_large_dog_at_early_slots_1_seat(): void
    {
        $this->assertEquals(1, $this->lib->calculate_seats_required('08:30', 'large'));
        $this->assertEquals(1, $this->lib->calculate_seats_required('09:00', 'large'));
    }

    public function test_large_dog_at_other_slots_2_seats(): void
    {
        $this->assertEquals(2, $this->lib->calculate_seats_required('10:00', 'large'));
        $this->assertEquals(2, $this->lib->calculate_seats_required('12:00', 'large'));
        $this->assertEquals(2, $this->lib->calculate_seats_required('13:00', 'large'));
    }

    // -- Daily dog limit -----------------------------------------------

    public function test_slot_available_when_under_daily_limit(): void
    {
        $this->setDbMock([], 5);
        $result = $this->lib->is_slot_available('2026-03-16', '10:00', 1);
        $this->assertTrue($result['available']);
    }

    public function test_slot_unavailable_when_daily_limit_reached(): void
    {
        $this->setDbMock([], 16);
        $result = $this->lib->is_slot_available('2026-03-16', '10:00', 1);
        $this->assertFalse($result['available']);
        $this->assertStringContainsString('Daily maximum', $result['reason']);
    }

    public function test_slot_unavailable_when_daily_limit_exceeded(): void
    {
        $this->setDbMock([], 20);
        $result = $this->lib->is_slot_available('2026-03-16', '10:00', 1);
        $this->assertFalse($result['available']);
    }

    public function test_custom_daily_limit(): void
    {
        $GLOBALS['_test_settings']['salon_max_dogs_per_day'] = '8';
        $this->setDbMock([], 8);
        $result = $this->lib->is_slot_available('2026-03-16', '10:00', 1);
        $this->assertFalse($result['available']);
        $this->assertStringContainsString('8', $result['reason']);
    }

    // -- is_slot_available capacity checks -----------------------------

    public function test_slot_available_with_room(): void
    {
        $this->setDbMock(['10:00' => 1], 5);
        $result = $this->lib->is_slot_available('2026-03-16', '10:00', 1);
        $this->assertTrue($result['available']);
    }

    public function test_slot_unavailable_when_at_capacity(): void
    {
        $this->setDbMock(['10:00' => 2], 5);
        $result = $this->lib->is_slot_available('2026-03-16', '10:00', 1);
        $this->assertFalse($result['available']);
        $this->assertStringContainsString('capacity', $result['reason']);
    }

    public function test_slot_unavailable_for_2_seats_when_only_1_left(): void
    {
        $this->setDbMock(['10:00' => 1], 5);
        $result = $this->lib->is_slot_available('2026-03-16', '10:00', 2);
        $this->assertFalse($result['available']);
    }

    // -- is_working_day ------------------------------------------------

    public function test_is_working_day_on_configured_days(): void
    {
        $this->assertTrue($this->lib->is_working_day('2026-03-16'));  // Monday
        $this->assertTrue($this->lib->is_working_day('2026-03-17'));  // Tuesday
        $this->assertTrue($this->lib->is_working_day('2026-03-18'));  // Wednesday
    }

    public function test_is_not_working_day_on_other_days(): void
    {
        $this->assertFalse($this->lib->is_working_day('2026-03-19')); // Thursday
        $this->assertFalse($this->lib->is_working_day('2026-03-21')); // Saturday
    }

    // -- get_alternative_slots -----------------------------------------

    public function test_get_alternative_slots_returns_nearby_available(): void
    {
        $this->setDbMock(['10:00' => 2], 2);
        $alternatives = $this->lib->get_alternative_slots('2026-03-16', '10:00', 1, 3);

        $this->assertNotEmpty($alternatives);
        $this->assertNotContains('10:00', $alternatives);
        $this->assertLessThanOrEqual(3, count($alternatives));
        $this->assertContains('09:30', $alternatives);
        $this->assertContains('10:30', $alternatives);
    }
}

// -- Stub classes (global namespace via Tests\ — referenced by FQN in mocks) --

class StubLoader
{
    public function model(string $name): void
    {
    }
}

class StubDb
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
        return new StubResult();
    }
}

class StubResult
{
    public function row_array(): array
    {
        return ['total_seats' => 0, 'dog_count' => 0];
    }
}
