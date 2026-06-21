import jquery from 'jquery';
import * as bootstrap from 'bootstrap'; // Expose the package components explicitly

// Bind jQuery and Bootstrap to the global window landscape cleanly
window.$ = window.jQuery = jquery;
window.bootstrap = bootstrap;

$(document).ready(function () {
    // -------------------------------------------------------------------------
    // 1. GLOBAL LAYOUT SETUP
    // -------------------------------------------------------------------------
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // -------------------------------------------------------------------------
    // 2. INITIAL DELIVERY ELIGIBILITY MODAL GATEKEEPER
    // -------------------------------------------------------------------------
    let eligibilityModalEl = document.getElementById('eligibilityModal');
    if (eligibilityModalEl) {
        // Initialize and show the gatekeeper modal using our exposed bundle instance
        let eligibilityInstance = new bootstrap.Modal(eligibilityModalEl);
        eligibilityInstance.show();

        // Form interception for the eligibility configuration
        $('#pincodeCheckForm').on('submit', function (e) {
            e.preventDefault();
            let pincode = $('#deliveryPincodeInput').value ? $('#deliveryPincodeInput').value.trim() : $('#deliveryPincodeInput').val().trim();
            let feedback = $('#pincodeFeedback');

            if (pincode.length === 6 && !isNaN(pincode)) {
                feedback.addClass('d-none');
                eligibilityInstance.hide();
                
                // TODO: You can drop an AJAX call here later to dynamically refresh 
                // nearby product cards on the dashboard template grid!
            } else {
                feedback.text("Please provide a valid 6-digit region pincode.").removeClass('d-none');
            }
        });
    }

    // -------------------------------------------------------------------------
    // 3. PRODUCT DETAIL MODAL DRAWER
    // -------------------------------------------------------------------------
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

    // -------------------------------------------------------------------------
    // 4. OFFCANVAS SHOPPING BASKET SYSTEM
    // -------------------------------------------------------------------------
    $('#cartDrawer').on('show.bs.offcanvas', function () {
        let container = $('#cart-drawer-body');
        
        $.ajax({
            url: '/cart/view',
            method: 'GET',
            dataType: 'html',
            success: function (htmlContent) {
                container.html(htmlContent);
            },
            error: function () {
                container.html('<p class="text-center text-danger py-4">Failed to load basket synchronization tokens safely.</p>');
            }
        });
    });

    // Handle item deletion inside offcanvas rows smoothly
    $(document).on('click', '.remove-cart-item-btn', function (e) {
        e.preventDefault();
        let button = $(this);
        let productId = button.data('id');

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

    // -------------------------------------------------------------------------
    // 5. URL QUERY PARAMETER AUTH TAB MANAGER
    // -------------------------------------------------------------------------
    // Detect if ?tab=signup is present in the current URL path bounds
    const urlParams = new URLSearchParams(window.location.search);
    const targetTab = urlParams.get('tab');

    if (targetTab === 'signup') {
        let signUpTabEl = document.getElementById('register-tab');
        if (signUpTabEl) {
            // Use Bootstrap's native tab constructor to switch panels cleanly
            let tabInstance = new bootstrap.Tab(signUpTabEl);
            tabInstance.show();
        }
    }
});