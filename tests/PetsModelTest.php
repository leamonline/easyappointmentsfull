<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/stubs/ci_stubs.php';
require_once __DIR__ . '/stubs/MockDb.php';
require_once __DIR__ . '/../application/models/Pets_model.php';

class PetsModelTest extends TestCase
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
    //  Helpers
    // -----------------------------------------------------------------

    private function validPet(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Biscuit',
            'id_users_customer' => 1,
            'breed' => 'Cockapoo',
            'size' => 'small',
        ], $overrides);
    }

    // =================================================================
    //  validate() tests
    // =================================================================

    public function test_validate_passes_with_valid_data(): void
    {
        $this->model->validate($this->validPet());
        $this->assertTrue(true);
    }

    public function test_validate_requires_name(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('name is required');

        $this->model->validate($this->validPet(['name' => '']));
    }

    public function test_validate_requires_customer_id(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('assigned to a customer');

        $this->model->validate($this->validPet(['id_users_customer' => 0]));
    }

    public function test_validate_rejects_invalid_size(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid pet size');

        $this->model->validate($this->validPet(['size' => 'huge']));
    }

    public function test_validate_allows_valid_sizes(): void
    {
        foreach (['small', 'medium', 'large'] as $size) {
            $this->model->validate($this->validPet(['size' => $size]));
        }

        $this->assertTrue(true);
    }

    public function test_validate_allows_empty_size(): void
    {
        $pet = $this->validPet();
        unset($pet['size']);

        $this->model->validate($pet);
        $this->assertTrue(true);
    }

    public function test_validate_rejects_nonexistent_pet_id(): void
    {
        $this->db->setNumRows(0);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('does not exist');

        $this->model->validate($this->validPet(['id' => 999]));
    }

    public function test_validate_accepts_existing_pet_id(): void
    {
        $this->db->setNumRows(1);

        $this->model->validate($this->validPet(['id' => 1]));
        $this->assertTrue(true);
    }

    // =================================================================
    //  save() routing tests
    // =================================================================

    public function test_save_inserts_when_no_id(): void
    {
        $this->db->setInsertId(42);

        $result = $this->model->save($this->validPet());

        $this->assertSame(42, $result);
    }

    public function test_save_updates_when_id_present(): void
    {
        $this->db->setNumRows(1);

        $result = $this->model->save($this->validPet(['id' => 7]));

        $this->assertSame(7, $result);
    }

    public function test_save_throws_on_failed_insert(): void
    {
        $this->db->setLastQuerySuccess(false);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Could not insert pet');

        $this->model->save($this->validPet());
    }

    public function test_save_throws_on_failed_update(): void
    {
        $this->db->setNumRows(1);
        $this->db->setLastQuerySuccess(false);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Could not update pet');

        $this->model->save($this->validPet(['id' => 7]));
    }

    // =================================================================
    //  find() tests
    // =================================================================

    public function test_find_returns_pet_with_cast_types(): void
    {
        $this->db->setRowResult([
            'id' => '5',
            'id_users_customer' => '12',
            'name' => 'Biscuit',
            'breed' => 'Cockapoo',
            'size' => 'small',
        ]);

        $pet = $this->model->find(5);

        $this->assertSame(5, $pet['id']);
        $this->assertSame(12, $pet['id_users_customer']);
        $this->assertSame('Biscuit', $pet['name']);
    }

    public function test_find_throws_for_missing_pet(): void
    {
        $this->db->setRowResult(null);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not found');

        $this->model->find(999);
    }

    // =================================================================
    //  get_by_customer() tests
    // =================================================================

    public function test_get_by_customer_returns_pets(): void
    {
        $this->db->setResult([
            ['id' => '1', 'id_users_customer' => '10', 'name' => 'Biscuit', 'size' => 'small'],
            ['id' => '2', 'id_users_customer' => '10', 'name' => 'Muffin', 'size' => 'medium'],
        ]);

        $pets = $this->model->get_by_customer(10);

        $this->assertCount(2, $pets);
        $this->assertSame(1, $pets[0]['id']);
        $this->assertSame(2, $pets[1]['id']);
    }

    public function test_get_by_customer_returns_empty_for_unknown(): void
    {
        $this->db->setResult([]);

        $pets = $this->model->get_by_customer(9999);

        $this->assertSame([], $pets);
    }

    // =================================================================
    //  search() tests
    // =================================================================

    public function test_search_returns_matching_pets(): void
    {
        $this->db->setResult([
            ['id' => '3', 'id_users_customer' => '5', 'name' => 'Buddy', 'breed' => 'Labrador', 'size' => 'large'],
        ]);

        $pets = $this->model->search('Buddy');

        $this->assertCount(1, $pets);
        $this->assertSame(3, $pets[0]['id']);
        $this->assertSame('Buddy', $pets[0]['name']);
    }

    public function test_search_returns_empty_for_no_match(): void
    {
        $this->db->setResult([]);

        $pets = $this->model->search('nonexistent');

        $this->assertSame([], $pets);
    }

    // =================================================================
    //  Edge cases & data integrity
    // =================================================================

    public function test_validate_rejects_whitespace_only_name(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->model->validate($this->validPet(['name' => '   ']));
    }
}
