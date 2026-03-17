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
 * Customer auth model.
 *
 * Handles all the database operations of the customer authentication resource.
 *
 * @package Models
 */
class Customer_auth_model extends EA_Model
{
    /**
     * @var array
     */
    protected array $casts = [
        'id' => 'integer',
        'id_users_customer' => 'integer',
        'email_verified' => 'integer',
    ];

    /**
     * Save (insert or update) a customer auth record.
     *
     * @param array $auth Associative array with the customer auth data.
     *
     * @return int Returns the customer auth ID.
     *
     * @throws InvalidArgumentException
     */
    public function save(array $auth): int
    {
        $this->validate($auth);

        if (empty($auth['id'])) {
            return $this->insert($auth);
        } else {
            return $this->update($auth);
        }
    }

    /**
     * Validate the customer auth data.
     *
     * @param array $auth Associative array with the customer auth data.
     *
     * @throws InvalidArgumentException
     */
    public function validate(array $auth): void
    {
        if (!empty($auth['id'])) {
            $count = $this->db->get_where('customer_auth', ['id' => $auth['id']])->num_rows();

            if (!$count) {
                throw new InvalidArgumentException(
                    'The provided customer auth ID does not exist in the database: ' . $auth['id'],
                );
            }
        }

        if (empty($auth['id_users_customer'])) {
            throw new InvalidArgumentException('The customer auth record must be assigned to a customer.');
        }
    }

    /**
     * Insert a new customer auth record into the database.
     *
     * @param array $auth Associative array with the customer auth data.
     *
     * @return int Returns the customer auth ID.
     *
     * @throws RuntimeException
     */
    protected function insert(array $auth): int
    {
        $auth['create_datetime'] = date('Y-m-d H:i:s');
        $auth['update_datetime'] = date('Y-m-d H:i:s');

        if (!$this->db->insert('customer_auth', $auth)) {
            throw new RuntimeException('Could not insert customer auth record.');
        }

        return $this->db->insert_id();
    }

    /**
     * Update an existing customer auth record.
     *
     * @param array $auth Associative array with the customer auth data.
     *
     * @return int Returns the customer auth ID.
     *
     * @throws RuntimeException
     */
    protected function update(array $auth): int
    {
        $auth['update_datetime'] = date('Y-m-d H:i:s');

        if (!$this->db->update('customer_auth', $auth, ['id' => $auth['id']])) {
            throw new RuntimeException('Could not update customer auth record.');
        }

        return $auth['id'];
    }

    /**
     * Find a customer auth record by customer ID.
     *
     * @param int $customer_id Customer user ID.
     *
     * @return array|null Returns an array with the auth data or null if not found.
     */
    public function find_by_customer_id(int $customer_id): ?array
    {
        $auth = $this->db->get_where('customer_auth', ['id_users_customer' => $customer_id])->row_array();

        if (!$auth) {
            return null;
        }

        $this->cast($auth);

        return $auth;
    }

    /**
     * Verify a customer's password.
     *
     * @param int $customer_id Customer user ID.
     * @param string $password Plain text password to verify.
     *
     * @return bool Returns true if the password matches, false otherwise.
     */
    public function verify_password(int $customer_id, string $password): bool
    {
        $auth = $this->find_by_customer_id($customer_id);

        if (!$auth || empty($auth['password_hash'])) {
            return false;
        }

        return password_verify($password, $auth['password_hash']);
    }

    /**
     * Set (hash and store) a customer's password.
     *
     * @param int $customer_id Customer user ID.
     * @param string $password Plain text password to hash and store.
     *
     * @throws RuntimeException
     */
    public function set_password(int $customer_id, string $password): void
    {
        $auth = $this->find_by_customer_id($customer_id);

        $data = [
            'id_users_customer' => $customer_id,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        ];

        if ($auth) {
            $data['id'] = $auth['id'];
            $this->update($data);
        } else {
            $this->insert($data);
        }
    }

    /**
     * Find a customer auth record by email address.
     *
     * Joins with the users table to look up by email.
     *
     * @param string $email Customer email address.
     *
     * @return array|null Returns an array with the auth data or null if not found.
     */
    public function find_by_email(string $email): ?array
    {
        $auth = $this->db
            ->select('customer_auth.*')
            ->from('customer_auth')
            ->join('users', 'users.id = customer_auth.id_users_customer')
            ->where('users.email', $email)
            ->get()
            ->row_array();

        if (!$auth) {
            return null;
        }

        $this->cast($auth);

        return $auth;
    }
}
