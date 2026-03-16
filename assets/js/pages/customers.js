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
 * Customers page.
 *
 * This module implements the functionality of the customers page.
 */
App.Pages.Customers = (function () {
    const $customers = $('#customers');
    const $filterCustomers = $('#filter-customers');
    const $id = $('#customer-id');
    const $firstName = $('#first-name');
    const $lastName = $('#last-name');
    const $email = $('#email');
    const $phoneNumber = $('#phone-number');
    const $address = $('#address');
    const $city = $('#city');
    const $zipCode = $('#zip-code');
    const $timezone = $('#timezone');
    const $language = $('#language');
    const $ldapDn = $('#ldap-dn');
    const $customField1 = $('#custom-field-1');
    const $customField2 = $('#custom-field-2');
    const $customField3 = $('#custom-field-3');
    const $customField4 = $('#custom-field-4');
    const $customField5 = $('#custom-field-5');
    const $notes = $('#notes');
    const $depositStatus = $('#deposit-status');
    const $strikeCount = $('#strike-count');
    const $strikeWarning = $('#strike-warning');
    const $formMessage = $('#form-message');
    const $customerAppointments = $('#customer-appointments');
    const $petsList = $('#pets-list');
    const $petForm = $('#pet-form');
    const $addPetBtn = $('#add-pet-btn');

    const moment = window.moment;

    let filterResults = {};
    let filterLimit = 20;

    /**
     * Add the page event listeners.
     */
    function addEventListeners() {
        /**
         * Event: Filter Customers Form "Submit"
         *
         * @param {jQuery.Event} event
         */
        $customers.on('submit', '#filter-customers form', (event) => {
            event.preventDefault();
            const key = $filterCustomers.find('.key').val();
            $filterCustomers.find('.selected').removeClass('selected');
            filterLimit = 20;
            App.Pages.Customers.resetForm();
            App.Pages.Customers.filter(key);
        });

        /**
         * Event: Filter Entry "Click"
         *
         * Display the customer data of the selected row.
         *
         * @param {jQuery.Event} event
         */
        $customers.on('click', '.customer-row', (event) => {
            if ($filterCustomers.find('.filter').prop('disabled')) {
                return; // Do nothing when user edits a customer record.
            }

            const customerId = $(event.currentTarget).attr('data-id');
            const customer = filterResults.find((filterResult) => Number(filterResult.id) === Number(customerId));

            App.Pages.Customers.display(customer);
            $('#filter-customers .selected').removeClass('selected');
            $(event.currentTarget).addClass('selected');
            $('#edit-customer, #delete-customer').prop('disabled', false);
        });

        /**
         * Event: Add Customer Button "Click"
         */
        $customers.on('click', '#add-customer', () => {
            App.Pages.Customers.resetForm();
            $customers.find('#add-edit-delete-group').hide();
            $customers.find('#save-cancel-group').show();
            $customers.find('.record-details').find('input, select, textarea').prop('disabled', false);
            $customers.find('.record-details .form-label span').prop('hidden', false);
            $filterCustomers.find('button').prop('disabled', true);
            $filterCustomers.find('.results').css('color', '#AAA');
        });

        /**
         * Event: Edit Customer Button "Click"
         */
        $customers.on('click', '#edit-customer', () => {
            $customers.find('.record-details').find('input, select, textarea').prop('disabled', false);
            $customers.find('.record-details .form-label span').prop('hidden', false);
            $customers.find('#add-edit-delete-group').hide();
            $customers.find('#save-cancel-group').show();
            $filterCustomers.find('button').prop('disabled', true);
            $filterCustomers.find('.results').css('color', '#AAA');
            $addPetBtn.show();
        });

        /**
         * Event: Cancel Customer Add/Edit Operation Button "Click"
         */
        $customers.on('click', '#cancel-customer', () => {
            const id = $id.val();

            App.Pages.Customers.resetForm();

            if (id) {
                select(id, true);
            }
        });

        /**
         * Event: Save Add/Edit Customer Operation "Click"
         */
        $customers.on('click', '#save-customer', () => {
            const customer = {
                first_name: $firstName.val(),
                last_name: $lastName.val(),
                email: $email.val(),
                phone_number: $phoneNumber.val(),
                address: $address.val(),
                city: $city.val(),
                zip_code: $zipCode.val(),
                notes: $notes.val(),
                timezone: $timezone.val(),
                language: $language.val() || 'english',
                custom_field_1: $customField1.val(),
                custom_field_2: $customField2.val(),
                custom_field_3: $customField3.val(),
                custom_field_4: $customField4.val(),
                custom_field_5: $customField5.val(),
                ldap_dn: $ldapDn.val(),
                deposit_status: $depositStatus.val(),
                strike_count: parseInt($strikeCount.val()) || 0,
            };

            if ($id.val()) {
                customer.id = $id.val();
            }

            if (!App.Pages.Customers.validate()) {
                return;
            }

            App.Pages.Customers.save(customer);
        });

        /**
         * Event: Delete Customer Button "Click"
         */
        $customers.on('click', '#delete-customer', () => {
            const customerId = $id.val();
            const buttons = [
                {
                    text: lang('cancel'),
                    click: (event, messageModal) => {
                        messageModal.hide();
                    },
                },
                {
                    text: lang('delete'),
                    click: (event, messageModal) => {
                        App.Pages.Customers.remove(customerId);
                        messageModal.hide();
                    },
                },
            ];

            App.Utils.Message.show(lang('delete_customer'), lang('delete_record_prompt'), buttons);
        });
    }

    /**
     * Save a customer record to the database (via ajax post).
     *
     * @param {Object} customer Contains the customer data.
     */
    function save(customer) {
        App.Http.Customers.save(customer).then((response) => {
            App.Layouts.Backend.displayNotification(lang('customer_saved'));
            App.Pages.Customers.resetForm();
            $('#filter-customers .key').val('');
            App.Pages.Customers.filter('', response.id, true);
        });
    }

    /**
     * Delete a customer record from database.
     *
     * @param {Number} id Record id to be deleted.
     */
    function remove(id) {
        App.Http.Customers.destroy(id).then(() => {
            App.Layouts.Backend.displayNotification(lang('customer_deleted'));
            App.Pages.Customers.resetForm();
            App.Pages.Customers.filter($('#filter-customers .key').val());
        });
    }

    /**
     * Validate customer data before save (insert or update).
     */
    function validate() {
        $formMessage.removeClass('alert-danger').hide();
        $('.is-invalid').removeClass('is-invalid');

        try {
            // Validate required fields.
            let missingRequired = false;

            $('.required').each((index, requiredField) => {
                if ($(requiredField).val() === '') {
                    $(requiredField).addClass('is-invalid');
                    missingRequired = true;
                }
            });

            if (missingRequired) {
                throw new Error(lang('fields_are_required'));
            }

            // Validate email address.
            const email = $email.val();

            if (email && !App.Utils.Validation.email(email)) {
                $email.addClass('is-invalid');
                throw new Error(lang('invalid_email'));
            }

            // Validate phone number.
            const phoneNumber = $phoneNumber.val();

            if (phoneNumber && !App.Utils.Validation.phone(phoneNumber)) {
                $phoneNumber.addClass('is-invalid');
                throw new Error(lang('invalid_phone'));
            }

            return true;
        } catch (error) {
            $formMessage.addClass('alert-danger').text(error.message).show();
            return false;
        }
    }

    /**
     * Bring the customer form back to its initial state.
     */
    function resetForm() {
        $customers.find('.record-details').find('input, select, textarea').val('').prop('disabled', true);
        $customers.find('.record-details .form-label span').prop('hidden', true);
        $customers.find('.record-details #timezone').val(vars('default_timezone'));
        $customers.find('.record-details #language').val(vars('default_language'));

        $depositStatus.val('not_required');
        $strikeCount.val(0);
        updateStrikeWarning(0);
        $petsList.empty();
        $petForm.hide();
        $addPetBtn.hide();
        $customerAppointments.empty();

        $customers.find('#edit-customer, #delete-customer').prop('disabled', true);
        $customers.find('#add-edit-delete-group').show();
        $customers.find('#save-cancel-group').hide();

        $customers.find('.record-details .is-invalid').removeClass('is-invalid');
        $customers.find('.record-details #form-message').hide();

        $filterCustomers.find('button').prop('disabled', false);
        $filterCustomers.find('.selected').removeClass('selected');
        $filterCustomers.find('.results').css('color', '');
    }

    /**
     * Display a customer record into the form.
     *
     * @param {Object} customer Contains the customer record data.
     */
    function display(customer) {
        $id.val(customer.id);
        $firstName.val(customer.first_name);
        $lastName.val(customer.last_name);
        $email.val(customer.email);
        $phoneNumber.val(customer.phone_number);
        $address.val(customer.address);
        $city.val(customer.city);
        $zipCode.val(customer.zip_code);
        $notes.val(customer.notes);
        $timezone.val(customer.timezone);
        $language.val(customer.language || 'english');
        $ldapDn.val(customer.ldap_dn);
        $customField1.val(customer.custom_field_1);
        $customField2.val(customer.custom_field_2);
        $customField3.val(customer.custom_field_3);
        $customField4.val(customer.custom_field_4);
        $customField5.val(customer.custom_field_5);
        $depositStatus.val(customer.deposit_status || 'not_required');
        $strikeCount.val(customer.strike_count || 0);
        updateStrikeWarning(customer.strike_count || 0);

        displayPets(customer.pets || []);
        $addPetBtn.hide(); // Shown only in edit mode

        $customerAppointments.empty();

        if (!customer.appointments.length) {
            $('<p/>', {
                'text': lang('no_records_found'),
            }).appendTo($customerAppointments);
        }

        customer.appointments.forEach((appointment) => {
            if (
                vars('role_slug') === App.Layouts.Backend.DB_SLUG_PROVIDER &&
                parseInt(appointment.id_users_provider) !== vars('user_id')
            ) {
                return;
            }

            if (
                vars('role_slug') === App.Layouts.Backend.DB_SLUG_SECRETARY &&
                vars('secretary_providers').indexOf(appointment.id_users_provider) === -1
            ) {
                return;
            }

            const start = App.Utils.Date.format(
                moment(appointment.start_datetime).toDate(),
                vars('date_format'),
                vars('time_format'),
                true,
            );

            const end = App.Utils.Date.format(
                moment(appointment.end_datetime).toDate(),
                vars('date_format'),
                vars('time_format'),
                true,
            );

            $('<div/>', {
                'class': 'appointment-row',
                'data-id': appointment.id,
                'html': [
                    // Service - Provider

                    $('<a/>', {
                        'href': App.Utils.Url.siteUrl(`calendar/reschedule/${appointment.hash}`),
                        'html': [
                            $('<i/>', {
                                'class': 'fas fa-edit me-1',
                            }),
                            $('<strong/>', {
                                'text':
                                    appointment.service.name +
                                    ' - ' +
                                    appointment.provider.first_name +
                                    ' ' +
                                    appointment.provider.last_name,
                            }),
                            $('<br/>'),
                        ],
                    }),

                    // Start

                    $('<small/>', {
                        'text': start,
                    }),
                    $('<br/>'),

                    // End

                    $('<small/>', {
                        'text': end,
                    }),
                    $('<br/>'),

                    // Timezone

                    $('<small/>', {
                        'text': vars('timezones')[appointment.provider.timezone],
                    }),
                ],
            }).appendTo('#customer-appointments');
        });
    }

    /**
     * Filter customer records.
     *
     * @param {String} keyword This keyword string is used to filter the customer records.
     * @param {Number} selectId Optional, if set then after the filter operation the record with the given
     * ID will be selected (but not displayed).
     * @param {Boolean} show Optional (false), if true then the selected record will be displayed on the form.
     */
    function filter(keyword, selectId = null, show = false) {
        App.Http.Customers.search(keyword, filterLimit).then((response) => {
            filterResults = response;

            $filterCustomers.find('.results').empty();

            response.forEach((customer) => {
                $('#filter-customers .results').append(App.Pages.Customers.getFilterHtml(customer)).append($('<hr/>'));
            });

            if (!response.length) {
                $filterCustomers.find('.results').append(
                    $('<em/>', {
                        'text': lang('no_records_found'),
                    }),
                );
            } else if (response.length === filterLimit) {
                $('<button/>', {
                    'type': 'button',
                    'class': 'btn btn-outline-secondary w-100 load-more text-center',
                    'text': lang('load_more'),
                    'click': () => {
                        filterLimit += 20;
                        App.Pages.Customers.filter(keyword, selectId, show);
                    },
                }).appendTo('#filter-customers .results');
            }

            if (selectId) {
                App.Pages.Customers.select(selectId, show);
            }
        });
    }

    /**
     * Get the filter results row HTML code.
     *
     * @param {Object} customer Contains the customer data.
     *
     * @return {String} Returns the record HTML code.
     */
    function getFilterHtml(customer) {
        const name = (customer.first_name || '[No First Name]') + ' ' + (customer.last_name || '[No Last Name]');

        let info = customer.email || '[No Email]';

        info = customer.phone_number ? info + ', ' + customer.phone_number : info;

        return $('<div/>', {
            'class': 'customer-row entry',
            'data-id': customer.id,
            'html': [
                $('<strong/>', {
                    'text': name,
                }),
                $('<br/>'),
                $('<small/>', {
                    'class': 'text-muted',
                    'text': info,
                }),
                $('<br/>'),
            ],
        });
    }

    /**
     * Select a specific record from the current filter results.
     *
     * If the customer id does not exist in the list then no record will be selected.
     *
     * @param {Number} id The record id to be selected from the filter results.
     * @param {Boolean} show Optional (false), if true then the method will display the record on the form.
     */
    function select(id, show = false) {
        $('#filter-customers .selected').removeClass('selected');

        $('#filter-customers .entry[data-id="' + id + '"]').addClass('selected');

        if (show) {
            const customer = filterResults.find((filterResult) => Number(filterResult.id) === Number(id));

            App.Pages.Customers.display(customer);

            $('#edit-customer, #delete-customer').prop('disabled', false);
        }
    }

    /**
     * Update the strike warning badge.
     */
    function updateStrikeWarning(count) {
        count = parseInt(count) || 0;
        if (count >= 3) {
            $strikeWarning.text('RECURRING SLOT AT RISK').removeClass('bg-warning text-dark').addClass('bg-danger text-white');
        } else if (count >= 2) {
            $strikeWarning.text('Warning: ' + count + '/3').removeClass('bg-danger text-white').addClass('bg-warning text-dark');
        } else {
            $strikeWarning.text(count + '/3').removeClass('bg-danger bg-warning text-white text-dark');
        }
    }

    /**
     * Display the pets list for the current customer.
     */
    function displayPets(pets) {
        $petsList.empty();
        $petForm.hide();

        if (!pets || !pets.length) {
            $petsList.append($('<p/>', {'class': 'text-muted', 'text': 'No pets registered.'}));
            return;
        }

        const sizeColors = {small: '#4CAF50', medium: '#2196F3', large: '#FF9800'};

        pets.forEach((pet) => {
            const sizeBadge = $('<span/>', {
                'class': 'badge ms-2',
                'text': pet.size || 'small',
                'css': {'backgroundColor': sizeColors[pet.size] || sizeColors.small},
            });

            const editBtn = $('<button/>', {
                'class': 'btn btn-sm btn-link edit-pet-btn p-0 ms-2',
                'type': 'button',
                'data-id': pet.id,
                'html': $('<i/>', {'class': 'fas fa-edit'}),
            });

            $('<div/>', {
                'class': 'pet-row p-2 mb-1 border rounded d-flex align-items-center',
                'data-id': pet.id,
                'html': [
                    $('<strong/>', {'text': pet.name}),
                    pet.breed ? $('<span/>', {'class': 'text-muted ms-1', 'text': '(' + pet.breed + ')'}) : null,
                    sizeBadge,
                    editBtn,
                ],
            }).appendTo($petsList);
        });
    }

    /**
     * Open the pet form for adding or editing.
     */
    function openPetForm(pet) {
        $('#pet-id').val(pet ? pet.id : '');
        $('#pet-customer-id').val(pet ? pet.id_users_customer : $id.val());
        $('#pet-name').val(pet ? pet.name : '');
        $('#pet-breed').val(pet ? pet.breed : '');
        $('#pet-size').val(pet ? pet.size : 'small');
        $('#pet-age').val(pet ? pet.age : '');
        $('#pet-coat-notes').val(pet ? pet.coat_notes : '');
        $('#pet-vaccination').val(pet ? pet.vaccination_status : 'unknown');
        $('#pet-behavioural-notes').val(pet ? pet.behavioural_notes : '');
        $('#delete-pet-btn').toggle(!!pet);
        $petForm.show();
    }

    /**
     * Add pet-related event listeners.
     */
    function addPetEventListeners() {
        $addPetBtn.on('click', () => openPetForm(null));

        $('#cancel-pet-btn').on('click', () => $petForm.hide());

        $('#save-pet-btn').on('click', () => {
            const petName = $('#pet-name').val().trim();
            if (!petName) {
                alert('Pet name is required.');
                return;
            }

            const pet = {
                name: petName,
                breed: $('#pet-breed').val(),
                size: $('#pet-size').val(),
                age: $('#pet-age').val(),
                coat_notes: $('#pet-coat-notes').val(),
                vaccination_status: $('#pet-vaccination').val(),
                behavioural_notes: $('#pet-behavioural-notes').val(),
                id_users_customer: $('#pet-customer-id').val() || $id.val(),
            };

            const petId = $('#pet-id').val();
            if (petId) {
                pet.id = petId;
            }

            const url = petId
                ? App.Utils.Url.siteUrl('pets/update')
                : App.Utils.Url.siteUrl('pets/store');

            $.post(url, {pet: pet, csrf_token: vars('csrf_token')})
                .done((response) => {
                    if (typeof response === 'string') response = JSON.parse(response);
                    $petForm.hide();
                    App.Layouts.Backend.displayNotification('Pet saved successfully.');
                    refreshPets();
                })
                .fail(() => {
                    App.Layouts.Backend.displayNotification('Failed to save pet.', 'danger');
                });
        });

        $('#delete-pet-btn').on('click', () => {
            if (!confirm('Delete this pet?')) return;
            const petId = $('#pet-id').val();
            $.post(App.Utils.Url.siteUrl('pets/destroy'), {pet_id: petId, csrf_token: vars('csrf_token')})
                .done(() => {
                    $petForm.hide();
                    App.Layouts.Backend.displayNotification('Pet deleted.');
                    refreshPets();
                })
                .fail(() => {
                    App.Layouts.Backend.displayNotification('Failed to delete pet.', 'danger');
                });
        });

        $petsList.on('click', '.edit-pet-btn', (event) => {
            const petId = $(event.currentTarget).data('id');
            const customerId = $id.val();
            $.post(App.Utils.Url.siteUrl('pets/get_by_customer'), {customer_id: customerId, csrf_token: vars('csrf_token')})
                .done((response) => {
                    if (typeof response === 'string') response = JSON.parse(response);
                    const pet = response.find((p) => Number(p.id) === Number(petId));
                    if (pet) openPetForm(pet);
                });
        });

        $strikeCount.on('change', () => updateStrikeWarning(parseInt($strikeCount.val()) || 0));
    }

    /**
     * Refresh the pets list for the current customer.
     */
    function refreshPets() {
        const customerId = $id.val();
        if (!customerId) return;
        $.post(App.Utils.Url.siteUrl('pets/get_by_customer'), {customer_id: customerId, csrf_token: vars('csrf_token')})
            .done((response) => {
                if (typeof response === 'string') response = JSON.parse(response);
                displayPets(response);
            });
    }

    /**
     * Initialize the module.
     */
    function initialize() {
        App.Pages.Customers.resetForm();
        App.Pages.Customers.addEventListeners();
        addPetEventListeners();
        App.Pages.Customers.filter('');
    }

    document.addEventListener('DOMContentLoaded', initialize);

    return {
        filter,
        save,
        remove,
        validate,
        getFilterHtml,
        resetForm,
        display,
        select,
        addEventListeners,
    };
})();
