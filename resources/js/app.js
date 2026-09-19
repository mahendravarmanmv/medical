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
    // 3. HIGH-PERFORMANCE PRODUCT DETAIL MODAL DRAWER & RENDER ENGINE
    // -------------------------------------------------------------------------
    $(document).on('click', '.open-product-modal', function (e) {
        e.preventDefault();
        
        let productId = $(this).data('id');
        let modalEl = document.getElementById('productModal');
        
        if (!productId || isNaN(productId) || !modalEl) return;

        // Reset inputs and buttons clean to prevent data overlap if the network lags
        $('#modalProductTitle').text('Loading Product Context...');
        $('#modalProductDescription').text('Fetching data securely...');
        let mainAddToCartBtn = $(modalEl).find('.add-to-cart-btn');
        mainAddToCartBtn.attr('data-product-id', productId); // Set an explicit attribute tracker

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
				
				// -------------------------------------------------------------------------
				// OPTIONAL WARRANTY SELECTION
				// -------------------------------------------------------------------------
				let warrantyContainer = document.getElementById('modalWarrantyContainer');

				if (warrantyContainer) {
				warrantyContainer.innerHTML = '';

				let warrantySection = $('.js-warranty-section');

				if (data.warranties && data.warranties.length > 0) {
				warrantySection.removeClass('d-none');

				let warrantyHtml = '';

				data.warranties.forEach((warranty, index) => {
				let isActive = index === 0;

				warrantyHtml += `
				<div class="col-6 col-md-4">
					<div class="border ${isActive ? 'border-primary shadow-sm' : 'border-light-subtle'} rounded-3 p-3 h-100 cursor-pointer warranty-variant-card"
						 data-warranty-id="${warranty.id}"
						 data-price="${warranty.price}">

						<div class="d-flex justify-content-between align-items-center mb-2">
							<span class="fw-semibold small text-dark">
								${warranty.warranty_years} Year${warranty.warranty_years > 1 ? 's' : ''}
							</span>

							${isActive ? '<i class="fas fa-check-circle text-primary"></i>' : ''}
						</div>

						<div class="fw-bold fs-5 ${isActive ? 'text-primary' : 'text-dark'}">
							₹${parseFloat(warranty.price).toLocaleString('en-IN', {
								maximumFractionDigits: 0
							})}
						</div>

					</div>
				</div>
				`;
				});

				warrantyContainer.innerHTML = warrantyHtml;

				// First warranty becomes the default selected warranty.
				let firstWarranty = data.warranties[0];

				$('#modalProductPrice').text(
				'₹' + parseFloat(firstWarranty.price).toLocaleString('en-IN')
				);

				let originalPriceEl = document.getElementById('modalProductOriginalPrice');

				if (originalPriceEl) {
				originalPriceEl.textContent =
				'₹' + Math.round(
					parseFloat(firstWarranty.price) * 1.35
				).toLocaleString('en-IN');
				}

				} else {
				warrantySection.addClass('d-none');
				}
				}

                let qtyInput = document.getElementById('modal-qty');
                if (qtyInput) {
                    qtyInput.value = 1;
                    qtyInput.setAttribute('max', data.stock || 20);
                }

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

        container.html(`
            <div class="d-flex flex-column align-items-center justify-content-center h-100 py-5 w-100 text-center">
                <div class="spinner-border text-primary" role="status"></div>
                <span class="mt-2 text-muted small">Loading your basket items...</span>
            </div>
        `);

        $.ajax({
            url: cleanAppUrl + '/cart/view',
            method: 'GET',
            dataType: 'html',
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

    // -------------------------------------------------------------------------
    // BASKET ITEM ASYNCHRONOUS DELETION SUB-SYSTEM
    // -------------------------------------------------------------------------
    $(document).on('click', '.remove-cart-item-btn', function (e) {
        e.preventDefault();
        
        let button = $(this);
        let cartKey = button.data('id'); 
        
        if (!cartKey) return;

        let appUrl = $('meta[name="app-url"]').attr('content') || '';
        let cleanAppUrl = appUrl.endsWith('/') ? appUrl.slice(0, -1) : appUrl;

        let itemRow = button.closest('.d-flex.align-items-center');
        itemRow.css('opacity', '0.5');
        button.prop('disabled', true);

        $.ajax({
            url: cleanAppUrl + '/cart/remove',
            method: 'POST',
            data: { cart_key: cartKey },
            dataType: 'json',
            success: function (response) {
                $('#global-cart-count').text(response.cart_count);
                $('#cartDrawer').trigger('show.bs.offcanvas');
            },
            error: function () {
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
// DYNAMIC CARD SELECTION ENGINE: CHOOSE WARRANTY INTERACTION
// -------------------------------------------------------------------------
$(document).on('click', '.warranty-variant-card', function () {

    $('.warranty-variant-card')
        .removeClass('border-primary shadow-sm')
        .addClass('border-light-subtle');

    $('.warranty-variant-card .fa-check-circle').remove();

    $(this)
        .removeClass('border-light-subtle')
        .addClass('border-primary shadow-sm');

    $(this)
        .find('.d-flex')
        .append('<i class="fas fa-check-circle text-primary"></i>');

    let selectedPrice = parseFloat($(this).data('price'));

    if (!isNaN(selectedPrice)) {

        $('#modalProductPrice').text(
            '₹' + selectedPrice.toLocaleString('en-IN')
        );

        let originalPriceEl =
            document.getElementById('modalProductOriginalPrice');

        if (originalPriceEl) {
            originalPriceEl.textContent =
                '₹' + Math.round(
                    selectedPrice * 1.35
                ).toLocaleString('en-IN');
        }
    }
});

    // -------------------------------------------------------------------------
    // SHOP BY CATEGORY SIDEBAR INTERACTION
    // -------------------------------------------------------------------------
    $(document).on('click', '.js-category-filter', function (e) {
        e.preventDefault();
        
        $('.js-category-filter').removeClass('active');
        $(this).addClass('active');

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
        // FIX: Prioritize explicit attribute evaluation over internal cached data logs
        let productId = button.attr('data-product-id') || button.data('id');
        let quantity = 1;
        let selectedPackageName = null;
		let selectedWarrantyId = null;

        let isInsideModal = button.closest('#productModal').length > 0;

		if (isInsideModal) {
		quantity = parseInt($('#modal-qty').val()) || 1;

		let activePackageCard =
		$('#modalPackageContainer .package-variant-card.border-primary');

		if (activePackageCard.length > 0) {
		selectedPackageName =
			activePackageCard.find('.fw-semibold').text().trim();
		}

		let activeWarrantyCard =
		$('#modalWarrantyContainer .warranty-variant-card.border-primary');

		if (activeWarrantyCard.length > 0) {
		selectedWarrantyId =
			activeWarrantyCard.data('warranty-id');
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
			package: selectedPackageName,
			warranty_id: selectedWarrantyId
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

                // -------------------------------------------------------------
                // NEW: PREMIUM LIVE TOAST TRIGGER SYSTEM
                // -------------------------------------------------------------
                let toastEl = document.getElementById('cartToast');
                if (toastEl) {
                    // Update message box text string context dynamically if returned
                    if(response.message) {
                        $('#cartToastMessage').text(response.message);
                    }
                    
                    // Instantiate and stream the Bootstrap Toast module down to the UI layout
                    let liveToast = bootstrap.Toast.getInstance(toastEl) || new bootstrap.Toast(toastEl);
                    liveToast.show();
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

                    $('#invoice-subtotal').text('₹' + summary.subtotal);
					if (summary.tax_enabled) {

					$('#invoice-tax-label').text(
					summary.tax_name + ' (' + parseFloat(summary.tax_rate) + '%)'
					);

					$('#invoice-gst').text('₹' + summary.gst);

					$('#invoice-tax-row').removeClass('d-none');

					} else {

					$('#invoice-tax-row').addClass('d-none');

					$('#invoice-gst').text('₹0.00');
					}
                    $('#invoice-delivery').text(parseFloat(summary.delivery) === 0 ? 'FREE' : '₹' + summary.delivery);
                    $('#invoice-installation').text('₹' + summary.installation);
                    $('#invoice-discount').text('- ₹' + summary.discounts);
                    $('#invoice-total').text('₹' + summary.final_payable);                    
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
	
	
		// -------------------------------------------------------------------------
		// CHECKOUT - CASH ON DELIVERY ORDER SUBMISSION
		// -------------------------------------------------------------------------
		$(document).on('submit', '#checkoutForm', function (e) {
		e.preventDefault();

		let form = $(this);
		let button = $('#placeOrderBtn');
		let errorBox = $('#checkoutError');

		if (button.prop('disabled')) {
		return;
		}

		/*
		* Clear previous error.
		*/
		errorBox
		.addClass('d-none')
		.text('');

		/*
		* Browser-level validation.
		*/
		if (!form[0].checkValidity()) {
		form[0].reportValidity();
		return;
		}

		/*
		* Disable button while order is being processed.
		*/
		button.prop('disabled', true);

		button.find('.place-order-text').addClass('d-none');
		button.find('.place-order-loading').removeClass('d-none');

		let appUrl =
		$('meta[name="app-url"]').attr('content') || '';

		let cleanAppUrl =
		appUrl.endsWith('/')
			? appUrl.slice(0, -1)
			: appUrl;

		/*
		* Serialize complete checkout form.
		*/
		let formData = form.serialize();

		$.ajax({
		url: cleanAppUrl + '/checkout/place-order',

		method: 'POST',

		data: formData,

		dataType: 'json',

		success: function (response) {

			if (response.success) {

				/*
				 * Redirect to order success page.
				 */
				if (response.redirect) {
					window.location.href =
						response.redirect;

					return;
				}

				/*
				 * Fallback.
				 */
				alert(
					response.message ||
					'Your order has been placed successfully.'
				);
			}
		},

		error: function (xhr) {

			let message =
				'Unable to place your order. Please try again.';

			/*
			 * Laravel validation response.
			 */
			if (
				xhr.responseJSON &&
				xhr.responseJSON.errors
			) {

				let errors =
					xhr.responseJSON.errors;

				let firstError = null;

				Object.keys(errors).some(function (key) {

					if (
						errors[key] &&
						errors[key].length > 0
					) {
						firstError =
							errors[key][0];

						return true;
					}

					return false;
				});

				if (firstError) {
					message = firstError;
				}

			} else if (
				xhr.responseJSON &&
				xhr.responseJSON.message
			) {

				message =
					xhr.responseJSON.message;
			}

			errorBox
				.removeClass('d-none')
				.text(message);

			/*
			 * Scroll user to the error.
			 */
			$('html, body').animate({
				scrollTop:
					errorBox.offset().top - 100
			}, 300);
		},

		complete: function () {

			button.prop('disabled', false);

			button
				.find('.place-order-text')
				.removeClass('d-none');

			button
				.find('.place-order-loading')
				.addClass('d-none');
		}
		});
		});

    // -------------------------------------------------------------------------
    // 7. UNIFIED QUANTITY CHANGE LISTENER (DRAWER & MODAL COMBINED)
    // -------------------------------------------------------------------------
    // 1. Drawer Counter Module (Triggers AJAX updates directly)
    $(document).on('click', '.change-drawer-qty-btn', function (e) {
        e.preventDefault();
        let button = $(this);
        let cartKey = button.data('id');
        let action = button.data('action');
        
        if (!cartKey) return;

        let appUrl = $('meta[name="app-url"]').attr('content') || '';
        let cleanAppUrl = appUrl.endsWith('/') ? appUrl.slice(0, -1) : appUrl;

        let groupWrapper = button.closest('.input-group');
        groupWrapper.css('opacity', '0.6');
        groupWrapper.find('button').prop('disabled', true);

        $.ajax({
            url: cleanAppUrl + '/cart/update-quantity',
            method: 'POST',
            data: { cart_key: cartKey, action: action },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#global-cart-count').text(response.cart_count);
                    $('#cartDrawer').trigger('show.bs.offcanvas');
                }
            },
            error: function () {
                groupWrapper.css('opacity', '1');
                groupWrapper.find('button').prop('disabled', false);
                alert('Fulfillment threshold error. Failed to alter item quantity securely.');
            }
        });
    });

    // 2. Modal Counter Module (Updates local UI values prior to 'Add to Cart' click)
    $(document).on('click', '.change-modal-qty-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();

        let button = $(this);
        let action = button.data('action');
        let qtyInput = $('#modal-qty');
        
        if (qtyInput.length > 0) {
            let currentVal = parseInt(qtyInput.val()) || 1;
            let maxLimit = parseInt(qtyInput.attr('max')) || 20;

            if (action === 'increase') {
                if (currentVal < maxLimit) {
                    qtyInput.val(currentVal + 1).trigger('change');
                }
            } else if (action === 'decrease') {
                if (currentVal > 1) {
                    qtyInput.val(currentVal - 1).trigger('change');
                }
            }
        }
    });
});