<?php

namespace Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

// Require the model under test.
require_once __DIR__ . '/../application/models/Appointments_model.php';

/**
 * Tests for Appointments_model::validate() — id_pets and seats_required validations.
 */
class AppointmentsModelValidateTest extends TestCase
{
    private \Appointments_model $model;

    /** A valid appointment that passes all existing validations. */
    private array $validAppointment = [
        'start_datetime' => '2026-04-06 10:00:00',
        'end_datetime' => '2026-04-06 10:30:00',
        'id_services' => 1,
        'id_users_provider' => 1,
        'id_users_customer' => 100,
        'is_unavailability' => false,
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $GLOBALS['_test_settings'] = ['require_notes' => '0'];

        $ci = new \EA_Controller();
        $ci->load = new AppointmentsStubLoader();
        $ci->db = $this->createPassingDbMock();

        $GLOBALS['_ci_instance'] = $ci;

        $this->model = new \Appointments_model();
    }

    protected function tearDown(): void
    {
        $GLOBALS['_test_settings'] = [];
        $GLOBALS['_ci_instance'] = new \EA_Controller();
        parent::tearDown();
    }

    // -- Helpers -------------------------------------------------------

    /**
     * Create a DB mock where all existence checks pass (num_rows=1)
     * and get_where for pets returns the given pet row (or null).
     */
    private function createPassingDbMock(?array $petRow = null): object
    {
        $db = $this->getMockBuilder(AppointmentsStubDb::class)->getMock();
        $db->method('select')->willReturnSelf();
        $db->method('from')->willReturnSelf();
        $db->method('join')->willReturnSelf();
        $db->method('where')->willReturnSelf();

        // Default result: num_rows=1, row_array=petRow
        $result = $this->createMock(AppointmentsStubResult::class);
        $result->method('num_rows')->willReturn(1);
        $result->method('row_array')->willReturn($petRow);

        $db->method('get')->willReturn($result);
        $db->method('get_where')->willReturn($result);

        return $db;
    }

    /**
     * Create a DB mock where get_where('pets', ...) returns a specific pet,
     * while all other existence checks pass.
     */
    private function createDbMockWithPet(?array $petRow): object
    {
        $db = $this->getMockBuilder(AppointmentsStubDb::class)->getMock();
        $db->method('select')->willReturnSelf();
        $db->method('from')->willReturnSelf();
        $db->method('join')->willReturnSelf();
        $db->method('where')->willReturnSelf();

        // For join-based queries (provider, customer): num_rows=1
        $passingResult = $this->createMock(AppointmentsStubResult::class);
        $passingResult->method('num_rows')->willReturn(1);
        $passingResult->method('row_array')->willReturn(null);

        $db->method('get')->willReturn($passingResult);

        // For get_where calls: need to distinguish between 'pets' and others.
        $db->method('get_where')->willReturnCallback(
            function (string $table, array $where) use ($petRow) {
                $result = $this->createMock(AppointmentsStubResult::class);

                if ($table === 'pets') {
                    $result->method('num_rows')->willReturn($petRow ? 1 : 0);
                    $result->method('row_array')->willReturn($petRow);
                } else {
                    // appointments, services — pass
                    $result->method('num_rows')->willReturn(1);
                    $result->method('row_array')->willReturn(null);
                }

                return $result;
            },
        );

        return $db;
    }

    private function ci(): \EA_Controller
    {
        return $GLOBALS['_ci_instance'];
    }

    // ===================================================================
    // id_pets validation
    // ===================================================================

    public function test_valid_appointment_without_pet_passes(): void
    {
        // No id_pets field at all — should pass.
        $this->model->validate($this->validAppointment);
        $this->assertTrue(true); // No exception means pass.
    }

    public function test_valid_appointment_with_empty_pet_id_passes(): void
    {
        $appt = $this->validAppointment;
        $appt['id_pets'] = null;

        $this->model->validate($appt);
        $this->assertTrue(true);
    }

    public function test_valid_pet_belonging_to_customer_passes(): void
    {
        $petRow = ['id' => 5, 'id_users_customer' => 100];
        $this->ci()->db = $this->createDbMockWithPet($petRow);
        $this->model = new \Appointments_model();

        $appt = $this->validAppointment;
        $appt['id_pets'] = 5;

        $this->model->validate($appt);
        $this->assertTrue(true);
    }

    public function test_nonexistent_pet_id_throws(): void
    {
        // Pet not found in DB.
        $this->ci()->db = $this->createDbMockWithPet(null);
        $this->model = new \Appointments_model();

        $appt = $this->validAppointment;
        $appt['id_pets'] = 999;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('pet ID was not found');

        $this->model->validate($appt);
    }

    public function test_pet_belonging_to_different_customer_throws(): void
    {
        // Pet exists but belongs to customer 200, not 100.
        $petRow = ['id' => 5, 'id_users_customer' => 200];
        $this->ci()->db = $this->createDbMockWithPet($petRow);
        $this->model = new \Appointments_model();

        $appt = $this->validAppointment;
        $appt['id_pets'] = 5;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('does not belong to the appointment customer');

        $this->model->validate($appt);
    }

    public function test_pet_with_string_customer_id_matches_correctly(): void
    {
        // MySQL returns strings — ensure int comparison works.
        $petRow = ['id' => 5, 'id_users_customer' => '100'];
        $this->ci()->db = $this->createDbMockWithPet($petRow);
        $this->model = new \Appointments_model();

        $appt = $this->validAppointment;
        $appt['id_pets'] = 5;

        $this->model->validate($appt);
        $this->assertTrue(true);
    }

    // ===================================================================
    // seats_required validation
    // ===================================================================

    public function test_seats_required_defaults_to_1_when_missing(): void
    {
        // No seats_required field — should pass (defaults to 1).
        $this->model->validate($this->validAppointment);
        $this->assertTrue(true);
    }

    public function test_seats_required_1_passes(): void
    {
        $appt = $this->validAppointment;
        $appt['seats_required'] = 1;

        $this->model->validate($appt);
        $this->assertTrue(true);
    }

    public function test_seats_required_2_passes(): void
    {
        $appt = $this->validAppointment;
        $appt['seats_required'] = 2;

        $this->model->validate($appt);
        $this->assertTrue(true);
    }

    public function test_seats_required_0_throws(): void
    {
        $appt = $this->validAppointment;
        $appt['seats_required'] = 0;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('seats_required value must be 1 or 2');

        $this->model->validate($appt);
    }

    public function test_seats_required_3_throws(): void
    {
        $appt = $this->validAppointment;
        $appt['seats_required'] = 3;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('seats_required value must be 1 or 2');

        $this->model->validate($appt);
    }

    public function test_seats_required_negative_throws(): void
    {
        $appt = $this->validAppointment;
        $appt['seats_required'] = -1;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('seats_required value must be 1 or 2');

        $this->model->validate($appt);
    }

    public function test_seats_required_string_1_passes(): void
    {
        // String "1" should be cast and pass.
        $appt = $this->validAppointment;
        $appt['seats_required'] = '1';

        $this->model->validate($appt);
        $this->assertTrue(true);
    }

    public function test_seats_required_string_2_passes(): void
    {
        $appt = $this->validAppointment;
        $appt['seats_required'] = '2';

        $this->model->validate($appt);
        $this->assertTrue(true);
    }

    public function test_seats_required_string_invalid_throws(): void
    {
        $appt = $this->validAppointment;
        $appt['seats_required'] = '5';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('seats_required value must be 1 or 2');

        $this->model->validate($appt);
    }

    // ===================================================================
    // Unavailability appointments skip pet/seats validation
    // ===================================================================

    public function test_unavailability_appointment_skips_pet_and_seats_validation(): void
    {
        $appt = $this->validAppointment;
        $appt['is_unavailability'] = true;
        $appt['id_pets'] = 999;        // Invalid pet
        $appt['seats_required'] = 99;  // Invalid seats

        // Should NOT throw — unavailability skips customer/service/pet/seats validation.
        $this->model->validate($appt);
        $this->assertTrue(true);
    }
}

// -- Stub classes --

class AppointmentsStubLoader
{
    public function model(string $name): void
    {
    }

    public function library(string $name): void
    {
    }
}

class AppointmentsStubDb
{
    public function select($s = ''): self
    {
        return $this;
    }

    public function from($t = ''): self
    {
        return $this;
    }

    public function join($t = '', $c = '', $type = ''): self
    {
        return $this;
    }

    public function where($k = '', $v = null): self
    {
        return $this;
    }

    public function get($t = ''): object
    {
        return new AppointmentsStubResult();
    }

    public function get_where(string $table, array $where = []): object
    {
        return new AppointmentsStubResult();
    }
}

class AppointmentsStubResult
{
    public function num_rows(): int
    {
        return 1;
    }

    public function row_array(): ?array
    {
        return null;
    }
}
