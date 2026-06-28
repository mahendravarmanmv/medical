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
    let isLoggedIn = $('meta[name="auth-check"]').attr('content') === '1';
    let localPincodeSet = localStorage.getItem('user_delivery_pincode');

    if (eligibilityModalEl) {
        let eligibilityInstance = new bootstrap.Modal(eligibilityModalEl);

        // Rule 5: ONLY trigger the popup if the user is a guest AND hasn't entered a pincode yet this session
        if (!isLoggedIn && !localPincodeSet) {
            eligibilityInstance.show();

            // Rule 3: Catch if the user forces the modal closed without verifying a code
            $(eligibilityModalEl).on('hidden.bs.modal', function () {
                if (!localStorage.getItem('user_delivery_pincode')) {
                    // Force system fallback state automatically to default 500090
                    applyGlobalLocationSync('500090');
                }
            });
        }

        // Intercept form submissions securely
        $('#pincodeCheckForm').on('submit', function (e) {
            e.preventDefault();
            let pincodeInput = $('#deliveryPincodeInput');
            let pincode = pincodeInput.val() ? pincodeInput.val().trim() : '';
            let feedback = $('#pincodeFeedback');

            if (pincode.length === 6 && !isNaN(pincode)) {
                feedback.addClass('d-none');
                eligibilityInstance.hide();
                applyGlobalLocationSync(pincode);
            } else {
                feedback.text("Please provide a valid 6-digit region pincode.").removeClass('d-none');
            }
        });
    }

    /**
     * AJAX endpoint sync processing core handler
     */
    function applyGlobalLocationSync(pincode) {
        let appUrl = $('meta[name="app-url"]').attr('content') || '';
        let cleanAppUrl = appUrl.endsWith('/') ? appUrl.slice(0, -1) : appUrl;

        $.ajax({
            url: cleanAppUrl + '/location/sync',
            method: 'POST',
            data: { pincode: pincode },
            dataType: 'json',
            success: function(response) {
                localStorage.setItem('user_delivery_pincode', pincode);
                console.log("Location successfully tracked:", response.pincode);
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

        $('#modalProductTitle').text('Loading Product Context...');
        $('#modalProductDescription').text('Fetching data securely...');

        let productModalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        productModalInstance.show();

        $.ajax({
            url: '/products/' + productId + '/details',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#modalProductTitle').text(data.title);
                $('#modalProductDescription').text(data.description);
                $('#modalProductPrice').text('₹' + parseFloat(data.price).toLocaleString('en-IN'));
                
                let stdPack = document.getElementById('modalStandardPackPrice');
                if (stdPack) stdPack.textContent = '₹' + parseFloat(data.price).toLocaleString('en-IN');

                let originalPriceEl = document.getElementById('modalProductOriginalPrice');
                let discountEl = document.getElementById('modalProductDiscount');
                if (originalPriceEl) {
                    let originalPrice = data.price * 1.35;
                    originalPriceEl.textContent = '₹' + Math.round(originalPrice).toLocaleString('en-IN');
                }
                if (discountEl) discountEl.textContent = '(26.91% off)';

                if (data.image_url) {
                    let fullImageUrl = data.image_url.startsWith('http') ? data.image_url : '/' + data.image_url;
                    $('#modalProductImage').attr('src', fullImageUrl);
                }

                let thumbTray = document.getElementById('modalThumbnailsTray');
                if (thumbTray) {
                    thumbTray.innerHTML = ''; 

                    if (data.image_url) {
                        thumbTray.innerHTML += `
                            <div class="border border-primary border-2 rounded p-1 bg-white cursor-pointer flex-shrink-0 js-gallery-thumb" style="width: 70px; height: 70px;">
                                <img src="${data.image_url}" class="img-fluid object-fit-contain h-100 w-100" alt="Main View">
                            </div>
                        `;
                    }

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

                let packageContainer = document.getElementById('modalPackageContainer');
                if (packageContainer) {
                    packageContainer.innerHTML = ''; 
                    let packageSection = $('.js-package-section'); 

                    if (data.packages && data.packages.length > 0) {
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
                        packageSection.addClass('d-none');
                    }
                }

                let qtyInput = document.getElementById('modal-qty');
                if (qtyInput) {
                    qtyInput.value = 1;
                    qtyInput.setAttribute('max', data.stock || 20);
                }
                
                $(modalEl).find('.add-to-cart-btn').attr('data-id', data.id);

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

        let collapseElement = document.getElementById('dealersCollapse');
        if (collapseElement && collapseElement.classList.contains('show')) {
            let bsCollapse = bootstrap.Collapse.getInstance(collapseElement);
            bsCollapse?.hide();
        }
    });

    // -------------------------------------------------------------------------
    // SWAP DISPLAY IMAGE ON THUMBNAIL CLICK
    // -------------------------------------------------------------------------
    $(document).on('click', '.js-gallery-thumb', function() {
        $('.js-gallery-thumb').removeClass('border-primary border-2').addClass('border-light-subtle');
        $(this).removeClass('border-light-subtle').addClass('border-primary border-2');
        
        let targetSrc = $(this).find('img').attr('src');
        $('#modalProductImage').attr('src', targetSrc);
    });

    // -------------------------------------------------------------------------
    // 4. OFFCANVAS SHOPPING BASKET SYSTEM (HTML UNIFIED CONFIG)
    // -------------------------------------------------------------------------
    $('#cartDrawer').on('show.bs.offcanvas', function () {
        let appUrl = $('meta[name="app-url"]').attr('content') || '';
        let cleanAppUrl = appUrl.endsWith('/') ? appUrl.slice(0, -1) : appUrl;
        let container = $('#cartDrawerContent');

        // Loading spinner animation initialization
        container.html(`
            <div class="d-flex flex-column align-items-center justify-content-center h-100 py-5 w-100 text-center">
                <div class="spinner-border text-primary" role="status"></div>
                <span class="mt-2 text-muted small">Loading your basket items...</span>
            </div>
        `);

        $.ajax({
            url: cleanAppUrl + '/cart/view',
            method: 'GET',
            dataType: 'html', // Expects HTML view partials from backend engine
            success: function (htmlContent) {
                container.html(htmlContent);
            },
            error: function () {
                container.html(`
                    <div class="d-flex flex-column align-items-center justify-content-center h-100 p-4 text-center my-auto w-100">
                        <i class="bi bi-cart-x text-muted mb-3" style="font-size: 3rem;"></i>
                        <h6 class="fw-bold text-dark">Basket Sync Interrupted</h6>
                        <p class="text-muted small">Could not render view components safely. Please refresh the page.</p>
                    </div>
                `);
            }
        });
    });

    // Handle asynchronous item deletions inside the offcanvas drawer layout template
    // -------------------------------------------------------------------------
    // BASKET ITEM ASYNCHRONOUS DELETION SUB-SYSTEM
    // -------------------------------------------------------------------------
    $(document).on('click', '.remove-cart-item-btn', function (e) {
        e.preventDefault();
        
        let button = $(this);
        let cartKey = button.data('id'); // Pulls '1_default' or structural string hash maps
        
        if (!cartKey) return;

        let appUrl = $('meta[name="app-url"]').attr('content') || '';
        let cleanAppUrl = appUrl.endsWith('/') ? appUrl.slice(0, -1) : appUrl;

        // Provide clean visual feedback by fading the target item row out natively
        let itemRow = button.closest('.d-flex.align-items-center');
        itemRow.css('opacity', '0.5');
        button.prop('disabled', true);

        $.ajax({
            url: cleanAppUrl + '/cart/remove',
            method: 'POST',
            data: { cart_key: cartKey },
            dataType: 'json',
            success: function (response) {
                // 1. Update the top right navbar cart counts bubble icon badge matrix
                $('#global-cart-count').text(response.cart_count);
                
                // 2. Retrigger a re-draw on the side panel canvas to compile fresh math tallies
                $('#cartDrawer').trigger('show.bs.offcanvas');
            },
            error: function () {
                // Reset styling if something blocks the deletion pipeline rules
                itemRow.css('opacity', '1');
                button.prop('disabled', false);
                alert('Fulfillment sync boundary error. Failed to remove product securely.');
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
        $('.package-variant-card')
            .removeClass('border-primary shadow-sm')
            .addClass('border-light-subtle');
            
        $('.package-variant-card .fa-check-circle').remove();

        $(this)
            .removeClass('border-light-subtle')
            .addClass('border-primary shadow-sm');

        $(this).find('.d-flex').append('<i class="fas fa-check-circle text-primary"></i>');

        let selectedPrice = $(this).data('price');
        if (selectedPrice) {
            $('#modalProductPrice').text('₹' + parseFloat(selectedPrice).toLocaleString('en-IN'));
            
            let originalPriceEl = document.getElementById('modalProductOriginalPrice');
            if (originalPriceEl) {
                originalPriceEl.textContent = '₹' + Math.round(selectedPrice * 1.35).toLocaleString('en-IN');
            }
        }
    });

    // -------------------------------------------------------------------------
    // SHOP BY CATEGORY SIDEBAR INTERACTION
    // -------------------------------------------------------------------------
    $(document).on('click', '.js-category-filter', function (e) {
        e.preventDefault();
        
        $('.js-category-filter').removeClass('active bg-primary text-white');
        $(this).addClass('active bg-primary text-white');

        let categorySlug = $(this).data('slug') || 'all';
        let appUrl = $('meta[name="app-url"]').attr('content') || '';
        let cleanAppUrl = appUrl.endsWith('/') ? appUrl.slice(0, -1) : appUrl;
        let currentSort = $('#sortOptionSelect').val() || 'latest';

        $('#mainProductGridContainer').html(`
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading Inventory...</span>
                </div>
            </div>
        `);

        $.ajax({
            url: cleanAppUrl + '/',
            method: 'GET',
            data: {
                category_slug: categorySlug,
                sort: currentSort
            },
            success: function (htmlGridPartial) {
                $('#mainProductGridContainer').html(htmlGridPartial);
                
                let savedPincode = localStorage.getItem('user_delivery_pincode');
                if (savedPincode && typeof evaluateExpressDeliveryBadges === 'function') {
                    evaluateExpressDeliveryBadges(savedPincode);
                }
            },
            error: function () {
                $('#mainProductGridContainer').html(`
                    <div class="col-12 text-center py-5">
                        <div class="text-danger fw-semibold">Failed to load filtered inventory items. Please refresh page.</div>
                    </div>
                `);
            }
        });
    });

    // -------------------------------------------------------------------------
    // HIGH-PERFORMANCE CART STATE ADDITION MODULE
    // -------------------------------------------------------------------------
    $(document).on('click', '.add-to-cart-btn', function (e) {
        e.preventDefault();
        
        let button = $(this);
        let productId = button.data('id');
        let quantity = 1;
        let selectedPackageName = null;

        let isInsideModal = button.closest('#productModal').length > 0;

        if (isInsideModal) {
            quantity = parseInt($('#modal-qty').val()) || 1;
            
            let activePackageCard = $('#modalPackageContainer .package-variant-card.border-primary');
            if (activePackageCard.length > 0) {
                selectedPackageName = activePackageCard.find('.fw-semibold').text().trim();
            }
        }

        if (!productId || isNaN(productId)) return;

        let originalHtml = button.html();
        button.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Syncing...
        `);

        let appUrl = $('meta[name="app-url"]').attr('content') || '';
        let cleanAppUrl = appUrl.endsWith('/') ? appUrl.slice(0, -1) : appUrl;

        $.ajax({
            url: cleanAppUrl + '/cart/add',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
                package: selectedPackageName
            },
            dataType: 'json',
            success: function (response) {
                $('#global-cart-count').text(response.cart_count);
                
                if (isInsideModal) {
                    let modalEl = document.getElementById('productModal');
                    if (modalEl) {
                        let instance = bootstrap.Modal.getInstance(modalEl);
                        instance?.hide();
                    }
                }

                $('#cartDrawer').trigger('show.bs.offcanvas');
                button.prop('disabled', false).html(originalHtml);
            },
            error: function () {
                button.prop('disabled', false).html(originalHtml);
                alert('Session authorization boundary expired. Failed to update item basket properties safely.');
            }
        });
    });

    // -------------------------------------------------------------------------
    // HIGH-PRECISION CHECKOUT INVOICE CALCULATION ENGINE
    // -------------------------------------------------------------------------
    function refreshCheckoutInvoiceSummary() {
        let appUrl = $('meta[name="app-url"]').attr('content') || '';
        let cleanAppUrl = appUrl.endsWith('/') ? appUrl.slice(0, -1) : appUrl;
        let isInstallationChecked = $('#toggleInstallationSupport').is(':checked') ? 1 : 0;

        $.ajax({
            url: cleanAppUrl + '/checkout/summary',
            method: 'GET',
            data: { installation_required: isInstallationChecked },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    let summary = response.summary;

                    // Hydrate dynamic invoice receipt text outputs cleanly
                    $('#invoice-subtotal').text('₹' + summary.subtotal);
                    $('#invoice-gst').text('₹' + summary.gst);
                    $('#invoice-delivery').text(parseFloat(summary.delivery) === 0 ? 'FREE' : '₹' + summary.delivery);
                    $('#invoice-installation').text('₹' + summary.installation);
                    $('#invoice-discount').text('- ₹' + summary.discounts);
                    $('#invoice-total').text('₹' + summary.final_payable);

                    // Update regional shipping timeline fields
                    $('#checkout-timeline-indicator').text(summary.delivery_time_string);
                    
                    if (summary.pickup_eligible) {
                        $('#store-pickup-option-box').removeClass('d-none');
                    } else {
                        $('#store-pickup-option-box').addClass('d-none');
                    }
                }
            }
        });
    }

    $(document).on('change', '#toggleInstallationSupport', function() {
        refreshCheckoutInvoiceSummary();
    });

    if ($('#invoice-total').length > 0) {
        refreshCheckoutInvoiceSummary();
    }
});