<?php
/**
 * @var string $company_color
 */
?>

<?php if (!empty($company_color) && $company_color !== DEFAULT_COMPANY_COLOR && preg_match('/^#[0-9a-fA-F]{3,8}$/', $company_color)): ?>
    <style>
        /* Override brand color tokens with company color */
        :root {
            --sd-brand: <?= $company_color ?>;
            --sd-brand-dark: <?= $company_color ?>;
            --sd-brand-darker: <?= $company_color ?>;
            --sd-brand-hover: <?= $company_color ?>;
            --sd-brand-active: <?= $company_color ?>;
            --sd-brand-light: <?= $company_color ?>;
            --sd-brand-lighter: <?= $company_color ?>;
            --sd-brand-text: <?= $company_color ?>;
            --sd-brand-subtle: <?= $company_color ?>;
        }

        /* Derived shades via filters where distinct shades are needed */
        :root {
            --sd-brand-dark: <?= $company_color ?>;
            --sd-brand-hover: <?= $company_color ?>;
            --sd-brand-active: <?= $company_color ?>;
        }

        #header #header-menu .nav-item:hover {
            background: var(--sd-brand) !important;
            filter: brightness(85%);
        }

        #header #header-menu .nav-item.active {
            background: var(--sd-brand) !important;
            filter: brightness(75%);
        }

        #header #header-logo small {
            color: var(--sd-brand) !important;
            filter: brightness(60%);
        }

        #book-appointment-wizard .book-step {
            background: var(--sd-brand);
            filter: brightness(75%);
        }

        #book-appointment-wizard .book-step strong {
            color: var(--sd-brand);
            filter: brightness(200%);
        }

        #book-appointment-wizard #company-name .display-selected-service,
        #book-appointment-wizard #company-name .display-selected-provider {
            color: var(--sd-brand);
            border-right-color: var(--sd-brand) !important;
            filter: brightness(35%);
        }

        #book-appointment-wizard #company-name .display-booking-selection {
            color: var(--sd-brand);
            border-right-color: var(--sd-brand);
            filter: brightness(280%);
        }

        /* Generic Overrides */

        a {
            color: var(--sd-brand);
        }

        a:hover {
            color: var(--sd-brand);
        }

        .btn-primary {
            background-color: var(--sd-brand);
            border-color: var(--sd-brand);
        }

        .btn-primary:hover,
        .btn-primary:active,
        .btn-primary:focus {
            background-color: var(--sd-brand);
            border-color: var(--sd-brand);
            filter: brightness(120%);
            outline: none;
            box-shadow: none;
        }

        .btn-primary:disabled, .btn-primary.disabled {
            background-color: var(--sd-brand);
            border-color: var(--sd-brand);
            filter: brightness(70%);
            opacity: .75;
        }

        .dropdown-item.active,
        .dropdown-item:active {
            background-color: var(--sd-brand) !important;
        }

        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            background-color: var(--sd-brand) !important;
        }

        .nav .nav-link:not(.active) {
            color: var(--sd-brand) !important;
        }

        .form-control:focus {
            border-color: var(--sd-brand) !important;
            filter: brightness(120%);
            box-shadow: none;
        }

        .form-check-input:checked {
            background-color: var(--sd-brand) !important;
            border-color: var(--sd-brand) !important;
        }

        #frame-footer .backend-link {
            background-color: var(--sd-brand) !important;
        }

        #frame-footer .backend-link:hover {
            color: #fff;
        }

        .backend-page .filter-records .results .entry.selected {
            border-right-color: var(--sd-brand) !important;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthDropdown-month {
            background-color: var(--sd-brand) !important;
        }

        #existing-customers-list div:hover {
            background: var(--sd-brand) !important;
        }

        .fc-daygrid-event {
            color: rgb(51, 51, 51) !important;
        }
    </style>
<?php endif; ?>
