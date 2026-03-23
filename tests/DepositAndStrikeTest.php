<?php

/**
 * ============================================================================
 * Deposit Status & Strike Count Tests
 * ============================================================================
 *
 * Tests the deposit_status and strike_count fields on the customer (users)
 * table. These fields were added in migration 063.
 *
 * The DB schema and model mappings are in place, but the business logic to
 * transition deposit_status and increment strike_count is NOT YET IMPLEMENTED.
 * Tests for unimplemented behaviour are marked @group unimplemented and use
 * markTestSkipped() with a description of the expected behaviour.
 *
 * Strike policy (confirmed by owner):
 *   - Cancellation 24h+ before appointment: 0 strikes
 *   - Cancellation under 24h: +1 strike
 *   - No-show: +1 strike
 *   - 3 strikes within 12 months → customer must pay £10 deposit per booking
 * ============================================================================
 */

require_once __DIR__ . '/stubs/ci_stubs.php';
require_once __DIR__ . '/stubs/MockDb.php';
require_once __DIR__ . '/../application/models/Customers_model.php';

use PHPUnit\Framework\TestCase;

class DepositAndStrikeTest extends TestCase
{
    private \Customers_model $model;

    private string $migrationPath;

    // -- Setup / Teardown ---------------------------------------------------

    protected function setUp(): void
    {
        parent::setUp();

        $GLOBALS['_test_settings'] = [
            'require_first_name' => '1',
            'require_last_name' => '1',
            'require_email' => '1',
            'require_phone_number' => '0',
            'require_address' => '0',
            'require_city' => '0',
            'require_zip_code' => '0',
        ];

        $ci = new \EA_Controller();
        $ci->load = new class {
            public function model(string $name): void {}
            public function library(string $name): void {}
        };
        $ci->db = $this->createPassingDb();
        $GLOBALS['_ci_instance'] = $ci;

        $this->model = new \Customers_model();
        $this->migrationPath = __DIR__ . '/../application/migrations/063_add_deposit_and_strike_columns_to_users_table.php';
    }

    protected function tearDown(): void
    {
        $GLOBALS['_test_settings'] = [];
        $GLOBALS['_ci_instance'] = new \EA_Controller();
        parent::tearDown();
    }

    // -- Helpers ------------------------------------------------------------

    private function sampleCustomer(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone_number' => '07700900123',
            'address' => '1 High St',
            'city' => 'London',
            'zip_code' => 'SW1A 1AA',
            'deposit_status' => 'not_required',
            'strike_count' => 0,
        ], $overrides);
    }

    /**
     * Create a MockDb that passes all standard checks the model needs:
     * - get_where('roles', ...) returns a role row (for get_customer_role_id)
     * - get_where('users', ...) returns 0 rows (customer doesn't exist yet)
     * - insert succeeds and returns id 1
     */
    private function createPassingDb(): MockDb
    {
        // For most tests we need a more flexible mock — use PHPUnit's mock builder.
        // But for simple cases, the basic MockDb works.
        $db = new MockDb();
        $db->setRowResult(['id' => 4, 'slug' => 'customer']);
        $db->setNumRows(0);
        $db->setInsertId(1);
        return $db;
    }

    /**
     * Build a capturing DB stub that records the data passed to insert(),
     * while handling the multi-query flow of Customers_model::save().
     */
    private function createInsertCapturingDb(array &$capturedData): object
    {
        return new class($capturedData) extends MockDb {
            private int $rowCallCount = 0;
            private int $numRowsCallCount = 0;
            private array $captured;

            public function __construct(array &$captured)
            {
                $this->captured = &$captured;
            }

            public function row_array(): ?array
            {
                $this->rowCallCount++;
                // First call: role lookup (for get_customer_role_id)
                if ($this->rowCallCount === 1) {
                    return ['id' => 4, 'slug' => 'customer'];
                }
                // Second call: exists() check — no existing customer
                return null;
            }

            public function num_rows(): int
            {
                $this->numRowsCallCount++;
                // 1st: validate() ID check or email uniqueness — return 0
                // All calls should return 0 (no existing records)
                return 0;
            }

            public function insert(string $table, array $data): bool
            {
                $this->captured = $data;
                return true;
            }

            public function insert_id(): int
            {
                return 1;
            }
        };
    }

    /**
     * Build a mock DB for find() — returns a single customer row.
     */
    private function createFindDb(array $customerRow): MockDb
    {
        $db = new MockDb();
        $db->setRowResult($customerRow);
        $db->setNumRows(1);
        return $db;
    }

    // =======================================================================
    // Schema & Model Foundation (PASSING)
    // =======================================================================

    public function test_migration_defaults_deposit_status_to_not_required(): void
    {
        $source = file_get_contents($this->migrationPath);

        $this->assertStringContainsString("'deposit_status'", $source);
        $this->assertStringContainsString("'default' => 'not_required'", $source,
            'Migration must default deposit_status to not_required');
    }

    public function test_migration_defaults_strike_count_to_zero(): void
    {
        $source = file_get_contents($this->migrationPath);

        $this->assertStringContainsString("'strike_count'", $source);
        $this->assertStringContainsString("'default' => 0", $source,
            'Migration must default strike_count to 0');
    }

    public function test_customers_model_casts_strike_count_to_integer(): void
    {
        $db = $this->createFindDb([
            'id' => '1',
            'strike_count' => '3',
            'deposit_status' => 'awaiting',
            'first_name' => 'Jane',
        ]);

        $ci = $GLOBALS['_ci_instance'];
        $ci->db = $db;

        $customer = $this->model->find(1);

        $this->assertIsInt($customer['strike_count'], 'strike_count must be cast to integer');
        $this->assertSame(3, $customer['strike_count']);
    }

    public function test_customers_model_casts_id_to_integer(): void
    {
        $db = $this->createFindDb([
            'id' => '42',
            'strike_count' => '0',
            'deposit_status' => 'not_required',
        ]);

        $ci = $GLOBALS['_ci_instance'];
        $ci->db = $db;

        $customer = $this->model->find(42);

        $this->assertIsInt($customer['id']);
        $this->assertSame(42, $customer['id']);
    }

    public function test_deposit_status_persisted_via_save(): void
    {
        $capturedData = [];
        $ci = $GLOBALS['_ci_instance'];
        $ci->db = $this->createInsertCapturingDb($capturedData);

        $customer = $this->sampleCustomer(['deposit_status' => 'awaiting']);
        $this->model->save($customer);

        $this->assertArrayHasKey('deposit_status', $capturedData,
            'deposit_status must be included in the DB insert');
        $this->assertSame('awaiting', $capturedData['deposit_status']);
    }

    public function test_strike_count_persisted_via_save(): void
    {
        $capturedData = [];
        $ci = $GLOBALS['_ci_instance'];
        $ci->db = $this->createInsertCapturingDb($capturedData);

        $customer = $this->sampleCustomer(['strike_count' => 2]);
        $this->model->save($customer);

        $this->assertArrayHasKey('strike_count', $capturedData,
            'strike_count must be included in the DB insert');
        $this->assertSame(2, $capturedData['strike_count']);
    }

    // =======================================================================
    // API Encoding Gaps (PASSING — documents missing fields)
    // =======================================================================

    public function test_api_encode_excludes_deposit_status_and_strike_count(): void
    {
        $customer = [
            'id' => 1,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone_number' => '07700900123',
            'address' => '1 High St',
            'city' => 'London',
            'zip_code' => 'SW1A 1AA',
            'notes' => '',
            'timezone' => 'Europe/London',
            'language' => 'english',
            'custom_field_1' => '',
            'custom_field_2' => '',
            'custom_field_3' => '',
            'custom_field_4' => '',
            'custom_field_5' => '',
            'ldap_dn' => null,
            'deposit_status' => 'awaiting',
            'strike_count' => 2,
        ];

        $this->model->api_encode($customer);

        $this->assertArrayNotHasKey('depositStatus', $customer,
            'GAP: api_encode() does not include depositStatus — it should be added');
        $this->assertArrayNotHasKey('strikeCount', $customer,
            'GAP: api_encode() does not include strikeCount — it should be added');
    }

    public function test_api_decode_excludes_deposit_status_and_strike_count(): void
    {
        $apiPayload = [
            'id' => 1,
            'firstName' => 'Jane',
            'lastName' => 'Smith',
            'email' => 'jane@example.com',
            'depositStatus' => 'awaiting',
            'strikeCount' => 2,
        ];

        $this->model->api_decode($apiPayload);

        $this->assertArrayNotHasKey('deposit_status', $apiPayload,
            'GAP: api_decode() does not map depositStatus — it should be added');
        $this->assertArrayNotHasKey('strike_count', $apiPayload,
            'GAP: api_decode() does not map strikeCount — it should be added');
    }

    // =======================================================================
    // Deposit Status Lifecycle (NOT YET IMPLEMENTED)
    // Per 04-Policies.docx and 02-Services-and-Pricing.docx
    // =======================================================================

    /**
     * Per 04-Policies.docx: new customers must pay a £10 deposit before their
     * first appointment. This means genuinely new customers should start with
     * deposit_status = 'awaiting', not the DB default 'not_required'.
     *
     * GAP: The migration defaults to 'not_required'. The booking flow or
     * controller must explicitly set 'awaiting' when creating a new customer.
     *
     * @group unimplemented
     */
    public function test_new_customer_should_start_with_deposit_awaiting(): void
    {
        $this->markTestSkipped(
            'NOT YET IMPLEMENTED: New customer creation should set deposit_status to "awaiting". '
            . 'Currently the DB defaults to "not_required". The booking flow needs to detect '
            . 'first-time customers and set deposit_status = "awaiting" so staff know a £10 '
            . 'bank transfer is required before the appointment.'
        );
    }

    /**
     * When the £10 bank transfer is confirmed, deposit_status should transition
     * from 'awaiting' to 'received'.
     *
     * @group unimplemented
     */
    public function test_deposit_transitions_awaiting_to_received(): void
    {
        $this->markTestSkipped(
            'NOT YET IMPLEMENTED: No method exists to transition deposit_status from "awaiting" '
            . 'to "received". Need a method like Customers_model::confirm_deposit(int $customer_id) '
            . 'that updates deposit_status to "received" when the £10 bank transfer is confirmed.'
        );
    }

    /**
     * After a customer's first groom is completed, deposit_status should become
     * 'not_required' — they are now a regular customer.
     *
     * @group unimplemented
     */
    public function test_deposit_becomes_not_required_after_first_groom(): void
    {
        $this->markTestSkipped(
            'NOT YET IMPLEMENTED: After a customer\'s first appointment is marked "completed", '
            . 'deposit_status should transition from "received" to "not_required". This likely '
            . 'belongs in the appointment completion flow (Calendar controller or a post-completion hook).'
        );
    }

    /**
     * Per 02-Services-and-Pricing.docx: when deposit_status = 'received', the
     * £10 deposit is deducted from the final bill on the day of the groom.
     *
     * @group unimplemented
     */
    public function test_deposit_received_means_deducted_from_bill(): void
    {
        $this->markTestSkipped(
            'NOT YET IMPLEMENTED: When deposit_status = "received", the £10 should be deducted '
            . 'from the service price on the day. No billing/pricing logic currently references '
            . 'deposit_status. This would be implemented in the checkout or invoice flow.'
        );
    }

    // =======================================================================
    // Deposit Refund Rules (NOT YET IMPLEMENTED)
    // Per 04-Policies.docx
    // =======================================================================

    /**
     * Cancellation with 24+ hours notice: deposit is refundable.
     * deposit_status stays 'received' but a refund flag or process is triggered.
     *
     * @group unimplemented
     */
    public function test_cancellation_24h_plus_deposit_refundable(): void
    {
        $this->markTestSkipped(
            'NOT YET IMPLEMENTED: When a customer cancels 24+ hours before the appointment, '
            . 'the deposit should be refundable. No cancellation-time-check logic exists. '
            . 'Needs: compare cancellation timestamp against appointment start_datetime, '
            . 'and if delta >= 24 hours, flag the deposit for refund.'
        );
    }

    /**
     * No-show: deposit is non-refundable.
     *
     * @group unimplemented
     */
    public function test_no_show_deposit_non_refundable(): void
    {
        $this->markTestSkipped(
            'NOT YET IMPLEMENTED: When an appointment is marked as no-show, the deposit is '
            . 'forfeited (non-refundable). No code currently handles deposit forfeiture on no-show. '
            . 'This should be part of the no-show status-change handler.'
        );
    }

    /**
     * Late cancellation (under 24 hours): deposit is non-refundable.
     *
     * @group unimplemented
     */
    public function test_late_cancellation_deposit_non_refundable(): void
    {
        $this->markTestSkipped(
            'NOT YET IMPLEMENTED: When a customer cancels under 24 hours before the appointment, '
            . 'the deposit is non-refundable. No time-based cancellation logic exists. '
            . 'Needs: the same time-check as the 24h+ test, but with the opposite outcome.'
        );
    }

    // =======================================================================
    // Strike Count (NOT YET IMPLEMENTED)
    //
    // Actual policy (confirmed by owner):
    //   - Cancellation 24h+ before appointment: 0 strikes
    //   - Cancellation under 24h before appointment: +1 strike
    //   - No-show: +1 strike
    //   - 3 strikes within 12 months: customer must pay £10 deposit per booking
    // =======================================================================

    /**
     * A cancellation made 24+ hours before the appointment incurs NO strike.
     * This is the "good citizen" path — customer gave adequate notice.
     *
     * @group unimplemented
     */
    public function test_cancellation_24h_plus_incurs_zero_strikes(): void
    {
        $this->markTestSkipped(
            'NOT YET IMPLEMENTED: When a customer cancels 24+ hours before the appointment, '
            . 'strike_count should NOT change. No cancellation-time-check logic exists. '
            . 'Need: compare cancellation timestamp against appointment start_datetime, '
            . 'and if delta >= 24 hours, do not increment strike_count.'
        );
    }

    /**
     * A late cancellation (under 24 hours notice) increments strike_count by 1.
     *
     * @group unimplemented
     */
    public function test_late_cancellation_increments_strike_by_1(): void
    {
        $this->markTestSkipped(
            'NOT YET IMPLEMENTED: When a customer cancels under 24 hours before the appointment, '
            . 'strike_count should increase by 1. No strike increment logic exists anywhere in '
            . 'the codebase. Need: a method or hook triggered on cancellation that checks '
            . 'time delta and calls Customers_model::increment_strike($customer_id, 1).'
        );
    }

    /**
     * A no-show increments strike_count by 1.
     *
     * @group unimplemented
     */
    public function test_no_show_increments_strike_by_1(): void
    {
        $this->markTestSkipped(
            'NOT YET IMPLEMENTED: When a customer no-shows, strike_count should increase by 1. '
            . 'No strike increment logic exists anywhere in the codebase. '
            . 'Need: a method or hook triggered when appointment status changes to "no_show" '
            . 'that calls something like Customers_model::increment_strike($customer_id, 1).'
        );
    }

    /**
     * Strikes are counted within a rolling 12-month window.
     * Only strikes from the last 12 months should count toward the threshold.
     *
     * @group unimplemented
     */
    public function test_strikes_counted_within_rolling_12_month_window(): void
    {
        $this->markTestSkipped(
            'NOT YET IMPLEMENTED: Strikes should only count within a rolling 12-month window. '
            . 'Currently strike_count is a simple integer with no date tracking. To implement '
            . 'this properly, either: (a) store individual strike events with timestamps and '
            . 'count those within 12 months, or (b) add a strike_window_start date and reset '
            . 'logic. Neither exists yet.'
        );
    }

    /**
     * When a customer accumulates 3 strikes within 12 months, they are
     * required to pay a £10 deposit for each subsequent booking.
     * This should set deposit_status to 'awaiting' on future bookings.
     *
     * @group unimplemented
     */
    public function test_3_strikes_in_12_months_requires_deposit_per_booking(): void
    {
        $this->markTestSkipped(
            'NOT YET IMPLEMENTED: When strike_count >= 3 (within 12 months), the customer '
            . 'should be required to pay a £10 deposit for each future booking. This means '
            . 'deposit_status should be set to "awaiting" when they book. No logic exists to '
            . 'link strike_count threshold to deposit requirements. Need: a check during '
            . 'booking creation that sets deposit_status = "awaiting" if the customer has '
            . '3+ strikes in the last 12 months.'
        );
    }
}
