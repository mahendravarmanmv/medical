import jquery from 'jquery';
import * as bootstrap from 'bootstrap'; 

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
        let eligibilityInstance = new bootstrap.Modal(eligibilityModalEl);
        eligibilityInstance.show();

        $('#pincodeCheckForm').on('submit', function (e) {
            e.preventDefault();
            let pincode = $('#deliveryPincodeInput').value ? $('#deliveryPincodeInput').value.trim() : $('#deliveryPincodeInput').val().trim();
            let feedback = $('#pincodeFeedback');

            if (pincode.length === 6 && !isNaN(pincode)) {
                feedback.addClass('d-none');
                eligibilityInstance.hide();
            } else {
                feedback.text("Please provide a valid 6-digit region pincode.").removeClass('d-none');
            }
        });
    }
    // -------------------------------------------------------------------------
    // 3. HIGH-PERFORMANCE PRODUCT DETAIL MODAL DRAWER & RENDER ENGINE
    // -------------------------------------------------------------------------
    $(document).on('click', '.open-product-modal', function (e) {
        e.preventDefault();
        
        let productId = $(this).data('id');
        let modalEl = document.getElementById('productModal');
        
        if (!productId || isNaN(productId) || !modalEl) return;

        // Visual cue fallback placeholders while performing fast background lookup
        $('#modalProductTitle').text('Loading Product Context...');
        $('#modalProductDescription').text('Fetching data securely...');

        // Initialize and fire the Bootstrap 5 Modal instance natively
        let productModalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        productModalInstance.show();

        // High-speed AJAX query using your fast controller JSON endpoint
        $.ajax({
            url: '/products/' + productId + '/details',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                // A. Hydrate core text layouts safely
                $('#modalProductTitle').text(data.title);
                $('#modalProductDescription').text(data.description);
                $('#modalProductPrice').text('₹' + parseFloat(data.price).toLocaleString('en-IN'));
                
                let stdPack = document.getElementById('modalStandardPackPrice');
                if (stdPack) stdPack.textContent = '₹' + parseFloat(data.price).toLocaleString('en-IN');

                // B. Process strike-through retail margins dynamically
                let originalPriceEl = document.getElementById('modalProductOriginalPrice');
                let discountEl = document.getElementById('modalProductDiscount');
                if (originalPriceEl) {
                    let originalPrice = data.price * 1.35;
                    originalPriceEl.textContent = '₹' + Math.round(originalPrice).toLocaleString('en-IN');
                }
                if (discountEl) discountEl.textContent = '(26.91% off)';

                // C. Swap main WebP image location cleanly
                if (data.image_url) {
                    let fullImageUrl = data.image_url.startsWith('http') ? data.image_url : '/' + data.image_url;
                    $('#modalProductImage').attr('src', fullImageUrl);
                }

                // D. HYDRATE MULTIPLE GALLERY THUMBNAILS DYNAMICALLY
                let thumbTray = document.getElementById('modalThumbnailsTray');
                if (thumbTray) {
                    thumbTray.innerHTML = ''; 

                    // Seed the primary main display image as the first selectable thumbnail frame
                    if (data.image_url) {
                        thumbTray.innerHTML += `
                            <div class="border border-primary border-2 rounded p-1 bg-white cursor-pointer flex-shrink-0 js-gallery-thumb" style="width: 70px; height: 70px;">
                                <img src="${data.image_url}" class="img-fluid object-fit-contain h-100 w-100" alt="Main View">
                            </div>
                        `;
                    }

                    // Loop append remaining secondary gallery views if they exist
                    if (data.gallery && data.gallery.length > 0) {
                        data.gallery.forEach(imgUrl => {
                            thumbTray.innerHTML += `
                                <div class="border border-light-subtle rounded p-1 bg-white cursor-pointer flex-shrink-0 js-gallery-thumb" style="width: 70px; height: 70px;">
                                    <img src="${imgUrl}" class="img-fluid object-fit-contain h-100 w-100" alt="Gallery View">
                                </div>
                            `;
                        });
                    }
                }

                // -------------------------------------------------------------
                // E. HYDRATE DYNAMIC PACKAGES/VARIATIONS CARDS
                // -------------------------------------------------------------
                let packageContainer = document.getElementById('modalPackageContainer');
                if (packageContainer) {
                    packageContainer.innerHTML = ''; 
                    
                    // Find the outermost structural section wrapper to clear empty space completely
                    let packageSection = $('.js-package-section'); 

                    if (data.packages && data.packages.length > 0) {
                        // 1. Re-display the entire block structure natively if dynamic packages exist
                        packageSection.removeClass('d-none');
                        
                        let htmlContent = '';
                        data.packages.forEach((pkg, index) => {
                            let isActive = index === 0;
                            htmlContent += `
                                <div class="col-6">
                                    <div class="border ${isActive ? 'border-primary shadow-sm' : 'border-light-subtle'} rounded-3 p-3 h-100 cursor-pointer package-variant-card" data-price="${pkg.price}">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-semibold small text-dark">${pkg.package_name}</span>
                                            ${isActive ? '<i class="fas fa-check-circle text-primary"></i>' : ''}
                                        </div>
                                        <div class="fw-bold fs-5 ${isActive ? 'text-primary' : 'text-dark'}">
                                            ₹${parseFloat(pkg.price).toLocaleString('en-IN', { maximumFractionDigits: 0 })}
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        packageContainer.innerHTML = htmlContent;
                    } else {
                        // 2. Hide the divider, title, and spacing cleanly if no packages are configured
                        packageSection.addClass('d-none');
                    }
                }

                // F. Configure operational inputs and attributes
                let qtyInput = document.getElementById('modal-qty');
                if (qtyInput) {
                    qtyInput.value = 1;
                    qtyInput.setAttribute('max', data.stock || 20);
                }
                
                $(modalEl).find('.add-to-cart-btn').attr('data-id', data.id);

                // G. Rebuild dynamic relational dealer table grid mapping
                let dealerListContainer = document.getElementById('dealerListContainer');
                if (dealerListContainer) {
                    dealerListContainer.innerHTML = '';

                    if (!data.dealers || data.dealers.length === 0) {
                        dealerListContainer.innerHTML = `
                        <div class="p-3 text-center text-muted fst-italic bg-light rounded-3">
                            No other dealers are handling this item currently.
                        </div>`;
                    } else {
                        let tableRows = data.dealers.map(dealer => `
                        <tr class="border-bottom border-light-subtle">
                            <td class="fw-semibold text-dark py-3">${dealer.dealer_name}</td>
                            <td class="text-muted py-3">${data.title}</td>
                            <td class="text-success fw-bold py-3 text-end">₹${parseFloat(dealer.price).toLocaleString('en-IN')}</td>
                        </tr>
                        `).join('');

                        dealerListContainer.innerHTML = `
                        <div class="table-responsive bg-white rounded-3 p-2 border border-light-subtle">
                            <table class="table table-borderless align-middle mb-0 small">
                                <thead>
                                    <tr class="border-bottom border-light-subtle text-muted">
                                        <th class="py-2">Dealer Name</th>
                                        <th class="py-2">Product Name</th>
                                        <th class="py-2 text-end">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${tableRows}
                                </tbody>
                            </table>
                        </div>`;
                    }
                }
            },
            error: function () {
                $('#modalProductTitle').text('Failed to load details.');
            }
        });

        // Auto close relational expansion trays cleanly on fresh clicks
        let collapseElement = document.getElementById('dealersCollapse');
        if (collapseElement && collapseElement.classList.contains('show')) {
            let bsCollapse = bootstrap.Collapse.getInstance(collapseElement);
            bsCollapse?.hide();
        }
    });
    // -------------------------------------------------------------------------
    // INTERACTIVE LIVE EVENT DELEGATION: SWAP DISPLAY IMAGE ON THUMBNAIL CLICK
    // -------------------------------------------------------------------------
    $(document).on('click', '.js-gallery-thumb', function() {
        $('.js-gallery-thumb').removeClass('border-primary border-2').addClass('border-light-subtle');
        $(this).removeClass('border-light-subtle').addClass('border-primary border-2');
        
        let targetSrc = $(this).find('img').attr('src');
        $('#modalProductImage').attr('src', targetSrc);
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
                $('#global-cart-count').text(response.cart_count);
                $('#cartDrawer').trigger('show.bs.offcanvas');
            }
        });
    });
    // -------------------------------------------------------------------------
    // 5. URL QUERY PARAMETER AUTH TAB MANAGER
    // -------------------------------------------------------------------------
    const urlParams = new URLSearchParams(window.location.search);
    const targetTab = urlParams.get('tab');

    if (targetTab === 'signup') {
        let signUpTabEl = document.getElementById('register-tab');
        if (signUpTabEl) {
            let tabInstance = new bootstrap.Tab(signUpTabEl);
            tabInstance.show();
        }
    }
    // -------------------------------------------------------------------------
    // DYNAMIC CARD SELECTION ENGINE: CHOOSE PACKAGE INTERACTION
    // -------------------------------------------------------------------------
    $(document).on('click', '.package-variant-card', function () {
        // 1. Reset all cards in the container to their default unselected style
        $('.package-variant-card')
            .removeClass('border-primary shadow-sm')
            .addClass('border-light-subtle');
            
        // Remove existing checkmark icons cleanly
        $('.package-variant-card .fa-check-circle').remove();

        // 2. Highlight the newly clicked card natively using Bootstrap states
        $(this)
            .removeClass('border-light-subtle')
            .addClass('border-primary shadow-sm');

        // 3. Inject the active visual checkmark icon dynamically into the title row
        $(this).find('.d-flex').append('<i class="fas fa-check-circle text-primary"></i>');

        // 4. Update the main header viewport price instantly to match the selection
        let selectedPrice = $(this).data('price');
        if (selectedPrice) {
            $('#modalProductPrice').text('₹' + parseFloat(selectedPrice).toLocaleString('en-IN'));
            
            // Optional: Recalculate the original retail crossed strike-through price too
            let originalPriceEl = document.getElementById('modalProductOriginalPrice');
            if (originalPriceEl) {
                originalPriceEl.textContent = '₹' + Math.round(selectedPrice * 1.35).toLocaleString('en-IN');
            }
        }
    });
});