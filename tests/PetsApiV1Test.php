<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/stubs/ci_stubs.php';
require_once __DIR__ . '/stubs/MockDb.php';
require_once __DIR__ . '/../application/models/Pets_model.php';

/**
 * Test suite for Pets_api_v1 controller.
 *
 * Since the controller depends on CodeIgniter's HTTP layer, these tests
 * focus on the model-level validation that the controller relies on for
 * its 400 responses (InvalidArgumentException from Pets_model::validate).
 */
class PetsApiV1Test extends TestCase
{
    private \Pets_model $model;
    private \MockDb $db;

    protected function setUp(): void
    {
        parent::setUp();

        $this->db = new \MockDb();
        $this->model = new \Pets_model();
        $this->model->db = $this->db;
    }

    // -----------------------------------------------------------------
    //  Controller file & structure
    // -----------------------------------------------------------------

    public function test_controller_file_exists(): void
    {
        $path = __DIR__ . '/../application/controllers/api/v1/Pets_api_v1.php';
        $this->assertFileExists($path);
    }

    public function test_controller_has_expected_methods(): void
    {
        // We can't instantiate the controller without full CI, but we can
        // verify the class file declares the expected REST methods.
        $source = file_get_contents(
            __DIR__ . '/../application/controllers/api/v1/Pets_api_v1.php',
        );

        $this->assertStringContainsString('function index()', $source);
        $this->assertStringContainsString('function show(', $source);
        $this->assertStringContainsString('function store()', $source);
        $this->assertStringContainsString('function update(', $source);
        $this->assertStringContainsString('function destroy(', $source);
    }

    public function test_controller_extends_ea_controller(): void
    {
        $source = file_get_contents(
            __DIR__ . '/../application/controllers/api/v1/Pets_api_v1.php',
        );

        $this->assertStringContainsString('extends EA_Controller', $source);
    }

    public function test_controller_loads_pets_model(): void
    {
        $source = file_get_contents(
            __DIR__ . '/../application/controllers/api/v1/Pets_api_v1.php',
        );

        $this->assertStringContainsString("model('pets_model')", $source);
    }

    public function test_controller_calls_api_auth(): void
    {
        $source = file_get_contents(
            __DIR__ . '/../application/controllers/api/v1/Pets_api_v1.php',
        );

        $this->assertStringContainsString('$this->api->auth()', $source);
    }

    // -----------------------------------------------------------------
    //  Size validation (model layer, triggered by controller on store/update)
    // -----------------------------------------------------------------

    public function test_valid_sizes_accepted(): void
    {
        foreach (['small', 'medium', 'large'] as $size) {
            $this->db->setNumRows(0); // No existing pet (insert path)
            $this->db->setInsertId(1);
            $this->db->setRowResult([
                'id' => '1',
                'id_users_customer' => '1',
                'name' => 'Biscuit',
                'breed' => 'Cockapoo',
                'size' => $size,
            ]);

            $pet = [
                'name' => 'Biscuit',
                'id_users_customer' => 1,
                'size' => $size,
            ];

            // Should not throw.
            $this->model->validate($pet);
            $this->addToAssertionCount(1);
        }
    }

    public function test_invalid_size_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid pet size');

        $pet = [
            'name' => 'Biscuit',
            'id_users_customer' => 1,
            'size' => 'extra_large',
        ];

        $this->model->validate($pet);
    }

    public function test_giant_size_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $pet = [
            'name' => 'Biscuit',
            'id_users_customer' => 1,
            'size' => 'giant',
        ];

        $this->model->validate($pet);
    }

    public function test_xl_size_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $pet = [
            'name' => 'Biscuit',
            'id_users_customer' => 1,
            'size' => 'xl',
        ];

        $this->model->validate($pet);
    }

    public function test_missing_name_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('name is required');

        $pet = [
            'id_users_customer' => 1,
            'size' => 'small',
        ];

        $this->model->validate($pet);
    }

    public function test_missing_customer_id_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('assigned to a customer');

        $pet = [
            'name' => 'Biscuit',
            'size' => 'small',
        ];

        $this->model->validate($pet);
    }

    // -----------------------------------------------------------------
    //  Vaccination status is stored but not enforced
    // -----------------------------------------------------------------

    public function test_any_vaccination_status_accepted_on_save(): void
    {
        $this->db->setNumRows(0);
        $this->db->setInsertId(1);

        $statuses = ['up_to_date', 'unknown', 'pending_first', 'pending_second', 'overdue'];

        foreach ($statuses as $status) {
            $pet = [
                'name' => 'Biscuit',
                'id_users_customer' => 1,
                'vaccination_status' => $status,
            ];

            // validate() should not reject any vaccination_status value
            $this->model->validate($pet);
            $this->addToAssertionCount(1);
        }
    }

    // -----------------------------------------------------------------
    //  API resource mapping
    // -----------------------------------------------------------------

    public function test_api_resource_mapping_has_expected_fields(): void
    {
        $expected = [
            'id',
            'customerId',
            'name',
            'breed',
            'size',
            'age',
            'coatNotes',
            'vaccinationStatus',
            'behaviouralNotes',
        ];

        foreach ($expected as $field) {
            $dbField = $this->model->db_field($field);
            $this->assertNotNull($dbField, "API field '$field' should be mapped");
        }
    }

    // -----------------------------------------------------------------
    //  Route configuration
    // -----------------------------------------------------------------

    public function test_pets_route_exists_in_config(): void
    {
        $routesFile = file_get_contents(
            __DIR__ . '/../application/config/routes.php',
        );

        $this->assertStringContainsString("route_api_resource(\$route, 'pets'", $routesFile);
    }
}
