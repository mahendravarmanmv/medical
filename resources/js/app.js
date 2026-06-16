import 'bootstrap';
import jquery from 'jquery';
window.$ = window.jQuery = jquery;

$(document).ready(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $(document).on('click', '.open-product-modal', function (e) {
        e.preventDefault();
        let productId = $(this).data('id');
        let modal = $('#productModal');
        if (!productId || isNaN(productId)) return;

        modal.modal('show');

        $.ajax({
            url: '/products/' + productId + '/details',
            method: 'GET',
            dataType: 'json',
            success: function (product) {
                $('#modalProductTitle').text(product.title);
                $('#modalProductDescription').text(product.description);
                $('#modalProductPrice').text('₹' + parseFloat(product.price).toLocaleString('en-IN', { minimumFractionDigits: 2 }));
                $('#modalProductImage').attr('src', product.image_url);
                modal.find('.add-to-cart-btn').attr('data-id', product.id);
            }
        });
    });
    /**
     * Intercept Offcanvas Visibility state and dynamically load active basket parameters
     */
    $('#cartDrawer').on('show.bs.offcanvas', function () {
        let container = $('#cart-drawer-body');
        
        $.ajax({
            url: '/cart/view',
            method: 'GET',
            dataType: 'html', // Expects clean precompiled layout partial fragments back from the server
            success: function (htmlContent) {
                container.html(htmlContent);
            },
            error: function () {
                container.html('<p class="text-center text-danger py-4">Failed to load basket synchronization tokens safely.</p>');
            }
        });
    });

    /**
     * Handle item deletion inside offcanvas rows smoothly
     */
    $(document).on('click', '.remove-cart-item-btn', function (e) {
        e.preventDefault();
        let button = $(this);
        let productId = button.data('id');
        let offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('cartDrawer'));

        if (!productId || isNaN(productId)) return;

        $.ajax({
            url: '/cart/remove',
            method: 'POST',
            data: { product_id: productId },
            dataType: 'json',
            success: function (response) {
                // Update global tracking element count bubble metrics
                $('#global-cart-count').text(response.cart_count);
                
                // Re-trigger offcanvas show sequence to automatically redraw updated layout content cleanly
                $('#cartDrawer').trigger('show.bs.offcanvas');
            }
        });
    });
});