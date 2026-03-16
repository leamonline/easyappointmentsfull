<?php defined('BASEPATH') or exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * Smarter Dog - Online Appointment Scheduler
 *
 * @package     SmarterDog
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://easyappointments.org
 * @since       v1.5.0
 * ---------------------------------------------------------------------------- */

/**
 * Pets model.
 *
 * Handles all the database operations of the pet resource.
 *
 * @package Models
 */
class Pets_model extends EA_Model
{
    /**
     * @var array
     */
    protected array $casts = [
        'id' => 'integer',
        'id_users_customer' => 'integer',
    ];

    /**
     * @var array
     */
    protected array $api_resource = [
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

    /**
     * Save (insert or update) a pet.
     *
     * @param array $pet Associative array with the pet data.
     *
     * @return int Returns the pet ID.
     *
     * @throws InvalidArgumentException
     */
    public function save(array $pet): int
    {
        $this->validate($pet);

        if (empty($pet['id'])) {
            return $this->insert($pet);
        } else {
            return $this->update($pet);
        }
    }

    /**
     * Validate the pet data.
     *
     * @param array $pet Associative array with the pet data.
     *
     * @throws InvalidArgumentException
     */
    public function validate(array $pet): void
    {
        if (!empty($pet['id'])) {
            $count = $this->db->get_where('pets', ['id' => $pet['id']])->num_rows();

            if (!$count) {
                throw new InvalidArgumentException(
                    'The provided pet ID does not exist in the database: ' . $pet['id'],
                );
            }
        }

        if (empty($pet['name'])) {
            throw new InvalidArgumentException('The pet name is required.');
        }

        if (empty($pet['id_users_customer'])) {
            throw new InvalidArgumentException('The pet must be assigned to a customer.');
        }

        if (!empty($pet['size']) && !in_array($pet['size'], ['small', 'medium', 'large'])) {
            throw new InvalidArgumentException('Invalid pet size: ' . $pet['size']);
        }
    }

    /**
     * Insert a new pet into the database.
     *
     * @param array $pet Associative array with the pet data.
     *
     * @return int Returns the pet ID.
     *
     * @throws RuntimeException
     */
    protected function insert(array $pet): int
    {
        $pet['create_datetime'] = date('Y-m-d H:i:s');
        $pet['update_datetime'] = date('Y-m-d H:i:s');

        if (!$this->db->insert('pets', $pet)) {
            throw new RuntimeException('Could not insert pet.');
        }

        return $this->db->insert_id();
    }

    /**
     * Update an existing pet.
     *
     * @param array $pet Associative array with the pet data.
     *
     * @return int Returns the pet ID.
     *
     * @throws RuntimeException
     */
    protected function update(array $pet): int
    {
        $pet['update_datetime'] = date('Y-m-d H:i:s');

        if (!$this->db->update('pets', $pet, ['id' => $pet['id']])) {
            throw new RuntimeException('Could not update pet.');
        }

        return $pet['id'];
    }

    /**
     * Remove an existing pet from the database.
     *
     * @param int $pet_id Pet ID.
     *
     * @throws RuntimeException
     */
    public function delete(int $pet_id): void
    {
        $this->db->delete('pets', ['id' => $pet_id]);
    }

    /**
     * Get a specific pet from the database.
     *
     * @param int $pet_id The ID of the record to be returned.
     *
     * @return array Returns an array with the pet data.
     */
    public function find(int $pet_id): array
    {
        $pet = $this->db->get_where('pets', ['id' => $pet_id])->row_array();

        if (!$pet) {
            throw new InvalidArgumentException(
                'The provided pet ID was not found in the database: ' . $pet_id,
            );
        }

        $this->cast($pet);

        return $pet;
    }

    /**
     * Get all pets that match the provided criteria.
     *
     * @param array|string|null $where Where conditions.
     * @param int|null $limit Record limit.
     * @param int|null $offset Record offset.
     * @param string|null $order_by Order by.
     *
     * @return array Returns an array of pets.
     */
    public function get(
        array|string|null $where = null,
        ?int $limit = null,
        ?int $offset = null,
        ?string $order_by = null,
    ): array {
        if ($where !== null) {
            $this->db->where($where);
        }

        if ($order_by !== null) {
            $this->db->order_by($this->quote_order_by($order_by));
        }

        $pets = $this->db->get('pets', $limit, $offset)->result_array();

        foreach ($pets as &$pet) {
            $this->cast($pet);
        }

        return $pets;
    }

    /**
     * Get all pets for a specific customer.
     *
     * @param int $customer_id Customer ID.
     *
     * @return array Returns an array of pets.
     */
    public function get_by_customer(int $customer_id): array
    {
        return $this->get(['id_users_customer' => $customer_id]);
    }

    /**
     * Search pets by the provided keyword.
     *
     * @param string $keyword Search keyword.
     * @param int|null $limit Record limit.
     * @param int|null $offset Record offset.
     * @param string|null $order_by Order by.
     *
     * @return array Returns an array of pets.
     */
    public function search(string $keyword, ?int $limit = null, ?int $offset = null, ?string $order_by = null): array
    {
        $pets = $this->db
            ->select()
            ->from('pets')
            ->group_start()
            ->like('name', $keyword)
            ->or_like('breed', $keyword)
            ->or_like('coat_notes', $keyword)
            ->or_like('behavioural_notes', $keyword)
            ->group_end()
            ->limit($limit)
            ->offset($offset)
            ->get()
            ->result_array();

        foreach ($pets as &$pet) {
            $this->cast($pet);
        }

        return $pets;
    }

    /**
     * Get the query builder interface, configured for use with the pets table.
     *
     * @return CI_DB_query_builder
     */
    public function query(): CI_DB_query_builder
    {
        return $this->db->from('pets');
    }
}
