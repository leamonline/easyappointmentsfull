<?php

namespace Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

// Require the model under test.
require_once __DIR__ . '/stubs/MockDb.php';
require_once __DIR__ . '/../application/helpers/validation_helper.php';
require_once __DIR__ . '/../application/models/Appointments_model.php';
require_once __DIR__ . '/../application/models/Pets_model.php';

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

        $ci->pets_model = new \Pets_model();

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

    public function test_valid_appointment_with_zero_pet_id_passes(): void
    {
        // id_pets = 0 is falsy — empty(0) is true, so pet validation is skipped.
        $appt = $this->validAppointment;
        $appt['id_pets'] = 0;

        $this->model->validate($appt);
        $this->assertTrue(true);
    }

    public function test_valid_appointment_with_empty_string_pet_id_passes(): void
    {
        // id_pets = '' is falsy — empty('') is true, so pet validation is skipped.
        $appt = $this->validAppointment;
        $appt['id_pets'] = '';

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

    public function test_seats_required_null_defaults_to_1(): void
    {
        // Explicit null — the ?? operator defaults to 1.
        $appt = $this->validAppointment;
        $appt['seats_required'] = null;

        $this->model->validate($appt);
        $this->assertTrue(true);
    }

    public function test_seats_required_2_with_large_dog_appointment_passes(): void
    {
        // Scenario: large dog occupies two slots across 12:00–13:00.
        $petRow = ['id' => 10, 'id_users_customer' => 100];
        $this->ci()->db = $this->createDbMockWithPet($petRow);
        $this->model = new \Appointments_model();

        $appt = $this->validAppointment;
        $appt['start_datetime'] = '2026-04-06 12:00:00';
        $appt['end_datetime'] = '2026-04-06 13:00:00';
        $appt['id_pets'] = 10;
        $appt['seats_required'] = 2;

        $this->model->validate($appt);
        $this->assertTrue(true);
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

    // ===================================================================
    // Datetime validation
    // ===================================================================

    public function test_invalid_start_datetime_throws(): void
    {
        $appt = $this->validAppointment;
        $appt['start_datetime'] = 'not-a-date';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('start date time is invalid');

        $this->model->validate($appt);
    }

    public function test_invalid_end_datetime_throws(): void
    {
        $appt = $this->validAppointment;
        $appt['end_datetime'] = 'not-a-date';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('end date time is invalid');

        $this->model->validate($appt);
    }

    public function test_start_after_end_throws(): void
    {
        $appt = $this->validAppointment;
        $appt['start_datetime'] = '2026-04-06 11:00:00';
        $appt['end_datetime'] = '2026-04-06 10:00:00';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('duration cannot be less than');

        $this->model->validate($appt);
    }

    public function test_equal_start_and_end_throws(): void
    {
        $appt = $this->validAppointment;
        $appt['start_datetime'] = '2026-04-06 10:00:00';
        $appt['end_datetime'] = '2026-04-06 10:00:00';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('duration cannot be less than');

        $this->model->validate($appt);
    }

    public function test_valid_start_before_end_passes(): void
    {
        // Explicit positive test: start < end with sufficient duration.
        $appt = $this->validAppointment;
        $appt['start_datetime'] = '2026-04-06 09:00:00';
        $appt['end_datetime'] = '2026-04-06 09:30:00';

        $this->model->validate($appt);
        $this->assertTrue(true);
    }

    public function test_duration_exactly_minimum_passes(): void
    {
        // EVENT_MINIMUM_DURATION is 5 minutes — boundary should pass.
        $appt = $this->validAppointment;
        $appt['start_datetime'] = '2026-04-06 10:00:00';
        $appt['end_datetime'] = '2026-04-06 10:05:00';

        $this->model->validate($appt);
        $this->assertTrue(true);
    }

    public function test_duration_below_minimum_throws(): void
    {
        // 4-minute duration is below EVENT_MINIMUM_DURATION (5 minutes).
        $appt = $this->validAppointment;
        $appt['start_datetime'] = '2026-04-06 10:00:00';
        $appt['end_datetime'] = '2026-04-06 10:04:00';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('duration cannot be less than');

        $this->model->validate($appt);
    }

    // ===================================================================
    // save() — insert path (no id)
    // ===================================================================

    /**
     * Create a MockDb pre-configured so all validate() existence checks pass.
     */
    private function createMockDbForSave(): \MockDb
    {
        $db = new \MockDb();
        $db->setNumRows(1);

        return $db;
    }

    public function test_save_insert_returns_new_id(): void
    {
        $db = $this->createMockDbForSave();
        $db->setInsertId(42);
        $this->ci()->db = $db;
        $this->model = new \Appointments_model();

        $result = $this->model->save($this->validAppointment);

        $this->assertSame(42, $result);
    }

    public function test_save_insert_with_null_id_returns_new_id(): void
    {
        $db = $this->createMockDbForSave();
        $db->setInsertId(10);
        $this->ci()->db = $db;
        $this->model = new \Appointments_model();

        $appt = $this->validAppointment;
        $appt['id'] = null;

        $result = $this->model->save($appt);

        $this->assertSame(10, $result);
    }

    public function test_save_insert_throws_on_failed_insert(): void
    {
        $db = $this->createMockDbForSave();
        $db->setLastQuerySuccess(false);
        $this->ci()->db = $db;
        $this->model = new \Appointments_model();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Could not insert appointment');

        $this->model->save($this->validAppointment);
    }

    // ===================================================================
    // save() — update path (existing id)
    // ===================================================================

    public function test_save_update_returns_existing_id(): void
    {
        $db = $this->createMockDbForSave();
        $this->ci()->db = $db;
        $this->model = new \Appointments_model();

        $appt = $this->validAppointment;
        $appt['id'] = 7;

        $result = $this->model->save($appt);

        $this->assertSame(7, $result);
    }

    public function test_save_update_throws_on_failed_update(): void
    {
        $db = $this->createMockDbForSave();
        $db->setLastQuerySuccess(false);
        $this->ci()->db = $db;
        $this->model = new \Appointments_model();

        $appt = $this->validAppointment;
        $appt['id'] = 7;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Could not update appointment');

        $this->model->save($appt);
    }

    // ===================================================================
    // delete()
    // ===================================================================

    public function test_delete_completes_without_error(): void
    {
        $db = $this->createMockDbForSave();
        $this->ci()->db = $db;
        $this->model = new \Appointments_model();

        $this->model->delete(5);
        $this->assertTrue(true);
    }

    public function test_delete_with_nonexistent_id_does_not_throw(): void
    {
        $db = $this->createMockDbForSave();
        $this->ci()->db = $db;
        $this->model = new \Appointments_model();

        $this->model->delete(9999);
        $this->assertTrue(true);
    }

    public function test_delete_does_not_throw_for_appointment_with_pet(): void
    {
        // Appointment linked to a pet — delete runs a simple DELETE query.
        // The ON DELETE SET NULL cascade is handled at the DB level.
        $db = $this->createMockDbForSave();
        $this->ci()->db = $db;
        $this->model = new \Appointments_model();

        $this->model->delete(5);
        $this->assertTrue(true);
    }

    // ===================================================================
    // Vaccination gate
    // ===================================================================

    /**
     * Set up CI instance with a pet row for vaccination gate tests.
     * The Pets_model is attached to the CI instance so load->model() finds it.
     */
    private function setupVaccinationTest(array $petRow): void
    {
        $this->ci()->db = $this->createDbMockWithPet($petRow);

        $pets_model = new \Pets_model();
        $this->ci()->pets_model = $pets_model;

        $this->model = new \Appointments_model();
    }

    private function puppyAppointment(int $petId = 5): array
    {
        $appt = $this->validAppointment;
        $appt['id_pets'] = $petId;

        return $appt;
    }

    public function test_puppy_with_unknown_vaccination_throws(): void
    {
        $this->setupVaccinationTest([
            'id' => 5,
            'id_users_customer' => 100,
            'age' => '4 months',
            'breed' => 'Labrador',
            'vaccination_status' => 'unknown',
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Puppies must have completed their second vaccinations before booking.');

        $this->model->validate($this->puppyAppointment());
    }

    public function test_puppy_with_pending_first_throws(): void
    {
        $this->setupVaccinationTest([
            'id' => 5,
            'id_users_customer' => 100,
            'age' => '3 months',
            'breed' => 'Poodle',
            'vaccination_status' => 'pending_first',
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Puppies must have completed their second vaccinations before booking.');

        $this->model->validate($this->puppyAppointment());
    }

    public function test_puppy_with_pending_second_throws(): void
    {
        $this->setupVaccinationTest([
            'id' => 5,
            'id_users_customer' => 100,
            'age' => '5 months',
            'breed' => 'Beagle',
            'vaccination_status' => 'pending_second',
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Puppies must have completed their second vaccinations before booking.');

        $this->model->validate($this->puppyAppointment());
    }

    public function test_puppy_with_up_to_date_passes(): void
    {
        $this->setupVaccinationTest([
            'id' => 5,
            'id_users_customer' => 100,
            'age' => '4 months',
            'breed' => 'Labrador',
            'vaccination_status' => 'up_to_date',
        ]);

        $this->model->validate($this->puppyAppointment());
        $this->assertTrue(true);
    }

    public function test_adult_with_unknown_vaccination_passes(): void
    {
        $this->setupVaccinationTest([
            'id' => 5,
            'id_users_customer' => 100,
            'age' => '2 years',
            'breed' => 'Labrador',
            'vaccination_status' => 'unknown',
        ]);

        $this->model->validate($this->puppyAppointment());
        $this->assertTrue(true);
    }

    public function test_breed_puppy_indicator_with_unknown_throws(): void
    {
        $this->setupVaccinationTest([
            'id' => 5,
            'id_users_customer' => 100,
            'age' => null,
            'breed' => 'Labrador puppy',
            'vaccination_status' => 'unknown',
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Puppies must have completed their second vaccinations before booking.');

        $this->model->validate($this->puppyAppointment());
    }

    public function test_age_field_puppy_with_pending_first_throws(): void
    {
        $this->setupVaccinationTest([
            'id' => 5,
            'id_users_customer' => 100,
            'age' => 'puppy',
            'breed' => 'Spaniel',
            'vaccination_status' => 'pending_first',
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Puppies must have completed their second vaccinations before booking.');

        $this->model->validate($this->puppyAppointment());
    }

    public function test_admin_override_bypasses_vaccination_gate(): void
    {
        $this->setupVaccinationTest([
            'id' => 5,
            'id_users_customer' => 100,
            'age' => '4 months',
            'breed' => 'Labrador',
            'vaccination_status' => 'unknown',
        ]);

        $this->model->validate($this->puppyAppointment(), ['is_admin' => true]);
        $this->assertTrue(true);
    }

    public function test_null_age_no_breed_indicator_passes(): void
    {
        $this->setupVaccinationTest([
            'id' => 5,
            'id_users_customer' => 100,
            'age' => null,
            'breed' => 'Labrador',
            'vaccination_status' => 'unknown',
        ]);

        $this->model->validate($this->puppyAppointment());
        $this->assertTrue(true);
    }

    public function test_unparseable_age_no_breed_indicator_passes(): void
    {
        $this->setupVaccinationTest([
            'id' => 5,
            'id_users_customer' => 100,
            'age' => 'old boy',
            'breed' => 'Poodle',
            'vaccination_status' => 'unknown',
        ]);

        $this->model->validate($this->puppyAppointment());
        $this->assertTrue(true);
    }

    public function test_six_months_exactly_passes(): void
    {
        $this->setupVaccinationTest([
            'id' => 5,
            'id_users_customer' => 100,
            'age' => '6 months',
            'breed' => 'Labrador',
            'vaccination_status' => 'unknown',
        ]);

        // 6.0 is NOT < 6.0, so this should pass.
        $this->model->validate($this->puppyAppointment());
        $this->assertTrue(true);
    }

    public function test_weeks_under_six_months_throws(): void
    {
        $this->setupVaccinationTest([
            'id' => 5,
            'id_users_customer' => 100,
            'age' => '8 weeks',
            'breed' => 'Terrier',
            'vaccination_status' => 'pending_first',
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Puppies must have completed their second vaccinations before booking.');

        $this->model->validate($this->puppyAppointment());
    }
}

// -- Stub classes --

class AppointmentsStubLoader
{
    public function model(string $name): void
    {
        // Attach the model instance to the CI controller if already set.
        $ci = $GLOBALS['_ci_instance'] ?? null;
        if ($ci && isset($ci->{$name})) {
            return; // already loaded
        }
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
