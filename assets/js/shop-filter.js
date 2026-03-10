jQuery(document).ready(function ($) {
    var $filterForm = $('#censkills-product-filter');

    if ($filterForm.length === 0) {
        return;
    }

    // Resolve the AJAX URL — fall back to a relative path if the localized var is missing
    var ajaxUrl = (typeof censkills_ajax !== 'undefined' && censkills_ajax.ajax_url)
        ? censkills_ajax.ajax_url
        : '/wp-admin/admin-ajax.php';

    // Find the products container dynamically on each request
    function getProductContainer() {
        return $('.censkills-products-container, ul.products').first();
    }

    function runFilter() {
        var $productContainer = getProductContainer();
        var formData = $filterForm.serialize();

        // Visual feedback
        $productContainer.css({ opacity: '0.5', 'pointer-events': 'none' });

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response && response.success) {
                    var $newHtml = $(response.data.html);
                    $productContainer.replaceWith($newHtml);
                } else {
                    console.error('Filter returned error:', response);
                    getProductContainer().css({ opacity: '1', 'pointer-events': 'auto' });
                }
            },
            error: function (xhr, status, error) {
                console.error('Filter AJAX error:', status, error);
                getProductContainer().css({ opacity: '1', 'pointer-events': 'auto' });
            },
            complete: function () {
                getProductContainer().css({ opacity: '1', 'pointer-events': 'auto' });
                $(document.body).trigger('post-load');
            }
        });
    }

    // Fire on any filter input change
    $filterForm.on('change', 'input', function () {
        runFilter();
    });
});
