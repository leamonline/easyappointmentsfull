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
 * Booking page.
 *
 * Flow: Login → Select Dog → Select Service → Pick Date/Time → Confirm
 */
App.Pages.Booking = (function () {
    const $selectDate = $('#select-date');
    const $selectService = $('#select-service');
    const $selectProvider = $('#select-provider');
    const $selectTimezone = $('#select-timezone');
    const $captchaTitle = $('.captcha-title');
    const $availableHours = $('#available-hours');
    const $bookAppointmentSubmit = $('#book-appointment-submit');
    const $deletePersonalInformation = $('#delete-personal-information');
    const $bookingNotes = $('#booking-notes');
    const $customField1 = $('#custom-field-1');
    const $customField2 = $('#custom-field-2');
    const $customField3 = $('#custom-field-3');
    const $customField4 = $('#custom-field-4');
    const $customField5 = $('#custom-field-5');
    const $displayBookingSelection = $('.display-booking-selection');
    const tippy = window.tippy;
    const moment = window.moment;

    let manageMode = vars('manage_mode') || false;
    let bookingCustomer = null;
    let bookingPets = [];
    let selectedPet = null;

    // Price tiers by size for each service (keyed by service name, lowercase)
    const servicePrices = {
        'full groom':   { small: 42, medium: 46, large: 60 },
        'coat refresh':  { small: 38, medium: 42, large: 55 },
        'puppy cut':     { small: 38, medium: 38, large: null }, // not available for large
    };

    const serviceIcons = {
        'full groom': '✂️',
        'coat refresh': '🛁',
        'puppy cut': '🐶',
    };

    const serviceSummaries = {
        'full groom': 'Complete groom including bath, dry, full haircut & style, nail trim, ear clean, and more.',
        'coat refresh': 'Bath plus the right coat care for your dog — brush-out or de-shed depending on coat type.',
        'puppy cut': 'Gentle first groom for puppies under 6 months. A calm introduction to grooming.',
    };

    function isPuppyEligible() {
        if (!selectedPet || !selectedPet.date_of_birth) return false;
        const dob = moment(selectedPet.date_of_birth);
        const ageMonths = moment().diff(dob, 'months');
        return ageMonths < 6;
    }

    function getPriceForSize(serviceName, size) {
        const key = serviceName.toLowerCase();
        const prices = servicePrices[key];
        if (!prices) return null;
        return prices[size || 'small'];
    }

    function renderServiceCards() {
        const $container = $('.service-cards-container');
        $container.empty();

        const dogSize = selectedPet ? selectedPet.size : 'small';
        const showPuppy = isPuppyEligible();

        const services = vars('available_services');
        const featured = services.find(s => s.name.toLowerCase() === 'full groom');
        const others = services.filter(s => s.name.toLowerCase() !== 'full groom');

        // Featured card
        if (featured) {
            const price = getPriceForSize(featured.name, dogSize);
            $container.append(`
                <div class="col-12 mb-4">
                    <div class="service-card service-card--featured" data-service-id="${featured.id}">
                        <div class="service-card__badge">Most Popular</div>
                        <div class="service-card__icon">${serviceIcons[featured.name.toLowerCase()] || '✂️'}</div>
                        <h3 class="service-card__name">${App.Utils.String.escapeHtml(featured.name)}</h3>
                        <p class="service-card__price">from £${price}</p>
                        <p class="service-card__summary">${serviceSummaries[featured.name.toLowerCase()] || ''}</p>
                        <div class="service-card__select-label">Select</div>
                    </div>
                </div>
            `);
        }

        // Other service cards
        const otherCards = others.filter(s => {
            const key = s.name.toLowerCase();
            if (key === 'puppy cut' && !showPuppy) return false;
            const price = getPriceForSize(s.name, dogSize);
            if (price === null) return false; // not available for this size
            return true;
        });

        otherCards.forEach(s => {
            const price = getPriceForSize(s.name, dogSize);
            const icon = serviceIcons[s.name.toLowerCase()] || '🐕';
            const summary = serviceSummaries[s.name.toLowerCase()] || '';
            const colClass = otherCards.length === 1 ? 'col-12 col-sm-8 offset-sm-2' : 'col-12 col-sm-6';

            $container.append(`
                <div class="${colClass} mb-3">
                    <div class="service-card" data-service-id="${s.id}">
                        <div class="service-card__icon">${icon}</div>
                        <h3 class="service-card__name">${App.Utils.String.escapeHtml(s.name)}</h3>
                        <p class="service-card__price">from £${price}</p>
                        <p class="service-card__summary">${summary}</p>
                        <div class="service-card__select-label">Select</div>
                    </div>
                </div>
            `);
        });
    }

    function detectDatepickerMonthChangeStep(previousDateTimeMoment, nextDateTimeMoment) {
        return previousDateTimeMoment.isAfter(nextDateTimeMoment) ? -1 : 1;
    }

    function initialize() {
        if (Boolean(Number(vars('display_cookie_notice'))) && window?.cookieconsent) {
            cookieconsent.initialise({
                palette: {
                    popup: {background: '#ffffffbd', text: '#666666'},
                    button: {background: '#429a82', text: '#ffffff'},
                },
                content: {
                    message: lang('website_using_cookies_to_ensure_best_experience'),
                    dismiss: 'OK',
                },
            });
            const $cookieNoticeLink = $('.cc-link');
            $cookieNoticeLink.replaceWith(
                $('<a/>', {
                    'data-bs-toggle': 'modal',
                    'data-bs-target': '#cookie-notice-modal',
                    'href': '#',
                    'class': 'cc-link',
                    'text': $cookieNoticeLink.text(),
                }),
            );
        }

        manageMode = vars('manage_mode');
        tippy('[data-tippy-content]');

        let monthTimeout;

        App.Utils.UI.initializeDatePicker($selectDate, {
            inline: true,
            minDate: moment().subtract(1, 'day').set({hours: 23, minutes: 59, seconds: 59}).toDate(),
            maxDate: moment().add(vars('future_booking_limit'), 'days').toDate(),
            onChange: (selectedDates) => {
                App.Http.Booking.getAvailableHours(moment(selectedDates[0]).format('YYYY-MM-DD'));
                updateConfirmFrame();
            },
            onMonthChange: (selectedDates, dateStr, instance) => {
                $selectDate.parent().fadeTo(400, 0.3);
                if (monthTimeout) clearTimeout(monthTimeout);
                monthTimeout = setTimeout(() => {
                    const previousMoment = moment(instance.selectedDates[0]);
                    const displayedMonthMoment = moment(
                        instance.currentYearElement.value + '-' +
                        String(Number(instance.monthsDropdownContainer.value) + 1).padStart(2, '0') + '-01',
                    );
                    App.Http.Booking.getUnavailableDates(
                        $selectProvider.val(), $selectService.val(),
                        displayedMonthMoment.format('YYYY-MM-DD'),
                        detectDatepickerMonthChangeStep(previousMoment, displayedMonthMoment),
                    );
                }, 500);
            },
            onYearChange: (selectedDates, dateStr, instance) => {
                setTimeout(() => {
                    const previousMoment = moment(instance.selectedDates[0]);
                    const displayedMonthMoment = moment(
                        instance.currentYearElement.value + '-' +
                        (Number(instance.monthsDropdownContainer.value) + 1) + '-01',
                    );
                    App.Http.Booking.getUnavailableDates(
                        $selectProvider.val(), $selectService.val(),
                        displayedMonthMoment.format('YYYY-MM-DD'),
                        detectDatepickerMonthChangeStep(previousMoment, displayedMonthMoment),
                    );
                }, 500);
            },
        });

        App.Utils.UI.setDateTimePickerValue($selectDate, new Date());

        $selectTimezone.val('Europe/London');

        addEventListeners();
        checkExistingSession();

        if (manageMode) {
            applyAppointmentData(vars('appointment_data'), vars('provider_data'), vars('customer_data'));
            $('#wizard-frame-1').css({'visibility': 'visible', 'display': 'none'}).fadeIn();
        } else {
            $('#wizard-frame-1').css({'visibility': 'visible', 'display': 'none'}).fadeIn();
        }
    }

    function checkExistingSession() {
        $.get(App.Utils.Url.siteUrl('booking_auth/check_session'), {
            csrf_token: vars('csrf_token'),
        }).done((response) => {
            if (response && response.customer) {
                bookingCustomer = response.customer;
                bookingPets = response.pets || [];
                showLoggedInState();
            }
        });
    }

    function showLoggedInState() {
        $('#auth-tabs, #auth-tab-content').hide();
        $('#logged-in-state').show();
        $('#logged-in-name').text(
            (bookingCustomer.first_name || '') + ' ' + (bookingCustomer.last_name || ''),
        );
        $('#logged-in-email').text(bookingCustomer.email || '');
        $('#button-next-1').prop('disabled', false);
    }

    function showAuthForm() {
        $('#auth-tabs, #auth-tab-content').show();
        $('#logged-in-state').hide();
        $('#button-next-1').prop('disabled', true);
        bookingCustomer = null;
        bookingPets = [];
        selectedPet = null;
    }

    function renderDogCards() {
        const $list = $('#dog-selection-list');
        const $noDogsMsg = $('#no-dogs-message');
        $list.empty();

        if (!bookingPets.length) {
            $noDogsMsg.show();
            return;
        }

        $noDogsMsg.hide();

        bookingPets.forEach((pet) => {
            const sizeLabel = pet.size === 'small' ? 'Small' : pet.size === 'medium' ? 'Medium' : 'Large';
            const isSelected = selectedPet && Number(selectedPet.id) === Number(pet.id);

            const $card = $(`
                <div class="dog-card p-3 mb-2 rounded-3 d-flex align-items-center" data-pet-id="${pet.id}"
                     style="cursor:pointer; border: 2px solid ${isSelected ? 'var(--sd-sky-blue)' : '#e0e0e0'};
                            background: ${isSelected ? 'rgba(11, 173, 235, 0.06)' : '#fff'}; transition: all 0.2s;">
                    <div class="me-3">
                        <i class="fas fa-dog fa-2x" style="color: ${isSelected ? 'var(--sd-sky-blue)' : '#aaa'};"></i>
                    </div>
                    <div class="flex-grow-1">
                        <strong>${App.Utils.String.escapeHtml(pet.name)}</strong>
                        ${pet.breed ? '<span class="text-muted ms-2">' + App.Utils.String.escapeHtml(pet.breed) + '</span>' : ''}
                        <br><span class="badge bg-secondary">${sizeLabel}</span>
                    </div>
                    <div>
                        <i class="fas ${isSelected ? 'fa-check-circle' : 'fa-circle'}"
                           style="color: ${isSelected ? 'var(--sd-sky-blue)' : '#ddd'}; font-size: 1.5rem;"></i>
                    </div>
                </div>
            `);

            $card.on('click', () => {
                selectedPet = pet;
                renderDogCards();
            });

            $list.append($card);
        });
    }

    function addEventListeners() {
        // --- Step 1: Login / Register ---

        $('#btn-login').on('click', () => {
            const email = $('#login-email').val().trim();
            const password = $('#login-password').val();
            const $error = $('#login-error');
            $error.hide();

            if (!email || !password) {
                $error.text('Please enter your email and password.').show();
                return;
            }

            $('#btn-login').prop('disabled', true).html(
                '<i class="fas fa-spinner fa-spin me-2"></i>Logging in...',
            );

            $.post(App.Utils.Url.siteUrl('booking_auth/login'), {
                csrf_token: vars('csrf_token'),
                email: email,
                password: password,
            })
                .done((response) => {
                    if (!response || response.success === false) {
                        $error.text((response && response.message) || 'Login failed.').show();
                        return;
                    }
                    bookingCustomer = response.customer;
                    bookingPets = response.pets || [];
                    showLoggedInState();
                })
                .fail(() => {
                    $error.text('Login failed. Please check your credentials.').show();
                })
                .always(() => {
                    $('#btn-login')
                        .prop('disabled', false)
                        .html('<i class="fas fa-sign-in-alt me-2"></i>' + lang('login'));
                });
        });

        // Allow Enter key to submit login
        $('#login-email, #login-password').on('keypress', (e) => {
            if (e.which === 13) $('#btn-login').trigger('click');
        });

        $('#btn-register').on('click', () => {
            const firstName = $('#reg-first-name').val().trim();
            const lastName = $('#reg-last-name').val().trim();
            const email = $('#reg-email').val().trim();
            const phone = $('#reg-phone').val().trim();
            const password = $('#reg-password').val();
            const confirmPassword = $('#reg-password-confirm').val();
            const $error = $('#register-error');
            $error.hide();

            if (!firstName || !lastName || !email || !password) {
                $error.text('Please fill in all required fields.').show();
                return;
            }
            if (password !== confirmPassword) {
                $error.text('Passwords do not match.').show();
                return;
            }
            if (password.length < 6) {
                $error.text('Password must be at least 6 characters.').show();
                return;
            }

            $('#btn-register').prop('disabled', true).html(
                '<i class="fas fa-spinner fa-spin me-2"></i>Creating account...',
            );

            $.post(App.Utils.Url.siteUrl('booking_auth/register'), {
                csrf_token: vars('csrf_token'),
                first_name: firstName,
                last_name: lastName,
                email: email,
                phone_number: phone,
                password: password,
            })
                .done((response) => {
                    if (!response || response.success === false) {
                        $error.text((response && response.message) || 'Registration failed.').show();
                        return;
                    }
                    bookingCustomer = response.customer;
                    bookingPets = response.pets || [];
                    showLoggedInState();
                })
                .fail(() => {
                    $error.text('Registration failed. Please try again.').show();
                })
                .always(() => {
                    $('#btn-register')
                        .prop('disabled', false)
                        .html('<i class="fas fa-user-plus me-2"></i>Register');
                });
        });

        $('#btn-logout').on('click', () => {
            $.post(App.Utils.Url.siteUrl('booking_auth/logout'), {
                csrf_token: vars('csrf_token'),
            }).always(() => showAuthForm());
        });

        // --- Step 2: Dog Selection ---

        $('#btn-show-add-dog').on('click', () => {
            $('#add-dog-form').slideDown();
            $('#btn-show-add-dog').hide();
        });

        $('#btn-cancel-add-dog').on('click', () => {
            $('#add-dog-form').slideUp();
            $('#btn-show-add-dog').show();
            $('#new-dog-name, #new-dog-breed').val('');
            $('#new-dog-size').val('medium');
            $('#add-dog-error').hide();
        });

        $('#btn-save-dog').on('click', () => {
            const name = $('#new-dog-name').val().trim();
            const breed = $('#new-dog-breed').val().trim();
            const dob = $('#new-dog-dob').val();
            const size = $('#new-dog-size').val();
            const $error = $('#add-dog-error');
            $error.hide();

            if (!name) {
                $error.text("Please enter your dog's name.").show();
                return;
            }
            if (!dob) {
                $error.text("Please enter your dog's date of birth.").show();
                return;
            }

            $('#btn-save-dog').prop('disabled', true);

            $.post(App.Utils.Url.siteUrl('booking_auth/add_pet'), {
                csrf_token: vars('csrf_token'),
                name: name,
                breed: breed,
                date_of_birth: dob,
                size: size,
            })
                .done((response) => {
                    if (response.success === false) {
                        $error.text(response.message).show();
                        return;
                    }
                    bookingPets.push(response.pet);
                    selectedPet = response.pet;
                    renderDogCards();
                    $('#add-dog-form').slideUp();
                    $('#btn-show-add-dog').show();
                    $('#new-dog-name, #new-dog-breed, #new-dog-dob').val('');
                    $('#new-dog-size').val('medium');
                })
                .fail(() => {
                    $error.text('Failed to save dog. Please try again.').show();
                })
                .always(() => {
                    $('#btn-save-dog').prop('disabled', false);
                });
        });

        // --- Step 3: Service Selection ---

        $selectTimezone.on('change', () => {
            const date = App.Utils.UI.getDateTimePickerValue($selectDate);
            if (!date) return;
            App.Http.Booking.getAvailableHours(moment(date).format('YYYY-MM-DD'));
            updateConfirmFrame();
        });

        $selectProvider.on('change', (event) => {
            const todayObj = new Date();
            App.Utils.UI.setDateTimePickerValue($selectDate, todayObj);
            App.Http.Booking.getUnavailableDates(
                $(event.target).val(),
                $selectService.val(),
                moment(todayObj).format('YYYY-MM-DD'),
            );
            updateConfirmFrame();
        });

        // --- Step 3: Service card selection ---
        $(document).on('click', '.service-card', function () {
            const serviceId = $(this).data('service-id');
            if (!serviceId) return;
            $('.service-card').removeClass('selected');
            $(this).addClass('selected');
            $selectService.val(serviceId).trigger('change');
        });

        $selectService.on('change', (event) => {
            const serviceId = $selectService.val();
            $selectProvider.parent().prop('hidden', true);
            $selectProvider.empty();
            $selectProvider.append(new Option(lang('please_select'), ''));

            vars('available_providers').forEach((provider) => {
                const canServe =
                    provider.services.filter((sid) => Number(sid) === Number(serviceId)).length > 0;
                if (canServe) {
                    $selectProvider.append(
                        new Option(provider.first_name + ' ' + provider.last_name, provider.id),
                    );
                }
            });

            const count = $selectProvider.find('option').length;
            if (count === 2) $selectProvider.find('option[value=""]').remove();
            if (count > 2 && Boolean(Number(vars('display_any_provider')))) {
                $(new Option(lang('any_provider'), 'any-provider')).insertAfter(
                    $selectProvider.find('option:first'),
                );
            }

            const $first = $selectProvider.find('option[value!=""]').first();
            if ($first.length) $selectProvider.val($first.val());

            App.Http.Booking.getUnavailableDates(
                $selectProvider.val(),
                $(event.target).val(),
                moment(App.Utils.UI.getDateTimePickerValue($selectDate)).format('YYYY-MM-DD'),
            );

            updateConfirmFrame();
            updateServiceDescription(serviceId);
        });

        // --- Navigation ---

        $('.button-next').on('click', (event) => {
            const $target = $(event.currentTarget);
            const step = $target.attr('data-step_index');

            if (step === '1' && !bookingCustomer) return;

            if (step === '2') {
                if (!selectedPet) {
                    if (!$('#select-dog-prompt').length) {
                        $('<div/>', {
                            id: 'select-dog-prompt',
                            class: 'text-danger mb-3 text-center',
                            text: 'Please select a dog for your appointment.',
                        }).prependTo('#dog-selection-list');
                    }
                    return;
                }
                $('#select-dog-prompt').remove();

                // Rebuild service cards based on selected dog's size and age
                renderServiceCards();
                // Clear any previous selection
                $selectService.val('');
                $('.service-card').removeClass('selected');
            }

            if (step === '3' && !$selectService.val()) return;

            if (step === '4') {
                if (!$('.selected-hour').length) {
                    if (!$('#select-hour-prompt').length) {
                        $('<div/>', {
                            id: 'select-hour-prompt',
                            class: 'text-danger mb-4',
                            text: lang('appointment_hour_missing'),
                        }).prependTo('#available-hours');
                    }
                    return;
                }
                updateConfirmFrame();
            }

            if (step === '1') renderDogCards();

            const next = parseInt(step) + 1;
            $target.parents().eq(1).fadeOut(() => {
                $('.active-step').removeClass('active-step');
                $('#step-' + next).addClass('active-step');
                $('#wizard-frame-' + next).fadeIn();
            });

            const el = document.scrollingElement || document.body;
            if (window.innerHeight < el.scrollHeight) el.scrollTop = 0;
        });

        $('.button-back').on('click', (event) => {
            const prev = parseInt($(event.currentTarget).attr('data-step_index')) - 1;
            $(event.currentTarget)
                .parents()
                .eq(1)
                .fadeOut(() => {
                    $('.active-step').removeClass('active-step');
                    $('#step-' + prev).addClass('active-step');
                    $('#wizard-frame-' + prev).fadeIn();
                });
        });

        // --- Step 4: Available Hours ---

        $availableHours.on('click', '.available-hour', (event) => {
            $availableHours.find('.selected-hour').removeClass('selected-hour');
            $(event.target).addClass('selected-hour');
            updateConfirmFrame();
        });

        // --- Step 5: Confirm ---

        $bookAppointmentSubmit.on('click', () => {
            const $terms = $('#accept-to-terms-and-conditions');
            $terms.removeClass('is-invalid');
            if ($terms.length && !$terms.prop('checked')) {
                $terms.addClass('is-invalid');
                return;
            }

            const $privacy = $('#accept-to-privacy-policy');
            $privacy.removeClass('is-invalid');
            if ($privacy.length && !$privacy.prop('checked')) {
                $privacy.addClass('is-invalid');
                return;
            }

            App.Http.Booking.registerAppointment();
        });

        $captchaTitle.on('click', 'button', () => {
            $('.captcha-image').attr('src', App.Utils.Url.siteUrl('captcha?' + Date.now()));
        });

        $selectDate.on('mousedown', '.ui-datepicker-calendar td', () => {
            setTimeout(() => App.Http.Booking.applyPreviousUnavailableDates(), 300);
        });

        // Manage mode
        if (manageMode) {
            $('#cancel-appointment').on('click', () => {
                const $form = $('#cancel-appointment-form');
                let $reason;
                App.Utils.Message.show(
                    lang('cancel_appointment_title'),
                    lang('write_appointment_removal_reason'),
                    [
                        {text: lang('close'), click: (e, m) => m.hide()},
                        {
                            text: lang('confirm'),
                            click: () => {
                                if (!$reason.val()) {
                                    $reason.css('border', '2px solid #DC3545');
                                    return;
                                }
                                $form.find('#hidden-cancellation-reason').val($reason.val());
                                $form.submit();
                            },
                        },
                    ],
                );
                $reason = $('<textarea/>', {
                    class: 'form-control mt-2',
                    id: 'cancellation-reason',
                    rows: '3',
                    css: {width: '100%'},
                }).appendTo('#message-modal .modal-body');
                return false;
            });

            $deletePersonalInformation.on('click', () => {
                App.Utils.Message.show(
                    lang('delete_personal_information'),
                    lang('delete_personal_information_prompt'),
                    [
                        {text: lang('cancel'), click: (e, m) => m.hide()},
                        {text: lang('delete'), click: () => App.Http.Booking.deletePersonalInformation(vars('customer_token'))},
                    ],
                );
            });
        }
    }

    function updateConfirmFrame() {
        const serviceId = $selectService.val();
        const serviceText = serviceId ? $selectService.find('option:selected').text() : lang('service');
        $displayBookingSelection.text(serviceText);

        if (!$availableHours.find('.selected-hour').text()) return;

        const service = vars('available_services').find((s) => Number(s.id) === Number(serviceId));
        if (!service) return;

        const dateObj = App.Utils.UI.getDateTimePickerValue($selectDate);
        const dateMoment = moment(dateObj);
        const dateStr = dateMoment.format('YYYY-MM-DD');
        const timeStr = $availableHours.find('.selected-hour').text();
        let formatted = '';
        if (dateObj) {
            formatted = App.Utils.Date.format(dateStr, vars('date_format'), vars('time_format'), false) + ' ' + timeStr;
        }

        const tzText = $selectTimezone.find('option:selected').text();
        const petName = selectedPet ? App.Utils.String.escapeHtml(selectedPet.name) : '';
        const petBreed = selectedPet?.breed ? App.Utils.String.escapeHtml(selectedPet.breed) : '';
        const petSize = selectedPet ? (selectedPet.size === 'small' ? 'Small' : selectedPet.size === 'medium' ? 'Medium' : 'Large') : '';

        $('#appointment-details').html(`
            <div>
                <div class="mb-2 fw-bold fs-3">${serviceText}</div>
                <div class="mb-2" ${!petName ? 'hidden' : ''}>
                    <i class="fas fa-paw me-2"></i>${petName}${petBreed ? ' (' + petBreed + ')' : ''} — ${petSize}
                </div>
                <div class="mb-2"><i class="fas fa-calendar-day me-2"></i>${formatted}</div>
                <div class="mb-2"><i class="fas fa-clock me-2"></i>${service.duration} ${lang('minutes')}</div>
                <div class="mb-2"><i class="fas fa-globe me-2"></i>${tzText}</div>
                <div class="mb-2" ${!Number(service.price) ? 'hidden' : ''}>
                    <i class="fas fa-cash-register me-2"></i>${Number(service.price).toFixed(2)} ${service.currency}
                </div>
            </div>
        `);

        const fn = bookingCustomer ? App.Utils.String.escapeHtml(bookingCustomer.first_name || '') : '';
        const ln = bookingCustomer ? App.Utils.String.escapeHtml(bookingCustomer.last_name || '') : '';
        const full = `${fn} ${ln}`.trim();
        const em = bookingCustomer ? App.Utils.String.escapeHtml(bookingCustomer.email || '') : '';
        const ph = bookingCustomer ? App.Utils.String.escapeHtml(bookingCustomer.phone_number || '') : '';

        $('#customer-details').html(`
            <div>
                <div class="mb-2 fw-bold fs-3">${lang('contact_info')}</div>
                <div class="mb-2 fw-bold text-muted" ${!full ? 'hidden' : ''}>${full}</div>
                <div class="mb-2" ${!em ? 'hidden' : ''}>${em}</div>
                <div class="mb-2" ${!ph ? 'hidden' : ''}>${ph}</div>
            </div>
        `);

        // Build post data
        const data = {};
        data.customer = {
            id: bookingCustomer ? bookingCustomer.id : null,
            last_name: bookingCustomer ? bookingCustomer.last_name : '',
            first_name: bookingCustomer ? bookingCustomer.first_name : '',
            email: bookingCustomer ? bookingCustomer.email : '',
            phone_number: bookingCustomer ? (bookingCustomer.phone_number || '') : '',
            address: bookingCustomer ? (bookingCustomer.address || '') : '',
            city: bookingCustomer ? (bookingCustomer.city || '') : '',
            zip_code: bookingCustomer ? (bookingCustomer.zip_code || '') : '',
            timezone: $selectTimezone.val(),
            custom_field_1: $customField1.val(),
            custom_field_2: $customField2.val(),
            custom_field_3: $customField3.val(),
            custom_field_4: $customField4.val(),
            custom_field_5: $customField5.val(),
        };

        data.appointment = {
            start_datetime:
                moment(App.Utils.UI.getDateTimePickerValue($selectDate)).format('YYYY-MM-DD') +
                ' ' + moment($('.selected-hour').data('value'), 'HH:mm').format('HH:mm') + ':00',
            end_datetime: calculateEndDatetime(),
            notes: $bookingNotes.val() || '',
            is_unavailability: false,
            id_users_provider: $selectProvider.val(),
            id_services: $selectService.val(),
        };

        data.pet = selectedPet
            ? {id: selectedPet.id, name: selectedPet.name, breed: selectedPet.breed || '', size: selectedPet.size || 'small'}
            : null;

        data.manage_mode = Number(manageMode);

        if (manageMode) {
            data.appointment.id = vars('appointment_data').id;
            data.customer.id = vars('customer_data').id;
        }

        $('input[name="post_data"]').val(JSON.stringify(data));
    }

    function calculateEndDatetime() {
        const serviceId = $selectService.val();
        const service = vars('available_services').find((s) => Number(s.id) === Number(serviceId));
        const selectedDate = moment(App.Utils.UI.getDateTimePickerValue($selectDate)).format('YYYY-MM-DD');
        const selectedHour = $('.selected-hour').data('value');
        const start = moment(selectedDate + ' ' + selectedHour);
        const end = service.duration && start ? start.clone().add({minutes: parseInt(service.duration)}) : moment();
        return end.format('YYYY-MM-DD HH:mm:ss');
    }

    function applyAppointmentData(appointment, provider, customer) {
        try {
            $selectService.val(appointment.id_services).trigger('change');
            $selectProvider.val(appointment.id_users_provider);
            const startMoment = moment(appointment.start_datetime);
            App.Utils.UI.setDateTimePickerValue($selectDate, startMoment.toDate());
            App.Http.Booking.getAvailableHours(startMoment.format('YYYY-MM-DD'));
            App.Http.Booking.getUnavailableDates(
                appointment.id_users_provider,
                appointment.id_services,
                startMoment.format('YYYY-MM-DD'),
            );
            bookingCustomer = customer;
            $bookingNotes.val(appointment.notes || '');
            updateConfirmFrame();
            return true;
        } catch (exc) {
            return false;
        }
    }

    function updateServiceDescription(serviceId) {
        // Highlight the matching card
        $('.service-card').removeClass('selected');
        $(`.service-card[data-service-id="${serviceId}"]`).addClass('selected');
    }

    function validateCustomerForm() {
        return !!bookingCustomer;
    }

    document.addEventListener('DOMContentLoaded', initialize);

    return {
        manageMode,
        updateConfirmFrame,
        updateServiceDescription,
        validateCustomerForm,
        renderServiceCards,
    };
})();
