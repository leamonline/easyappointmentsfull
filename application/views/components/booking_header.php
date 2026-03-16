<?php
/**
 * Local variables.
 *
 * @var string $company_name
 */
?>

<div id="header">
    <div id="company-name">
        <img src="<?= vars('company_logo') ?: base_url('assets/img/logo.png') ?>" alt="logo" id="company-logo">

        <span>
            <?= e($company_name) ?>
        </span>

        <p class="welcome-tagline">Come scruffy. Leave gorgeous. &#10022;</p>

        <div class="d-flex justify-content-center justify-content-md-start">
            <span class="display-booking-selection">
                <?= lang('service') ?>
            </span>
        </div>
    </div>

    <div id="steps">
        <div id="step-1" class="book-step active-step"
             data-tippy-content="<?= lang('service_and_provider') ?>">
            <strong>1</strong>
        </div>

        <div id="step-2" class="book-step" data-bs-toggle="tooltip"
             data-tippy-content="<?= lang('appointment_date_and_time') ?>">
            <strong>2</strong>
        </div>
        <div id="step-3" class="book-step" data-bs-toggle="tooltip"
             data-tippy-content="<?= lang('customer_information') ?>">
            <strong>3</strong>
        </div>
        <div id="step-4" class="book-step" data-bs-toggle="tooltip"
             data-tippy-content="<?= lang('appointment_confirmation') ?>">
            <strong>4</strong>
        </div>
    </div>

    <div class="header-wave">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,0 C360,55 720,55 1080,30 C1260,17 1380,5 1440,0 L1440,60 L0,60 Z" fill="#ffffff"/>
        </svg>
    </div>
</div>
