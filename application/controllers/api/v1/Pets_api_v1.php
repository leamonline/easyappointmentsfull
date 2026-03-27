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
 * Pets API v1 controller.
 *
 * @package Controllers
 */
class Pets_api_v1 extends EA_Controller
{
    /**
     * Pets_api_v1 constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->library('api');
        $this->load->library('webhooks_client');

        $this->api->auth();

        $this->api->model('pets_model');
    }

    /**
     * Get a pet collection.
     */
    public function index(): void
    {
        try {
            $keyword = $this->api->request_keyword();

            $limit = $this->api->request_limit();

            $offset = $this->api->request_offset();

            $order_by = $this->api->request_order_by();

            $fields = $this->api->request_fields();

            $with = $this->api->request_with();

            if (!empty($keyword)) {
                $pets = $this->pets_model->search($keyword, $limit, $offset, $order_by);
            } else {
                $where = [];

                $customer_id = $this->input->get('customer_id');

                if (!empty($customer_id)) {
                    $where['id_users_customer'] = (int) $customer_id;
                }

                $size = $this->input->get('size');

                if (!empty($size)) {
                    $where['size'] = $size;
                }

                $pets = $this->pets_model->get($where ?: null, $limit, $offset, $order_by);
            }

            foreach ($pets as &$pet) {
                $this->pets_model->api_encode($pet);

                if (!empty($fields)) {
                    $this->pets_model->only($pet, $fields);
                }

                if (!empty($with)) {
                    $this->pets_model->load($pet, $with);
                }
            }

            json_response($pets);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    /**
     * Get a single pet.
     *
     * @param int|null $id Pet ID.
     */
    public function show(?int $id = null): void
    {
        try {
            $occurrences = $this->pets_model->get(['id' => $id]);

            if (empty($occurrences)) {
                response('', 404);

                return;
            }

            $fields = $this->api->request_fields();

            $pet = $this->pets_model->find($id);

            $this->pets_model->api_encode($pet);

            if (!empty($fields)) {
                $this->pets_model->only($pet, $fields);
            }

            json_response($pet);
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
            $pet = request();

            $this->pets_model->api_decode($pet);

            if (array_key_exists('id', $pet)) {
                unset($pet['id']);
            }

            $pet_id = $this->pets_model->save($pet);

            $created_pet = $this->pets_model->find($pet_id);

            $this->pets_model->api_encode($created_pet);

            json_response($created_pet, 201);
        } catch (InvalidArgumentException $e) {
            json_response(['success' => false, 'message' => $e->getMessage()], 400);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    /**
     * Update a pet.
     *
     * @param int $id Pet ID.
     */
    public function update(int $id): void
    {
        try {
            $occurrences = $this->pets_model->get(['id' => $id]);

            if (empty($occurrences)) {
                response('', 404);

                return;
            }

            $original_pet = $occurrences[0];

            $pet = request();

            $this->pets_model->api_decode($pet, $original_pet);

            $pet_id = $this->pets_model->save($pet);

            $updated_pet = $this->pets_model->find($pet_id);

            $this->pets_model->api_encode($updated_pet);

            json_response($updated_pet);
        } catch (InvalidArgumentException $e) {
            json_response(['success' => false, 'message' => $e->getMessage()], 400);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }

    /**
     * Delete a pet.
     *
     * @param int $id Pet ID.
     */
    public function destroy(int $id): void
    {
        try {
            $occurrences = $this->pets_model->get(['id' => $id]);

            if (empty($occurrences)) {
                response('', 404);

                return;
            }

            $this->pets_model->delete($id);

            response('', 204);
        } catch (Throwable $e) {
            json_exception($e);
        }
    }
}
