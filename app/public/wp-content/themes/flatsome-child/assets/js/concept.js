(() => {
 'use strict';
 // No analytics SDK, identifiers, health details or form values are sent anywhere.
 // Privacy review and explicit clinic approval are required before adding tracking.
 const bar = document.querySelector('.tfd-mobile-actions__item--book');
 if (bar && document.getElementById('appointment-form')) bar.setAttribute('href','#appointment-form');
 const panel = document.querySelector('[data-booking-reason]');
 const reason = panel?.querySelector('select[name="visit-reason"]');
 if(reason && [...reason.options].some(option => option.value === panel.dataset.bookingReason)) {
   reason.value = panel.dataset.bookingReason;
 }
})();
