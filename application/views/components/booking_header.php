<?php
/**
 * Local variables.
 *
 * @var string $company_name
 */
?>

<div id="header">
    <div id="company-name">
        <img src="<?= vars('company_logo') ?: base_url('assets/img/smarterdog-logo.png') ?>" alt="Smarter Dog Grooming Salon logo" id="company-logo">

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

    <div id="steps" role="list" aria-label="Booking steps">
        <div id="step-1" class="book-step active-step" role="listitem"
             aria-current="step"
             data-tippy-content="<?= lang('login') ?>">
            <strong>1</strong>
            <span class="step-label"><?= lang('login') ?></span>
        </div>

        <div id="step-2" class="book-step" role="listitem"
             data-tippy-content="Your Dog">
            <strong>2</strong>
            <span class="step-label">Dog</span>
        </div>
        <div id="step-3" class="book-step" role="listitem"
             data-tippy-content="<?= lang('service_and_provider') ?>">
            <strong>3</strong>
            <span class="step-label"><?= lang('service') ?></span>
        </div>
        <div id="step-4" class="book-step" role="listitem"
             data-tippy-content="<?= lang('appointment_date_and_time') ?>">
            <strong>4</strong>
            <span class="step-label">Time</span>
        </div>
        <div id="step-5" class="book-step" role="listitem"
             data-tippy-content="<?= lang('appointment_confirmation') ?>">
            <strong>5</strong>
            <span class="step-label">Confirm</span>
        </div>
    </div>

    <div class="header-wave">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,0 C360,55 720,55 1080,30 C1260,17 1380,5 1440,0 L1440,60 L0,60 Z" fill="#ffffff"/>
        </svg>
    </div>
</div>
