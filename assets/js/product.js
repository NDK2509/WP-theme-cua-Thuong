/**
 * Single Product Page JS
 * - Adds minus/plus stepper buttons around the quantity input
 */
(function () {
    'use strict';

    function initQuantityStepper() {
        document.querySelectorAll('.quantity:not(.censkills-stepper-done)').forEach(function (wrapper) {
            var input = wrapper.querySelector('input.qty');
            if (!input) return;

            wrapper.classList.add('censkills-stepper-done');
            wrapper.classList.add('censkills-qty-wrapper');

            // Create minus button
            var minus = document.createElement('button');
            minus.type = 'button';
            minus.className = 'censkills-qty-btn censkills-qty-minus';
            minus.setAttribute('aria-label', 'Decrease quantity');
            minus.innerHTML = '&minus;';

            // Create plus button
            var plus = document.createElement('button');
            plus.type = 'button';
            plus.className = 'censkills-qty-btn censkills-qty-plus';
            plus.setAttribute('aria-label', 'Increase quantity');
            plus.innerHTML = '&plus;';

            wrapper.insertBefore(minus, input);
            wrapper.appendChild(plus);

            // Hide the native number spinners
            input.classList.add('censkills-qty-input');

            minus.addEventListener('click', function () {
                var current = parseInt(input.value, 10) || 1;
                var min = parseInt(input.getAttribute('min'), 10) || 1;
                if (current > min) {
                    input.value = current - 1;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });

            plus.addEventListener('click', function () {
                var current = parseInt(input.value, 10) || 1;
                var max = parseInt(input.getAttribute('max'), 10) || Infinity;
                if (current < max) {
                    input.value = current + 1;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        });
    }

    // Run on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initQuantityStepper);
    } else {
        initQuantityStepper();
    }

    // Re-run after WooCommerce AJAX updates (cart page)
    if (window.jQuery) {
        jQuery(document.body).on('updated_cart_totals wc-blocks-cart-update-customer-data', initQuantityStepper);
    }
})();
