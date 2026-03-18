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
 * Booking auth controller.
 *
 * Handles customer authentication in the booking flow.
 *
 * @package Controllers
 *
 * @property Customers_model $customers_model
 * @property Customer_auth_model $customer_auth_model
 * @property Pets_model $pets_model
 */
class Booking_auth extends EA_Controller
{
    /**
     * Booking_auth constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('customers_model');
        $this->load->model('customer_auth_model');
        $this->load->model('pets_model');
    }

    /**
     * Authenticate a customer by email and password.
     */
    public function login(): void
    {
        try {
            $email = request('email');
            $password = request('password');

            if (empty($email) || empty($password)) {
                throw new InvalidArgumentException('Email and password are required.');
            }

            $customers = $this->customers_model->get(['email' => $email]);

            if (empty($customers)) {
                throw new InvalidArgumentException('No account found with that email address.');
            }

            $customer = $customers[0];

            if (!$this->customer_auth_model->verify_password($customer['id'], $password)) {
                throw new InvalidArgumentException('Invalid email or password.');
            }

            $this->session->set_userdata('booking_customer_id', $customer['id']);

            $pets = $this->pets_model->get_by_customer($customer['id']);

            json_response([
                'success' => true,
                'customer' => $customer,
                'pets' => $pets,
            ]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    /**
     * Register a new customer account for booking.
     */
    public function register(): void
    {
        try {
            $first_name = request('first_name');
            $last_name = request('last_name');
            $email = request('email');
            $phone_number = request('phone_number');
            $password = request('password');

            if (empty($email) || empty($password)) {
                throw new InvalidArgumentException('Email and password are required.');
            }

            $existing_customers = $this->customers_model->get(['email' => $email]);

            if (!empty($existing_customers)) {
                $existing_customer = $existing_customers[0];
                $existing_auth = $this->customer_auth_model->find_by_customer_id($existing_customer['id']);

                if ($existing_auth) {
                    throw new InvalidArgumentException('An account with this email already exists. Please log in.');
                }

                // Customer exists but has no auth record — create auth for them.
                $this->customer_auth_model->set_password($existing_customer['id'], $password);

                $this->session->set_userdata('booking_customer_id', $existing_customer['id']);

                json_response([
                    'success' => true,
                    'customer' => $existing_customer,
                    'pets' => $this->pets_model->get_by_customer($existing_customer['id']),
                ]);

                return;
            }

            // Create new customer.
            $customer_data = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone_number' => $phone_number,
            ];

            $customer_id = $this->customers_model->save($customer_data);

            $this->customer_auth_model->set_password($customer_id, $password);

            $customer = $this->customers_model->find($customer_id);

            $this->session->set_userdata('booking_customer_id', $customer['id']);

            json_response([
                'success' => true,
                'customer' => $customer,
                'pets' => [],
            ]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    /**
     * Check if the customer has an active booking session.
     */
    public function check_session(): void
    {
        try {
            $customer_id = session('booking_customer_id');

            if (empty($customer_id)) {
                json_response([
                    'success' => false,
                    'customer' => null,
                ]);
                return;
            }

            $customer = $this->customers_model->find($customer_id);
            $pets = $this->pets_model->get_by_customer($customer_id);

            json_response([
                'success' => true,
                'customer' => $customer,
                'pets' => $pets,
            ]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    /**
     * Log out the customer from the booking session.
     */
    public function logout(): void
    {
        try {
            $this->session->unset_userdata('booking_customer_id');

            json_response([
                'success' => true,
            ]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    /**
     * Add a pet for the currently authenticated booking customer.
     */
    public function add_pet(): void
    {
        try {
            $customer_id = session('booking_customer_id');

            if (empty($customer_id)) {
                throw new RuntimeException('Customer is not authenticated.');
            }

            $name = request('name');
            $breed = request('breed');
            $date_of_birth = request('date_of_birth');
            $size = request('size');

            if (empty($name)) {
                throw new InvalidArgumentException('Pet name is required.');
            }

            $pet_data = [
                'id_users_customer' => $customer_id,
                'name' => $name,
                'breed' => $breed ?? '',
                'date_of_birth' => !empty($date_of_birth) ? $date_of_birth : null,
                'size' => $size ?? 'small',
            ];

            $pet_id = $this->pets_model->save($pet_data);

            $pet = $this->pets_model->find($pet_id);

            json_response([
                'success' => true,
                'pet' => $pet,
            ]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }
}
