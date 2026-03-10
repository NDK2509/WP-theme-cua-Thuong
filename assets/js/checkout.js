/**
 * Checkout Page JS
 * - Syncs the sticky bottom bar's payment label with the selected payment method
 */
(function () {
    'use strict';

    function initCheckout() {
        var stickyLabel = document.getElementById('sticky-payment-label');
        if (!stickyLabel) return;

        function updateStickyLabel() {
            var checked = document.querySelector('#payment ul.payment_methods input[type="radio"]:checked');
            if (checked) {
                var label = document.querySelector('label[for="' + checked.id + '"]');
                if (label) {
                    // Get text content only (no nested icons/imgs)
                    var clone = label.cloneNode(true);
                    clone.querySelectorAll('img, .payment_method_icons').forEach(function (el) { el.remove(); });
                    stickyLabel.textContent = clone.textContent.trim();
                }
            }
        }

        // Listen for WooCommerce payment method change events
        document.body.addEventListener('change', function (e) {
            if (e.target && e.target.name === 'payment_method') {
                updateStickyLabel();
            }
        });

        // Also update on WooCommerce's updated_checkout event (AJAX re-render)
        if (window.jQuery) {
            jQuery(document.body).on('updated_checkout', updateStickyLabel);
        }

        // Initial call
        updateStickyLabel();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCheckout);
    } else {
        initCheckout();
    }
})();
