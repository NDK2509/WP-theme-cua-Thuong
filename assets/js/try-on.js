document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.censkills-try-on-container');
    if (!container) return;

    const uploadInput = document.getElementById('try-on-upload');
    const previewArea = document.getElementById('try-on-preview-area');
    const loadingArea = document.getElementById('try-on-loading');
    const canvas = document.getElementById('try-on-canvas');
    const ctx = canvas.getContext('2d');
    const renderBtn = document.getElementById('try-on-render');
    const resetBtn = document.getElementById('try-on-reset');
    const uploadWrapper = document.querySelector('.try-on-upload-wrapper');

    let userImg = null;
    let productImg = null;
    const productImgUrl = container.dataset.productImg;

    // Preload product image
    const preloadProduct = new Image();
    preloadProduct.crossOrigin = "anonymous";
    preloadProduct.onload = function() {
        productImg = preloadProduct;
    };
    preloadProduct.src = productImgUrl;

    uploadInput.addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(event) {
                userImg = new Image();
                userImg.onload = function() {
                    showPreview();
                };
                userImg.src = event.target.result;
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    function showPreview() {
        uploadWrapper.classList.add('hidden');
        previewArea.classList.remove('hidden');
        renderCanvas('preview');
    }

    resetBtn.addEventListener('click', function() {
        previewArea.classList.add('hidden');
        uploadWrapper.classList.remove('hidden');
        uploadInput.value = '';
        userImg = null;
    });

    renderBtn.addEventListener('click', function() {
        if (!userImg || !productImgUrl) return;

        loadingArea.classList.remove('hidden');
        previewArea.classList.add('hidden');
        
        // Call Backend AI Handler
        const formData = new FormData();
        formData.append('action', 'censkills_try_on_ai');
        formData.append('user_image', userImg.src);
        formData.append('product_image', productImgUrl);

        const ajaxUrl = (typeof censkills_try_on_params !== 'undefined') ? censkills_try_on_params.ajax_url : (typeof censkills_ajax !== 'undefined' ? censkills_ajax.ajax_url : '/wp-admin/admin-ajax.php');

        fetch(ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            loadingArea.classList.add('hidden');
            previewArea.classList.remove('hidden');

            if (data.success && data.data.image) {
                const aiImg = new Image();
                aiImg.onload = function() {
                    canvas.width = aiImg.width;
                    canvas.height = aiImg.height;
                    ctx.drawImage(aiImg, 0, 0);
                };
                aiImg.src = data.data.image;
            } else {
                alert('Error: ' + (data.data.message || 'AI failed to generate image.'));
                // Fallback to local rendering
                renderCanvas('final');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            loadingArea.classList.add('hidden');
            previewArea.classList.remove('hidden');
            alert('Something went wrong. Please try again.');
            renderCanvas('final');
        });
    });

    function renderCanvas(mode) {
        if (!userImg) return;

        // Set canvas size based on user image aspect ratio
        const maxWidth = 800;
        const scale = maxWidth / userImg.width;
        canvas.width = maxWidth;
        canvas.height = userImg.height * scale;

        // Draw User Image as base
        ctx.drawImage(userImg, 0, 0, canvas.width, canvas.height);

        if (mode === 'final' && productImg) {
            // Draw product image over user image
            // We'll place it in the center/bottom area as a simple overlay
            // In a real app, this would involve background removal and better positioning
            const pWidth = canvas.width * 0.4;
            const pHeight = (productImg.height / productImg.width) * pWidth;
            const pX = (canvas.width - pWidth) / 2;
            const pY = (canvas.height - pHeight) / 2;

            // Optional: Draw a subtle glow/shadow for the product
            ctx.shadowColor = "rgba(0,0,0,0.2)";
            ctx.shadowBlur = 20;
            ctx.drawImage(productImg, pX, pY, pWidth, pHeight);
            ctx.shadowBlur = 0;
        }
    }
});
