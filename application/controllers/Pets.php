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
 * Pets controller.
 *
 * Handles pet CRUD operations via AJAX.
 *
 * @package Controllers
 */
class Pets extends EA_Controller
{
    /**
     * Pets constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('pets_model');
        $this->load->model('customers_model');

        $this->load->library('permissions');
    }

    /**
     * Get pets for a specific customer.
     */
    public function get_by_customer(): void
    {
        try {
            if (cannot('view', PRIV_CUSTOMERS)) {
                abort(403, 'Forbidden');
            }

            $customer_id = (int) request('customer_id');

            $pets = $this->pets_model->get_by_customer($customer_id);

            json_response($pets);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    /**
     * Store a new pet.
     */
    public function store(): void
    {
        try {
            if (cannot('add', PRIV_CUSTOMERS)) {
                abort(403, 'Forbidden');
            }

            $pet = request('pet');

            $pet_id = $this->pets_model->save($pet);

            $pet = $this->pets_model->find($pet_id);

            json_response([
                'success' => true,
                'id' => $pet_id,
                'pet' => $pet,
            ]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    /**
     * Update an existing pet.
     */
    public function update(): void
    {
        try {
            if (cannot('edit', PRIV_CUSTOMERS)) {
                abort(403, 'Forbidden');
            }

            $pet = request('pet');

            $pet_id = $this->pets_model->save($pet);

            $pet = $this->pets_model->find($pet_id);

            json_response([
                'success' => true,
                'id' => $pet_id,
                'pet' => $pet,
            ]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    /**
     * Remove a pet.
     */
    public function destroy(): void
    {
        try {
            if (cannot('delete', PRIV_CUSTOMERS)) {
                abort(403, 'Forbidden');
            }

            $pet_id = (int) request('pet_id');

            $this->pets_model->delete($pet_id);

            json_response([
                'success' => true,
            ]);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }
}
