<?php

/**
 * ============================================================================
 * Controller Testing Pattern for CodeIgniter 3 (Smarter Dog)
 * ============================================================================
 *
 * This is the first controller-level test in this project. The pattern works
 * as follows:
 *
 * 1. CI stubs (tests/stubs/ci_stubs.php) provide a no-op EA_Controller, so
 *    the controller can be instantiated without a full CI bootstrap.
 *
 * 2. Global helper functions (session, cannot, redirect, abort, request,
 *    site_url, html_vars) are stubbed BEFORE requiring the controller file.
 *    Each stub reads/writes from $GLOBALS arrays so tests can control inputs
 *    and inspect outputs.
 *
 * 3. abort() and redirect() throw lightweight exceptions so the controller
 *    method stops execution at the right point (matching real CI behaviour
 *    where these functions exit the process).
 *
 * 4. Models and libraries are injected as simple stub objects on the
 *    controller instance. The stub loader's model()/library() calls are
 *    no-ops; the test wires up mock models directly on $controller->model_name.
 *
 * 5. html_vars() captures the data the controller would pass to the view,
 *    letting us assert on exactly what the view receives.
 *
 * To add a new controller test:
 *   - Copy this file's stub definitions (or extract to a shared stubs file).
 *   - Require the controller under test.
 *   - Wire up mock models in setUp().
 *   - Call $controller->method() and assert on captured globals.
 * ============================================================================
 */

// ---------------------------------------------------------------------------
// Global namespace: function stubs and exception types.
// Must be defined here (global namespace) because the controller calls these
// as unqualified global functions.
// ---------------------------------------------------------------------------

namespace {

require_once __DIR__ . '/stubs/MockDb.php';

if (!defined('PRIV_APPOINTMENTS')) {
    define('PRIV_APPOINTMENTS', 'appointments');
}

// Exception types for flow-control functions that normally exit.
class ReportsTestRedirectException extends \Exception {}
class ReportsTestAbortException extends \Exception {
    public int $statusCode;
    public function __construct(int $code, string $message = '') {
        $this->statusCode = $code;
        parent::__construct($message, $code);
    }
}

if (!function_exists('session')) {
    function session(array|string|null $key = null, mixed $default = null): mixed
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $GLOBALS['_test_session'][$k] = $v;
            }
            return null;
        }
        return $GLOBALS['_test_session'][$key] ?? $default;
    }
}

if (!function_exists('cannot')) {
    function cannot(string $action, string $resource, ?int $user_id = null): bool
    {
        return $GLOBALS['_test_cannot_result'] ?? false;
    }
}

if (!function_exists('redirect')) {
    function redirect(string $uri = '', string $method = 'auto', ?int $code = null): void
    {
        $GLOBALS['_test_redirected_to'] = $uri;
        throw new ReportsTestRedirectException($uri);
    }
}

if (!function_exists('abort')) {
    function abort(int $code, string $message = '', array $headers = []): void
    {
        $GLOBALS['_test_aborted_code'] = $code;
        throw new ReportsTestAbortException($code, $message);
    }
}

if (!function_exists('show_error')) {
    function show_error(string $message = '', int $status_code = 500): void
    {
        throw new ReportsTestAbortException($status_code, $message);
    }
}

if (!function_exists('request')) {
    function request(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $GLOBALS['_test_request_params'] ?? [];
        }
        return $GLOBALS['_test_request_params'][$key] ?? $default;
    }
}

if (!function_exists('site_url')) {
    function site_url(string $uri = ''): string
    {
        return $uri;
    }
}

if (!function_exists('html_vars')) {
    function html_vars(array|string|null $key = null, mixed $default = null): mixed
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $GLOBALS['_test_html_vars'][$k] = $v;
            }
            return null;
        }
        if (is_string($key)) {
            return $GLOBALS['_test_html_vars'][$key] ?? $default;
        }
        return $GLOBALS['_test_html_vars'] ?? [];
    }
}

if (!function_exists('config')) {
    function config(array|string|null $key = null, mixed $default = null): mixed
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $GLOBALS['_test_config'][$k] = $v;
            }
            return null;
        }
        return $GLOBALS['_test_config'][$key] ?? $default;
    }
}

// Require the controller under test.
require_once __DIR__ . '/../application/controllers/Reports.php';

/**
 * Testable subclass that skips the constructor (which loads models/libraries
 * via $this->load, unavailable in the test environment). Tests wire up mock
 * models directly on the instance.
 */
class TestableReports extends Reports
{
    public function __construct()
    {
        // Intentionally skip parent::__construct() — models and libraries
        // are injected manually by the test setUp().
    }
}

// Stub classes (global namespace — referenced by test class below).

class ReportsStubLoader
{
    public function model(string $name): void {}
    public function library(string $name): void {}

    public function view(string $name): void
    {
        $GLOBALS['_test_view_loaded'] = $name;
    }
}

class ReportsStubAccounts
{
    public function get_user_display_name(?int $user_id): string
    {
        return 'Test User';
    }
}

class ReportsStubRolesModel
{
    public function get_permissions_by_slug(?string $slug): array
    {
        return [
            'appointments' => ['view' => true, 'add' => true, 'edit' => true, 'delete' => true],
        ];
    }
}

/**
 * Mock appointments model that returns configurable appointment arrays
 * and tracks load() calls to enrich appointments with related data.
 */
class ReportsStubAppointmentsModel
{
    public array $appointments = [];
    public array $serviceMap = [];
    public array $customerMap = [];
    public array $providerMap = [];

    public function get(array $where = [], ?int $limit = null, ?int $offset = null, ?string $order_by = null): array
    {
        return $this->appointments;
    }

    public function load(array &$appointment, array $resources = []): void
    {
        $id = $appointment['id'] ?? 0;

        if (in_array('service', $resources) && isset($this->serviceMap[$id])) {
            $appointment['service'] = $this->serviceMap[$id];
        }
        if (in_array('customer', $resources) && isset($this->customerMap[$id])) {
            $appointment['customer'] = $this->customerMap[$id];
        }
        if (in_array('provider', $resources)) {
            $appointment['provider'] = $this->providerMap[$id] ?? ['id' => 1, 'first_name' => 'Provider'];
        }
    }
}

class ReportsStubPetsModel
{
    public array $pets = [];
    public array $customerPets = [];

    public function find(int $pet_id): array
    {
        if (!isset($this->pets[$pet_id])) {
            throw new \InvalidArgumentException("Pet not found: $pet_id");
        }
        return $this->pets[$pet_id];
    }

    public function get_by_customer(int $customer_id): array
    {
        return $this->customerPets[$customer_id] ?? [];
    }
}

} // end global namespace

// ---------------------------------------------------------------------------
// Tests namespace
// ---------------------------------------------------------------------------

namespace Tests {

use PHPUnit\Framework\TestCase;

class ReportsHandoffTest extends TestCase
{
    private \Reports $controller;
    private \ReportsStubAppointmentsModel $appointmentsModel;
    private \ReportsStubPetsModel $petsModel;

    // -- Sample data --------------------------------------------------------

    private function samplePet(array $overrides = []): array
    {
        return array_merge([
            'id' => 10,
            'id_users_customer' => 100,
            'name' => 'Biscuit',
            'breed' => 'Cockapoo',
            'size' => 'medium',
            'age' => '3',
            'coat_notes' => 'Curly, tangles easily',
            'vaccination_status' => 'up_to_date',
            'behavioural_notes' => 'Nervous around dryers',
        ], $overrides);
    }

    private function sampleCustomer(array $overrides = []): array
    {
        return array_merge([
            'id' => 100,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone_number' => '07700900123',
            'deposit_status' => 'not_required',
        ], $overrides);
    }

    private function sampleService(array $overrides = []): array
    {
        return array_merge([
            'id' => 1,
            'name' => 'Full Groom',
            'duration' => 60,
            'price' => 45.00,
            'is_walkin' => 0,
        ], $overrides);
    }

    private function sampleAppointment(array $overrides = []): array
    {
        return array_merge([
            'id' => 1,
            'start_datetime' => '2026-04-01 09:00:00',
            'end_datetime' => '2026-04-01 10:00:00',
            'status' => 'booked',
            'id_services' => 1,
            'id_users_provider' => 1,
            'id_users_customer' => 100,
            'id_pets' => 10,
            'seats_required' => 1,
            'notes' => '',
            'is_unavailability' => false,
        ], $overrides);
    }

    // -- Setup / Teardown ---------------------------------------------------

    protected function setUp(): void
    {
        parent::setUp();

        // Reset all test globals.
        $GLOBALS['_test_session'] = [
            'user_id' => 1,
            'role_slug' => 'admin',
        ];
        $GLOBALS['_test_cannot_result'] = false;
        $GLOBALS['_test_redirected_to'] = null;
        $GLOBALS['_test_aborted_code'] = null;
        $GLOBALS['_test_request_params'] = [];
        $GLOBALS['_test_html_vars'] = [];
        $GLOBALS['_test_config'] = [];
        $GLOBALS['_test_view_loaded'] = null;

        // Build the CI instance with stubs.
        $ci = new \EA_Controller();
        $ci->load = new \ReportsStubLoader();
        $GLOBALS['_ci_instance'] = $ci;

        // Instantiate testable controller (skips constructor to avoid load->model calls).
        $this->controller = new \TestableReports();

        // Wire up the loader for view() calls at the end of handoff().
        $this->controller->load = new \ReportsStubLoader();

        // Wire up mock models and libraries.
        $this->appointmentsModel = new \ReportsStubAppointmentsModel();
        $this->petsModel = new \ReportsStubPetsModel();

        $this->controller->appointments_model = $this->appointmentsModel;
        $this->controller->pets_model = $this->petsModel;
        $this->controller->accounts = new \ReportsStubAccounts();
        $this->controller->roles_model = new \ReportsStubRolesModel();

        // Wire up default sample data: one grooming appointment.
        $pet = $this->samplePet();
        $customer = $this->sampleCustomer();
        $service = $this->sampleService();
        $appointment = $this->sampleAppointment();

        $this->appointmentsModel->appointments = [$appointment];
        $this->appointmentsModel->serviceMap = [1 => $service];
        $this->appointmentsModel->customerMap = [1 => $customer];
        $this->petsModel->pets = [10 => $pet];
        $this->petsModel->customerPets = [100 => [$pet]];
    }

    protected function tearDown(): void
    {
        $GLOBALS['_test_session'] = [];
        $GLOBALS['_test_cannot_result'] = false;
        $GLOBALS['_test_redirected_to'] = null;
        $GLOBALS['_test_aborted_code'] = null;
        $GLOBALS['_test_request_params'] = [];
        $GLOBALS['_test_html_vars'] = [];
        $GLOBALS['_test_config'] = [];
        $GLOBALS['_test_view_loaded'] = null;
        $GLOBALS['_ci_instance'] = new \EA_Controller();

        parent::tearDown();
    }

    /** Helper: return the captured html_vars array. */
    private function capturedVars(): array
    {
        return $GLOBALS['_test_html_vars'];
    }

    /** Helper: call handoff() and return captured html_vars. */
    private function runHandoff(): array
    {
        $this->controller->handoff();
        return $this->capturedVars();
    }

    // =======================================================================
    // Access control
    // =======================================================================

    public function test_handoff_redirects_to_login_when_no_session(): void
    {
        $GLOBALS['_test_session'] = []; // No user_id.
        $GLOBALS['_test_cannot_result'] = true;

        try {
            $this->controller->handoff();
            $this->fail('Expected ReportsTestRedirectException');
        } catch (\ReportsTestRedirectException $e) {
            $this->assertSame('login', $GLOBALS['_test_redirected_to']);
        }
    }

    public function test_handoff_returns_403_when_user_lacks_permission(): void
    {
        $GLOBALS['_test_session'] = ['user_id' => 1, 'role_slug' => 'customer'];
        $GLOBALS['_test_cannot_result'] = true;

        try {
            $this->controller->handoff();
            $this->fail('Expected ReportsTestAbortException');
        } catch (\ReportsTestAbortException $e) {
            $this->assertSame(403, $GLOBALS['_test_aborted_code']);
        }
    }

    public function test_handoff_renders_successfully_with_permission(): void
    {
        $vars = $this->runHandoff();

        $this->assertSame('pages/reports_handoff', $GLOBALS['_test_view_loaded']);
        $this->assertArrayHasKey('grooming_appointments', $vars);
        $this->assertArrayHasKey('walkin_services', $vars);
        $this->assertSame('Evening Handoff Report', $vars['page_title']);
    }

    // =======================================================================
    // Data completeness — mandatory per Operations doc
    // =======================================================================

    public function test_handoff_includes_start_datetime(): void
    {
        $vars = $this->runHandoff();
        $appt = $vars['grooming_appointments'][0];

        $this->assertArrayHasKey('start_datetime', $appt);
        $this->assertSame('2026-04-01 09:00:00', $appt['start_datetime']);
    }

    public function test_handoff_includes_dog_name_from_pet(): void
    {
        $vars = $this->runHandoff();
        $appt = $vars['grooming_appointments'][0];

        $this->assertArrayHasKey('pet', $appt);
        $this->assertSame('Biscuit', $appt['pet']['name']);
    }

    public function test_handoff_includes_owner_names(): void
    {
        $vars = $this->runHandoff();
        $appt = $vars['grooming_appointments'][0];

        $this->assertSame('Jane', $appt['customer']['first_name']);
        $this->assertSame('Smith', $appt['customer']['last_name']);
    }

    public function test_handoff_includes_service_name(): void
    {
        $vars = $this->runHandoff();
        $appt = $vars['grooming_appointments'][0];

        $this->assertSame('Full Groom', $appt['service']['name']);
    }

    public function test_handoff_includes_breed(): void
    {
        $vars = $this->runHandoff();
        $appt = $vars['grooming_appointments'][0];

        $this->assertSame('Cockapoo', $appt['pet']['breed']);
    }

    public function test_handoff_includes_size_with_colour_mapping(): void
    {
        // The controller passes pet size; the view maps small->green, medium->blue, large->orange.
        $vars = $this->runHandoff();
        $appt = $vars['grooming_appointments'][0];

        $this->assertSame('medium', $appt['pet']['size']);

        // Verify the view's colour mapping is consistent (read from view source).
        $viewPath = __DIR__ . '/../application/views/pages/reports_handoff.php';
        $viewSource = file_get_contents($viewPath);
        $this->assertStringContainsString("'small' => '#4CAF50'", $viewSource, 'small should map to green');
        $this->assertStringContainsString("'medium' => '#2196F3'", $viewSource, 'medium should map to blue');
        $this->assertStringContainsString("'large' => '#FF9800'", $viewSource, 'large should map to orange');
    }

    public function test_handoff_includes_seats_required(): void
    {
        $vars = $this->runHandoff();
        $appt = $vars['grooming_appointments'][0];

        $this->assertArrayHasKey('seats_required', $appt);
        $this->assertSame(1, $appt['seats_required']);
    }

    public function test_handoff_includes_behavioural_notes(): void
    {
        $vars = $this->runHandoff();
        $appt = $vars['grooming_appointments'][0];

        $this->assertArrayHasKey('behavioural_notes', $appt['pet']);
        $this->assertSame('Nervous around dryers', $appt['pet']['behavioural_notes']);
    }

    public function test_handoff_includes_appointment_notes_for_coat_matting(): void
    {
        // Coat/matting notes come from appointment notes and/or pet coat_notes.
        $this->appointmentsModel->appointments = [
            $this->sampleAppointment(['notes' => 'Heavy matting on legs']),
        ];

        $vars = $this->runHandoff();
        $appt = $vars['grooming_appointments'][0];

        $this->assertSame('Heavy matting on legs', $appt['notes']);
        $this->assertSame('Curly, tangles easily', $appt['pet']['coat_notes']);
    }

    public function test_handoff_includes_status_counts(): void
    {
        $this->appointmentsModel->appointments = [
            $this->sampleAppointment(['id' => 1, 'status' => 'completed']),
            $this->sampleAppointment(['id' => 2, 'status' => 'cancelled', 'id_pets' => 10]),
            $this->sampleAppointment(['id' => 3, 'status' => 'no_show', 'id_pets' => 10]),
            $this->sampleAppointment(['id' => 4, 'status' => 'booked', 'id_pets' => 10]),
        ];
        $service = $this->sampleService();
        $customer = $this->sampleCustomer();
        $this->appointmentsModel->serviceMap = [1 => $service, 2 => $service, 3 => $service, 4 => $service];
        $this->appointmentsModel->customerMap = [1 => $customer, 2 => $customer, 3 => $customer, 4 => $customer];

        $vars = $this->runHandoff();

        $this->assertSame(1, $vars['completed_count']);
        $this->assertSame(1, $vars['cancelled_count']);
        $this->assertSame(1, $vars['no_show_count']);
    }

    // =======================================================================
    // Deposit status & new customer flag
    // =======================================================================

    public function test_handoff_includes_customer_deposit_status(): void
    {
        $this->appointmentsModel->customerMap = [
            1 => $this->sampleCustomer(['deposit_status' => 'awaiting']),
        ];

        $vars = $this->runHandoff();
        $appt = $vars['grooming_appointments'][0];

        $this->assertArrayHasKey('deposit_status', $appt['customer']);
        $this->assertSame('awaiting', $appt['customer']['deposit_status']);
    }

    public function test_handoff_new_customer_flag_when_deposit_awaiting(): void
    {
        // Per Operations doc: deposit_status != 'not_required' indicates a new customer.
        $this->appointmentsModel->customerMap = [
            1 => $this->sampleCustomer(['deposit_status' => 'awaiting']),
        ];

        $vars = $this->runHandoff();
        $customer = $vars['grooming_appointments'][0]['customer'];

        $this->assertNotSame('not_required', $customer['deposit_status'],
            'A customer with deposit_status=awaiting should be flagged as new');
    }

    public function test_handoff_not_new_customer_when_deposit_not_required(): void
    {
        $this->appointmentsModel->customerMap = [
            1 => $this->sampleCustomer(['deposit_status' => 'not_required']),
        ];

        $vars = $this->runHandoff();
        $customer = $vars['grooming_appointments'][0]['customer'];

        $this->assertSame('not_required', $customer['deposit_status'],
            'A customer with deposit_status=not_required is NOT a new customer');
    }

    // =======================================================================
    // Date parameter handling
    // =======================================================================

    public function test_handoff_defaults_to_today_when_no_date_param(): void
    {
        $GLOBALS['_test_request_params'] = []; // No date param.

        $vars = $this->runHandoff();

        $this->assertSame(date('Y-m-d'), $vars['report_date']);
    }

    public function test_handoff_accepts_custom_date_param(): void
    {
        $GLOBALS['_test_request_params'] = ['date' => '2026-04-01'];

        $vars = $this->runHandoff();

        $this->assertSame('2026-04-01', $vars['report_date']);
        $this->assertStringContainsString('April', $vars['report_date_formatted']);
    }

    public function test_handoff_grooming_sorted_by_start_datetime_asc(): void
    {
        // Provide appointments out of order.
        $this->appointmentsModel->appointments = [
            $this->sampleAppointment(['id' => 2, 'start_datetime' => '2026-04-01 14:00:00']),
            $this->sampleAppointment(['id' => 1, 'start_datetime' => '2026-04-01 09:00:00']),
            $this->sampleAppointment(['id' => 3, 'start_datetime' => '2026-04-01 11:30:00']),
        ];
        $service = $this->sampleService();
        $customer = $this->sampleCustomer();
        $this->appointmentsModel->serviceMap = [1 => $service, 2 => $service, 3 => $service];
        $this->appointmentsModel->customerMap = [1 => $customer, 2 => $customer, 3 => $customer];

        $vars = $this->runHandoff();
        $times = array_map(fn($a) => $a['start_datetime'], $vars['grooming_appointments']);

        $this->assertSame([
            '2026-04-01 09:00:00',
            '2026-04-01 11:30:00',
            '2026-04-01 14:00:00',
        ], $times);
    }

    public function test_handoff_walkin_services_sorted_by_start_datetime_asc(): void
    {
        $walkinService = $this->sampleService(['id' => 5, 'name' => 'Nail Clip', 'is_walkin' => 1, 'price' => 10.00]);

        $this->appointmentsModel->appointments = [
            $this->sampleAppointment(['id' => 2, 'start_datetime' => '2026-04-01 15:00:00', 'id_services' => 5]),
            $this->sampleAppointment(['id' => 1, 'start_datetime' => '2026-04-01 10:00:00', 'id_services' => 5]),
        ];
        $customer = $this->sampleCustomer();
        $this->appointmentsModel->serviceMap = [1 => $walkinService, 2 => $walkinService];
        $this->appointmentsModel->customerMap = [1 => $customer, 2 => $customer];

        $vars = $this->runHandoff();
        $times = array_map(fn($a) => $a['start_datetime'], $vars['walkin_services']);

        $this->assertSame([
            '2026-04-01 10:00:00',
            '2026-04-01 15:00:00',
        ], $times);
    }

    // =======================================================================
    // Walk-in exclusion
    // =======================================================================

    public function test_walkin_services_separated_from_grooming(): void
    {
        $groomingService = $this->sampleService(['id' => 1, 'is_walkin' => 0]);
        $walkinService = $this->sampleService(['id' => 5, 'name' => 'Nail Clip', 'is_walkin' => 1, 'price' => 10.00]);

        $this->appointmentsModel->appointments = [
            $this->sampleAppointment(['id' => 1, 'id_services' => 1]),
            $this->sampleAppointment(['id' => 2, 'id_services' => 5, 'start_datetime' => '2026-04-01 11:00:00']),
        ];
        $customer = $this->sampleCustomer();
        $this->appointmentsModel->serviceMap = [1 => $groomingService, 2 => $walkinService];
        $this->appointmentsModel->customerMap = [1 => $customer, 2 => $customer];

        $vars = $this->runHandoff();

        $this->assertCount(1, $vars['grooming_appointments']);
        $this->assertCount(1, $vars['walkin_services']);
        $this->assertSame(1, $vars['grooming_appointments'][0]['id']);
        $this->assertSame(2, $vars['walkin_services'][0]['id']);
    }

    public function test_walkins_not_counted_in_total_dogs(): void
    {
        $groomingService = $this->sampleService(['id' => 1, 'is_walkin' => 0]);
        $walkinService = $this->sampleService(['id' => 5, 'name' => 'Nail Clip', 'is_walkin' => 1, 'price' => 10.00]);

        $this->appointmentsModel->appointments = [
            $this->sampleAppointment(['id' => 1, 'id_services' => 1]),
            $this->sampleAppointment(['id' => 2, 'id_services' => 5, 'start_datetime' => '2026-04-01 11:00:00']),
            $this->sampleAppointment(['id' => 3, 'id_services' => 5, 'start_datetime' => '2026-04-01 12:00:00']),
        ];
        $customer = $this->sampleCustomer();
        $this->appointmentsModel->serviceMap = [1 => $groomingService, 2 => $walkinService, 3 => $walkinService];
        $this->appointmentsModel->customerMap = [1 => $customer, 2 => $customer, 3 => $customer];

        $vars = $this->runHandoff();

        // Only the one grooming appointment counts.
        $this->assertSame(1, $vars['total_dogs']);
    }

    public function test_walkin_revenue_calculated(): void
    {
        $walkinService = $this->sampleService(['id' => 5, 'name' => 'Nail Clip', 'is_walkin' => 1, 'price' => 12.50]);

        $this->appointmentsModel->appointments = [
            $this->sampleAppointment(['id' => 1, 'id_services' => 5]),
            $this->sampleAppointment(['id' => 2, 'id_services' => 5, 'start_datetime' => '2026-04-01 11:00:00']),
        ];
        $customer = $this->sampleCustomer();
        $this->appointmentsModel->serviceMap = [1 => $walkinService, 2 => $walkinService];
        $this->appointmentsModel->customerMap = [1 => $customer, 2 => $customer];

        $vars = $this->runHandoff();

        $this->assertSame(25.0, $vars['walkin_revenue']);
    }

    // =======================================================================
    // Large dog visual flag
    // =======================================================================

    public function test_large_dog_size_passed_to_view(): void
    {
        $largePet = $this->samplePet(['id' => 20, 'name' => 'Thor', 'size' => 'large', 'breed' => 'German Shepherd']);
        $this->petsModel->pets[20] = $largePet;
        $this->petsModel->customerPets[100][] = $largePet;

        $this->appointmentsModel->appointments = [
            $this->sampleAppointment(['id' => 1, 'id_pets' => 20]),
        ];

        $vars = $this->runHandoff();
        $appt = $vars['grooming_appointments'][0];

        $this->assertSame('large', $appt['pet']['size'],
            'Large dog size must be passed so the view can render the visual flag');
    }

    // =======================================================================
    // Handoff report gaps — flagged per Operations doc review
    // =======================================================================

    /**
     * GAP: The view does NOT display deposit_status from the customer record.
     *
     * The Operations doc requires deposit status (not_required / awaiting / received)
     * to be visible on the handoff report so the team knows which customers still
     * owe a deposit.
     *
     * TODO: Add a deposit status column or badge to the grooming appointments table
     *       in application/views/pages/reports_handoff.php.
     */
    public function test_gap_deposit_status_not_displayed_in_view(): void
    {
        $viewPath = __DIR__ . '/../application/views/pages/reports_handoff.php';
        $viewSource = file_get_contents($viewPath);

        // The view currently does not reference deposit_status anywhere.
        $this->assertStringNotContainsString('deposit_status', $viewSource,
            'GAP CONFIRMED: deposit_status is not displayed in the handoff view. '
            . 'It should be added per the Operations doc requirements.');
    }

    /**
     * GAP: The view does NOT display the customer's phone_number.
     *
     * The Operations doc says the handoff should include quick-contact info.
     * The customer record has phone_number, but the handoff view only shows
     * the customer name.
     *
     * TODO: Add phone_number to the Owner column or as a tooltip/secondary line
     *       in application/views/pages/reports_handoff.php.
     */
    public function test_gap_phone_number_not_displayed_in_view(): void
    {
        $viewPath = __DIR__ . '/../application/views/pages/reports_handoff.php';
        $viewSource = file_get_contents($viewPath);

        $this->assertStringNotContainsString('phone_number', $viewSource,
            'GAP CONFIRMED: phone_number is not displayed in the handoff view. '
            . 'It should be added for quick customer contact.');
    }

    /**
     * GAP: There is no dedicated "flags" summary section in the view.
     *
     * Currently, new-customer indicators, large-dog badges, and behavioural
     * notes are displayed inline within the appointments table. The Operations
     * doc suggests "anything unusual or that needs Leam's attention" should
     * be easy to spot at a glance.
     *
     * TODO: Consider adding a summary/flags section at the top of the handoff
     *       report (below the stat cards, above the grooming table) that
     *       highlights:
     *       - New customers (deposit_status != 'not_required')
     *       - Large dogs (size === 'large')
     *       - Dogs with behavioural notes
     *       This would give a quick "heads-up" view before reading the full table.
     */
    public function test_gap_no_summary_flags_section_in_view(): void
    {
        $viewPath = __DIR__ . '/../application/views/pages/reports_handoff.php';
        $viewSource = file_get_contents($viewPath);

        // Look for any kind of dedicated flags/summary section.
        $hasFlagsSection = (
            stripos($viewSource, 'flags-section') !== false
            || stripos($viewSource, 'heads-up') !== false
            || stripos($viewSource, 'attention-summary') !== false
            || stripos($viewSource, 'new-customer-flag') !== false
        );

        $this->assertFalse($hasFlagsSection,
            'GAP CONFIRMED: No dedicated flags/summary section exists in the handoff view. '
            . 'Consider adding one to highlight new customers, large dogs, and behavioural notes.');
    }
}

} // end Tests namespace
