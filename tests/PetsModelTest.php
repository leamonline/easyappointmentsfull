<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/stubs/ci_stubs.php';
require_once __DIR__ . '/stubs/MockDb.php';
require_once __DIR__ . '/../application/models/Pets_model.php';

/**
 * Comprehensive test suite for Pets_model.
 *
 * Uses MockDb (chainable stub) to avoid any real database connection,
 * following the same patterns as SalonCapacityTest and AvailabilityTest.
 */
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

    /**
     * Build a valid pet array, merging any overrides on top of sensible defaults.
     */
    private function validPet(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Biscuit',
            'id_users_customer' => 1,
            'breed' => 'Cockapoo',
            'size' => 'small',
        ], $overrides);
    }

    /**
     * Build a full pet row as it would come back from the database (string values).
     */
    private function dbPetRow(array $overrides = []): array
    {
        return array_merge([
            'id' => '5',
            'id_users_customer' => '12',
            'name' => 'Biscuit',
            'breed' => 'Cockapoo',
            'size' => 'small',
            'age' => '3',
            'coat_notes' => 'Curly, matts easily',
            'vaccination_status' => 'up-to-date',
            'behavioural_notes' => 'Friendly with other dogs',
        ], $overrides);
    }

    // =================================================================
    //  validate() — missing name
    // =================================================================

    /**
     * Verifies that validate() passes when all required fields are present.
     */
    public function test_validate_passes_with_valid_data(): void
    {
        $this->model->validate($this->validPet());
        $this->assertTrue(true);
    }

    /**
     * Verifies that validate() throws when the name field is an empty string.
     */
    public function test_validate_requires_name(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('name is required');

        $this->model->validate($this->validPet(['name' => '']));
    }

    /**
     * Verifies that validate() throws when the name field contains only whitespace.
     */
    public function test_validate_rejects_whitespace_only_name(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('name is required');

        $this->model->validate($this->validPet(['name' => '   ']));
    }

    /**
     * Verifies that validate() throws when the name key is missing entirely.
     */
    public function test_validate_rejects_missing_name_key(): void
    {
        $pet = $this->validPet();
        unset($pet['name']);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('name is required');

        $this->model->validate($pet);
    }

    // =================================================================
    //  validate() — missing id_users_customer
    // =================================================================

    /**
     * Verifies that validate() throws when id_users_customer is zero.
     */
    public function test_validate_requires_customer_id(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('assigned to a customer');

        $this->model->validate($this->validPet(['id_users_customer' => 0]));
    }

    /**
     * Verifies that validate() throws when id_users_customer is null.
     */
    public function test_validate_rejects_null_customer_id(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('assigned to a customer');

        $this->model->validate($this->validPet(['id_users_customer' => null]));
    }

    /**
     * Verifies that validate() throws when id_users_customer key is absent.
     */
    public function test_validate_rejects_missing_customer_id_key(): void
    {
        $pet = $this->validPet();
        unset($pet['id_users_customer']);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('assigned to a customer');

        $this->model->validate($pet);
    }

    // =================================================================
    //  validate() — invalid pet ID on update
    // =================================================================

    /**
     * Verifies that validate() queries the database when an id is provided
     * and throws if the pet ID does not exist (num_rows == 0).
     */
    public function test_validate_rejects_nonexistent_pet_id(): void
    {
        $this->db->setNumRows(0);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('does not exist');

        $this->model->validate($this->validPet(['id' => 999]));
    }

    /**
     * Verifies that validate() passes when the provided pet ID exists in the database.
     */
    public function test_validate_accepts_existing_pet_id(): void
    {
        $this->db->setNumRows(1);

        $this->model->validate($this->validPet(['id' => 1]));
        $this->assertTrue(true);
    }

    // =================================================================
    //  validate() — valid size values (small/medium/large)
    // =================================================================

    /**
     * Verifies that validate() accepts each of the three valid size values.
     */
    public function test_validate_allows_valid_sizes(): void
    {
        foreach (['small', 'medium', 'large'] as $size) {
            $this->model->validate($this->validPet(['size' => $size]));
        }

        $this->assertTrue(true);
    }

    /**
     * Verifies that validate() rejects a size value not in the allowed set.
     */
    public function test_validate_rejects_invalid_size(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid pet size');

        $this->model->validate($this->validPet(['size' => 'huge']));
    }

    /**
     * Verifies that validate() rejects another arbitrary invalid size string.
     */
    public function test_validate_rejects_extra_large_size(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid pet size');

        $this->model->validate($this->validPet(['size' => 'extra-large']));
    }

    // =================================================================
    //  validate() — empty size allowed
    // =================================================================

    /**
     * Verifies that validate() passes when the size key is omitted entirely
     * (size is optional).
     */
    public function test_validate_allows_empty_size(): void
    {
        $pet = $this->validPet();
        unset($pet['size']);

        $this->model->validate($pet);
        $this->assertTrue(true);
    }

    /**
     * Verifies that validate() passes when size is an empty string
     * (treated as falsy, so the in_array check is skipped).
     */
    public function test_validate_allows_empty_string_size(): void
    {
        $this->model->validate($this->validPet(['size' => '']));
        $this->assertTrue(true);
    }

    /**
     * Verifies that validate() passes when size is null.
     */
    public function test_validate_allows_null_size(): void
    {
        $this->model->validate($this->validPet(['size' => null]));
        $this->assertTrue(true);
    }

    // =================================================================
    //  validate() — coat_notes and behavioural_notes as optional
    // =================================================================

    /**
     * Verifies that validate() passes when coat_notes is provided.
     */
    public function test_validate_accepts_coat_notes(): void
    {
        $this->model->validate($this->validPet(['coat_notes' => 'Curly, matts easily']));
        $this->assertTrue(true);
    }

    /**
     * Verifies that validate() passes when coat_notes is omitted.
     */
    public function test_validate_passes_without_coat_notes(): void
    {
        $pet = $this->validPet();
        unset($pet['coat_notes']);

        $this->model->validate($pet);
        $this->assertTrue(true);
    }

    /**
     * Verifies that validate() passes when behavioural_notes is provided.
     */
    public function test_validate_accepts_behavioural_notes(): void
    {
        $this->model->validate($this->validPet(['behavioural_notes' => 'Anxious around cats']));
        $this->assertTrue(true);
    }

    /**
     * Verifies that validate() passes when behavioural_notes is omitted.
     */
    public function test_validate_passes_without_behavioural_notes(): void
    {
        $pet = $this->validPet();
        unset($pet['behavioural_notes']);

        $this->model->validate($pet);
        $this->assertTrue(true);
    }

    /**
     * Verifies that validate() passes when both optional note fields are empty strings.
     */
    public function test_validate_passes_with_empty_string_notes(): void
    {
        $this->model->validate($this->validPet([
            'coat_notes' => '',
            'behavioural_notes' => '',
        ]));
        $this->assertTrue(true);
    }

    // =================================================================
    //  validate() — vaccination_status is optional with free-form values
    // =================================================================

    /**
     * Verifies that validate() passes when vaccination_status is omitted entirely.
     * The DB ENUM default of 'unknown' is applied at the database level.
     */
    public function test_validate_passes_without_vaccination_status(): void
    {
        $pet = $this->validPet();
        unset($pet['vaccination_status']);

        $this->model->validate($pet);
        $this->assertTrue(true);
    }

    /**
     * Verifies that validate() accepts 'up_to_date' as a vaccination_status value.
     */
    public function test_validate_accepts_vaccination_status_up_to_date(): void
    {
        $this->model->validate($this->validPet(['vaccination_status' => 'up_to_date']));
        $this->assertTrue(true);
    }

    /**
     * Verifies that validate() accepts 'pending_second' as a vaccination_status value.
     */
    public function test_validate_accepts_vaccination_status_pending_second(): void
    {
        $this->model->validate($this->validPet(['vaccination_status' => 'pending_second']));
        $this->assertTrue(true);
    }

    /**
     * Verifies that validate() accepts 'unknown' as a vaccination_status value.
     */
    public function test_validate_accepts_vaccination_status_unknown(): void
    {
        $this->model->validate($this->validPet(['vaccination_status' => 'unknown']));
        $this->assertTrue(true);
    }

    /**
     * Verifies that validate() accepts null vaccination_status without throwing.
     */
    public function test_validate_accepts_null_vaccination_status(): void
    {
        $this->model->validate($this->validPet(['vaccination_status' => null]));
        $this->assertTrue(true);
    }

    // =================================================================
    //  save() — insert path (empty id)
    // =================================================================

    /**
     * Verifies that save() routes to insert when no id is present
     * and returns the auto-generated insert ID.
     */
    public function test_save_inserts_when_no_id(): void
    {
        $this->db->setInsertId(42);

        $result = $this->model->save($this->validPet());

        $this->assertSame(42, $result);
    }

    /**
     * Verifies that save() routes to insert when id is explicitly null.
     */
    public function test_save_inserts_when_id_is_null(): void
    {
        $this->db->setInsertId(10);

        $result = $this->model->save($this->validPet(['id' => null]));

        $this->assertSame(10, $result);
    }

    /**
     * Verifies that save() routes to insert when id is zero (falsy).
     */
    public function test_save_inserts_when_id_is_zero(): void
    {
        $this->db->setInsertId(15);

        $result = $this->model->save($this->validPet(['id' => 0]));

        $this->assertSame(15, $result);
    }

    /**
     * Verifies that save() inserts successfully when vaccination_status is absent.
     * The DB ENUM default ('unknown') is applied at the database layer, not the model.
     */
    public function test_save_insert_succeeds_without_vaccination_status(): void
    {
        $this->db->setInsertId(50);

        $pet = $this->validPet();
        unset($pet['vaccination_status']);

        $result = $this->model->save($pet);

        $this->assertSame(50, $result);
    }

    /**
     * Verifies that save() throws RuntimeException when the database insert fails.
     */
    public function test_save_throws_on_failed_insert(): void
    {
        $this->db->setLastQuerySuccess(false);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Could not insert pet');

        $this->model->save($this->validPet());
    }

    // =================================================================
    //  save() — update path (existing id)
    // =================================================================

    /**
     * Verifies that save() routes to update when a positive id is present
     * and returns the same pet ID.
     */
    public function test_save_updates_when_id_present(): void
    {
        $this->db->setNumRows(1);

        $result = $this->model->save($this->validPet(['id' => 7]));

        $this->assertSame(7, $result);
    }

    /**
     * Verifies that save() throws RuntimeException when the database update fails.
     */
    public function test_save_throws_on_failed_update(): void
    {
        $this->db->setNumRows(1);
        $this->db->setLastQuerySuccess(false);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Could not update pet');

        $this->model->save($this->validPet(['id' => 7]));
    }

    /**
     * Verifies that save() calls validate() before attempting an update,
     * so a nonexistent pet ID triggers InvalidArgumentException, not RuntimeException.
     */
    public function test_save_validates_before_update(): void
    {
        $this->db->setNumRows(0);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('does not exist');

        $this->model->save($this->validPet(['id' => 999]));
    }

    // =================================================================
    //  delete() — valid deletion
    // =================================================================

    /**
     * Verifies that delete() completes without throwing when given a valid pet ID.
     * MockDb::delete() returns true by default.
     */
    public function test_delete_succeeds(): void
    {
        $this->model->delete(5);
        $this->assertTrue(true);
    }

    /**
     * Verifies that delete() can be called with any integer pet ID
     * without error (the model does not check existence before deleting).
     */
    public function test_delete_with_nonexistent_pet_does_not_throw(): void
    {
        $this->db->setLastQuerySuccess(true);

        $this->model->delete(9999);
        $this->assertTrue(true);
    }

    // =================================================================
    //  find() — single pet retrieval
    // =================================================================

    /**
     * Verifies that find() returns a pet array with integer-cast id and
     * id_users_customer fields (matching the model's $casts definition).
     */
    public function test_find_returns_pet_with_cast_types(): void
    {
        $this->db->setRowResult($this->dbPetRow());

        $pet = $this->model->find(5);

        $this->assertSame(5, $pet['id']);
        $this->assertSame(12, $pet['id_users_customer']);
        $this->assertSame('Biscuit', $pet['name']);
        $this->assertSame('Cockapoo', $pet['breed']);
        $this->assertSame('small', $pet['size']);
    }

    /**
     * Verifies that find() preserves all non-cast fields as-is from the DB row.
     */
    public function test_find_preserves_optional_fields(): void
    {
        $this->db->setRowResult($this->dbPetRow());

        $pet = $this->model->find(5);

        $this->assertSame('Curly, matts easily', $pet['coat_notes']);
        $this->assertSame('Friendly with other dogs', $pet['behavioural_notes']);
        $this->assertSame('up-to-date', $pet['vaccination_status']);
    }

    // =================================================================
    //  find() — pet not found
    // =================================================================

    /**
     * Verifies that find() throws InvalidArgumentException when the DB returns
     * null (no matching row).
     */
    public function test_find_throws_for_missing_pet(): void
    {
        $this->db->setRowResult(null);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not found');

        $this->model->find(999);
    }

    // =================================================================
    //  get() — filtering by customer ID
    // =================================================================

    /**
     * Verifies that get() returns multiple pets when given a where clause
     * and casts integer fields on each row.
     */
    public function test_get_returns_filtered_pets_by_customer(): void
    {
        $this->db->setResult([
            $this->dbPetRow(['id' => '1', 'id_users_customer' => '10', 'name' => 'Biscuit']),
            $this->dbPetRow(['id' => '2', 'id_users_customer' => '10', 'name' => 'Muffin']),
        ]);

        $pets = $this->model->get(['id_users_customer' => 10]);

        $this->assertCount(2, $pets);
        $this->assertSame(1, $pets[0]['id']);
        $this->assertSame(10, $pets[0]['id_users_customer']);
        $this->assertSame(2, $pets[1]['id']);
        $this->assertSame(10, $pets[1]['id_users_customer']);
    }

    // =================================================================
    //  get() — filtering by size
    // =================================================================

    /**
     * Verifies that get() works when filtering by size and returns
     * correctly cast results.
     */
    public function test_get_returns_filtered_pets_by_size(): void
    {
        $this->db->setResult([
            $this->dbPetRow(['id' => '3', 'name' => 'Buddy', 'size' => 'large']),
        ]);

        $pets = $this->model->get(['size' => 'large']);

        $this->assertCount(1, $pets);
        $this->assertSame('large', $pets[0]['size']);
        $this->assertSame('Buddy', $pets[0]['name']);
    }

    // =================================================================
    //  get() — empty results
    // =================================================================

    /**
     * Verifies that get() returns an empty array when no pets match the criteria.
     */
    public function test_get_returns_empty_when_no_match(): void
    {
        $this->db->setResult([]);

        $pets = $this->model->get(['id_users_customer' => 9999]);

        $this->assertSame([], $pets);
    }

    /**
     * Verifies that get() returns an empty array when called with no where clause
     * and the table is empty.
     */
    public function test_get_returns_empty_with_no_where_and_no_data(): void
    {
        $this->db->setResult([]);

        $pets = $this->model->get();

        $this->assertSame([], $pets);
    }

    // =================================================================
    //  get_by_customer() tests
    // =================================================================

    /**
     * Verifies that get_by_customer() delegates to get() with the correct
     * where clause and returns cast results.
     */
    public function test_get_by_customer_returns_pets(): void
    {
        $this->db->setResult([
            $this->dbPetRow(['id' => '1', 'id_users_customer' => '10', 'name' => 'Biscuit', 'size' => 'small']),
            $this->dbPetRow(['id' => '2', 'id_users_customer' => '10', 'name' => 'Muffin', 'size' => 'medium']),
        ]);

        $pets = $this->model->get_by_customer(10);

        $this->assertCount(2, $pets);
        $this->assertSame(1, $pets[0]['id']);
        $this->assertSame(2, $pets[1]['id']);
    }

    /**
     * Verifies that get_by_customer() returns an empty array for a customer
     * with no pets.
     */
    public function test_get_by_customer_returns_empty_for_unknown(): void
    {
        $this->db->setResult([]);

        $pets = $this->model->get_by_customer(9999);

        $this->assertSame([], $pets);
    }

    // =================================================================
    //  search() tests
    // =================================================================

    /**
     * Verifies that search() returns matching pets with integer-cast fields.
     */
    public function test_search_returns_matching_pets(): void
    {
        $this->db->setResult([
            $this->dbPetRow(['id' => '3', 'id_users_customer' => '5', 'name' => 'Buddy', 'breed' => 'Labrador', 'size' => 'large']),
        ]);

        $pets = $this->model->search('Buddy');

        $this->assertCount(1, $pets);
        $this->assertSame(3, $pets[0]['id']);
        $this->assertSame('Buddy', $pets[0]['name']);
    }

    /**
     * Verifies that search() returns an empty array when no pets match the keyword.
     */
    public function test_search_returns_empty_for_no_match(): void
    {
        $this->db->setResult([]);

        $pets = $this->model->search('nonexistent');

        $this->assertSame([], $pets);
    }

    // =================================================================
    //  API field mapping (db_field)
    // =================================================================

    /**
     * Verifies that db_field() correctly maps API-style camelCase field names
     * to their database column equivalents using the $api_resource property.
     */
    public function test_db_field_maps_api_fields_to_db_columns(): void
    {
        $this->assertSame('id', $this->model->db_field('id'));
        $this->assertSame('id_users_customer', $this->model->db_field('customerId'));
        $this->assertSame('name', $this->model->db_field('name'));
        $this->assertSame('breed', $this->model->db_field('breed'));
        $this->assertSame('size', $this->model->db_field('size'));
        $this->assertSame('age', $this->model->db_field('age'));
        $this->assertSame('coat_notes', $this->model->db_field('coatNotes'));
        $this->assertSame('vaccination_status', $this->model->db_field('vaccinationStatus'));
        $this->assertSame('behavioural_notes', $this->model->db_field('behaviouralNotes'));
    }

    /**
     * Verifies that db_field() returns null for an API field name that does not
     * exist in the $api_resource mapping.
     */
    public function test_db_field_returns_null_for_unknown_api_field(): void
    {
        $this->assertNull($this->model->db_field('nonExistentField'));
    }

    /**
     * Verifies that every entry in the $api_resource mapping resolves correctly
     * through db_field(), ensuring the mapping is complete and internally consistent.
     */
    public function test_api_resource_mapping_is_complete(): void
    {
        $expectedMappings = [
            'id' => 'id',
            'customerId' => 'id_users_customer',
            'name' => 'name',
            'breed' => 'breed',
            'size' => 'size',
            'age' => 'age',
            'coatNotes' => 'coat_notes',
            'vaccinationStatus' => 'vaccination_status',
            'behaviouralNotes' => 'behavioural_notes',
        ];

        foreach ($expectedMappings as $apiField => $dbColumn) {
            $this->assertSame(
                $dbColumn,
                $this->model->db_field($apiField),
                "API field '$apiField' should map to DB column '$dbColumn'",
            );
        }
    }

    // =================================================================
    //  Integer casting
    // =================================================================

    /**
     * Verifies that the model casts 'id' and 'id_users_customer' from strings
     * (as returned by MySQL) to integers, while leaving other fields as strings.
     */
    public function test_cast_only_applies_to_declared_fields(): void
    {
        $this->db->setRowResult($this->dbPetRow([
            'id' => '7',
            'id_users_customer' => '20',
            'age' => '4',
        ]));

        $pet = $this->model->find(7);

        $this->assertIsInt($pet['id']);
        $this->assertIsInt($pet['id_users_customer']);
        // age is not in $casts, so it stays as a string
        $this->assertIsString($pet['age']);
    }
}
