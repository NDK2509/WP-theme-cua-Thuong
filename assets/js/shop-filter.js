jQuery(document).ready(function ($) {
    var $filterForm = $('#censkills-product-filter');
    var $productContainer = $('ul.products'); // We will replace the inner products ul

    if ($filterForm.length === 0) {
        return;
    }

    $filterForm.on('change', 'input', function (e) {
        // Collect form data
        var formData = $filterForm.serialize();

        // Visual feedback that it's loading
        $productContainer.css('opacity', '0.5');
        $productContainer.css('pointer-events', 'none');

        $.ajax({
            url: censkills_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response.success) {
                    $productContainer.html(response.data.html);
                } else {
                    console.error('Error fetching products:', response);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
            },
            complete: function () {
                $productContainer.css('opacity', '1');
                $productContainer.css('pointer-events', 'auto');

                // Trigger an event so scripts like Lazy Load can re-init
                $(document.body).trigger('post-load');
            }
        });
    });
});
