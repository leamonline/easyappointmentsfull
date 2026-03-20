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
 * Reports controller.
 *
 * Handles salon reports such as the evening handoff.
 *
 * @package Controllers
 */
class Reports extends EA_Controller
{
    /**
     * Reports constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('appointments_model');
        $this->load->model('customers_model');
        $this->load->model('services_model');
        $this->load->model('providers_model');
        $this->load->model('pets_model');
        $this->load->model('roles_model');

        $this->load->library('accounts');
        $this->load->library('timezones');
    }

    /**
     * Render the evening handoff report.
     */
    public function handoff(): void
    {
        session(['dest_url' => site_url('reports/handoff')]);

        $user_id = session('user_id');

        if (cannot('view', PRIV_APPOINTMENTS)) {
            if ($user_id) {
                abort(403, 'Forbidden');
            }

            redirect('login');

            return;
        }

        $date = request('date') ?: date('Y-m-d');

        $role_slug = session('role_slug');

        $appointments = $this->appointments_model->get([
            'DATE(start_datetime)' => $date,
        ]);

        $grooming_appointments = [];
        $walkin_services = [];
        $total_dogs = 0;
        $completed_count = 0;
        $cancelled_count = 0;
        $no_show_count = 0;

        foreach ($appointments as &$appointment) {
            $this->appointments_model->load($appointment, ['service', 'provider', 'customer']);

            // Load pet data if present
            if (!empty($appointment['id_pets'])) {
                try {
                    $appointment['pet'] = $this->pets_model->find($appointment['id_pets']);
                } catch (Throwable $e) {
                    $appointment['pet'] = null;
                }
            }

            // Load customer pets list
            if (!empty($appointment['id_users_customer'])) {
                $appointment['customer_pets'] = $this->pets_model->get_by_customer(
                    $appointment['id_users_customer'],
                );
            }

            $is_walkin = !empty($appointment['service']['is_walkin']);

            if ($is_walkin) {
                $walkin_services[] = $appointment;
            } else {
                $grooming_appointments[] = $appointment;
                $total_dogs++;

                $status = strtolower($appointment['status'] ?? '');
                if ($status === 'completed') {
                    $completed_count++;
                } elseif ($status === 'cancelled') {
                    $cancelled_count++;
                } elseif ($status === 'no_show') {
                    $no_show_count++;
                }
            }
        }

        // Sort by start time
        usort($grooming_appointments, function ($a, $b) {
            return strtotime($a['start_datetime']) - strtotime($b['start_datetime']);
        });

        usort($walkin_services, function ($a, $b) {
            return strtotime($a['start_datetime']) - strtotime($b['start_datetime']);
        });

        // Calculate walk-in revenue
        $walkin_revenue = 0;
        foreach ($walkin_services as $walkin) {
            $walkin_revenue += (float) ($walkin['service']['price'] ?? 0);
        }

        html_vars([
            'page_title' => 'Evening Handoff Report',
            'active_menu' => PRIV_APPOINTMENTS,
            'user_display_name' => $this->accounts->get_user_display_name($user_id),
            'privileges' => $this->roles_model->get_permissions_by_slug($role_slug),
            'report_date' => $date,
            'report_date_formatted' => date('l, j F Y', strtotime($date)),
            'grooming_appointments' => $grooming_appointments,
            'walkin_services' => $walkin_services,
            'total_dogs' => $total_dogs,
            'completed_count' => $completed_count,
            'cancelled_count' => $cancelled_count,
            'no_show_count' => $no_show_count,
            'walkin_revenue' => $walkin_revenue,
        ]);

        $this->load->view('pages/reports_handoff');
    }
}
