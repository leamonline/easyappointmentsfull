<?php extend('layouts/booking_layout'); ?>

<?php section('content'); ?>

<!-- Booking Cancellation Frame -->

<?php component('booking_cancellation_frame', [
    'manage_mode' => vars('manage_mode'),
    'appointment_data' => vars('appointment_data'),
    'display_delete_personal_information' => vars('display_delete_personal_information'),
]); ?>

<!-- Step 1: Login / Register -->

<?php component('booking_login_step'); ?>

<!-- Step 2: Select Your Dog -->

<?php component('booking_dog_step'); ?>

<!-- Step 3: Select Service & Provider -->

<?php component('booking_type_step', ['available_services' => vars('available_services')]); ?>

<!-- Step 4: Pick An Appointment Date -->

<?php component('booking_time_step', ['grouped_timezones' => vars('grouped_timezones')]); ?>

<!-- Step 5: Appointment Data Confirmation -->

<?php component('booking_final_step', [
    'manage_mode' => vars('manage_mode'),
    'display_terms_and_conditions' => vars('display_terms_and_conditions'),
    'display_privacy_policy' => vars('display_privacy_policy'),
]); ?>

<?php end_section('content'); ?>

<?php section('scripts'); ?>

<script src="<?= asset_url('assets/js/utils/lang.js') ?>"></script>
<script src="<?= asset_url('assets/js/utils/ui.js') ?>"></script>
<script src="<?= asset_url('assets/js/http/booking_http_client.js') ?>"></script>
<script src="<?= asset_url('assets/js/pages/booking.js') ?>"></script>

<?php end_section('scripts'); ?>
