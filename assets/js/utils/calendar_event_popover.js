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
 * Calendar event popover utility.
 *
 * This module implements the functionality of calendar event popovers.
 */
App.Utils.CalendarEventPopover = (function () {
    /**
     * Render a map icon that links to Google maps.
     *
     * Old Name: GeneralFunctions.renderMapIcon
     *
     * @param {Object} user Should have the address, city, etc properties.
     *
     * @return {String} The rendered HTML.
     */
    function renderMapIcon(user) {
        const data = [];

        if (user.address) {
            data.push(user.address);
        }

        if (user.city) {
            data.push(user.city);
        }

        if (user.state) {
            data.push(user.state);
        }

        if (user.zip_code) {
            data.push(user.zip_code);
        }

        if (!data.length) {
            return null;
        }

        return $('<div/>', {
            'html': [
                $('<a/>', {
                    'href': 'https://google.com/maps/place/' + data.join(','),
                    'target': '_blank',
                    'html': [
                        $('<span/>', {
                            'class': 'fas fa-map-marker-alt',
                        }),
                    ],
                }),
            ],
        }).html();
    }

    /**
     * Render a mail icon.
     *
     * Old Name: GeneralFunctions.renderMailIcon
     *
     * @param {String} email
     *
     * @return {String} The rendered HTML.
     */
    function renderMailIcon(email) {
        if (!email) {
            return null;
        }

        return $('<div/>', {
            'html': [
                $('<a/>', {
                    'href': 'mailto:' + email,
                    'target': '_blank',
                    'html': [
                        $('<span/>', {
                            'class': 'fas fa-envelope',
                        }),
                    ],
                }),
            ],
        }).html();
    }

    /**
     * Render a phone icon.
     *
     * Old Name: GeneralFunctions.renderPhoneIcon
     *
     * @param {String} phone
     *
     * @return {String} The rendered HTML.
     */
    function renderPhoneIcon(phone) {
        if (!phone) {
            return null;
        }

        return $('<div/>', {
            'html': [
                $('<a/>', {
                    'href': 'tel:' + phone,
                    'target': '_blank',
                    'html': [
                        $('<span/>', {
                            'class': 'fas fa-phone-alt',
                        }),
                    ],
                }),
            ],
        }).html();
    }

    /**
     * Render custom content into the popover of events.
     *
     * Shows pet info (name, breed, size) and seat count for salon appointments.
     *
     * @param {Object} info The info object as passed from FullCalendar
     *
     * @return {Object|null} Return a jQuery element or null for nothing.
     */
    function renderCustomContent(info) {
        const data = info.event.extendedProps.data;

        if (!data || !data.pet) {
            return null;
        }

        const pet = data.pet;
        const parts = [];

        if (pet.name) {
            let petText = pet.name;
            if (pet.breed) petText += ' (' + pet.breed + ')';
            parts.push(petText);
        }

        if (pet.size) {
            const sizeLabels = {small: 'Small', medium: 'Medium', large: 'Large'};
            parts.push(sizeLabels[pet.size] || pet.size);
        }

        const seats = data.seats_required || 1;
        parts.push(seats + (seats === 1 ? ' seat' : ' seats'));

        return $('<div/>', {
            'html': [
                $('<strong/>', {
                    'class': 'd-inline-block me-2',
                    'text': 'Dog',
                }),
                $('<span/>', {
                    'text': parts.join(' — '),
                }),
                $('<br/>'),
            ],
        });
    }

    return {
        renderPhoneIcon,
        renderMapIcon,
        renderMailIcon,
        renderCustomContent,
    };
})();
