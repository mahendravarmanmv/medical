<x-layout :title="'SleepWell | Secure Checkout'">
<div class="container py-5">
    <div class="row g-4">
        
        <!-- LEFT COLUMN: SHIPPING DETAILS & PREFERENCES -->
        <div class="col-lg-7">
            <div class="card border border-light-subtle rounded-3 shadow-sm bg-white p-4 mb-4">
                <h4 class="fw-bold text-dark mb-4">Fulfillment Details</h4>
                
                <!-- Pincode Summary Box -->
                <div class="p-3 bg-light rounded-3 d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 text-primary p-2.5 rounded-circle">
                            <i class="fas fa-map-marker-alt fs-5"></i>
                        </div>
                        <div>
                            <div class="small text-muted">Shipping Destination</div>
                            <div class="fw-bold text-dark" id="checkout-active-pincode">
                                PIN Code: {{ session()->get('user_delivery_pincode', '500090') }}
                            </div>
                        </div>
                    </div>
                    <span class="badge bg-success py-2 px-3 fw-bold rounded-pill" id="checkout-timeline-indicator">
                        Calculating...
                    </span>
                </div>

                <!-- Installation Support Selection Toggle -->
                <h5 class="fw-bold text-dark mb-3">Additional Services</h5>
                <div class="form-check form-switch p-0 border rounded-3 p-3 bg-light mb-4">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="pe-3">
                            <label class="form-check-label fw-bold text-dark cursor-pointer d-block" for="toggleInstallationSupport">
                                Add Premium Installation Support
                            </label>
                            <small class="text-muted d-block mt-1">
                                An expert technician will visit your location to unbox, configure, and calibrate your medical equipment safely.
                            </small>
                        </div>
                        <input class="form-check-input ms-0 mt-1 shadow-none cursor-pointer" type="checkbox" id="toggleInstallationSupport" style="width: 2.5em; height: 1.25em;">
                    </div>
                </div>

                <!-- Store Pickup Option Row -->
                <div class="p-3 border border-success border-opacity-25 rounded-3 bg-success bg-opacity-10 d-none mb-2" id="store-pickup-option-box">
                    <div class="form-check m-0 d-flex align-items-center gap-2">
                        <input class="form-check-input mt-0 shadow-none" type="checkbox" id="preferStorePickup" name="prefer_pickup">
                        <label class="form-check-label fw-bold text-success cursor-pointer small" for="preferStorePickup">
                            <i class="fas fa-store-alt me-1"></i> I want to self-pickup this order from the nearest local dealer store branch (Free)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Placeholder Proceed Action Trigger -->
            <button class="btn btn-primary btn-lg w-100 rounded-pill fw-bold py-3 shadow-sm">
                Place Secure Order <i class="fas fa-arrow-right ms-2 small"></i>
            </button>
        </div>

        <!-- RIGHT COLUMN: DYNAMIC ORDER INVOICE SUMMARY PANEL -->
        <div class="col-lg-5">
            <div class="card border border-light-subtle rounded-3 shadow-sm bg-white p-4 sticky-top" style="top: 2rem; z-index: 4;">
                <h5 class="fw-bold text-dark mb-4">Order Summary Matrix</h5>
                
                <!-- Dynamic Breakdown Rows caught by your app.js Engine -->
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Unit Price Subtotal</span>
                    <span class="fw-bold text-dark" id="invoice-subtotal">₹0.00</span>
                </div>
                
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">GST / Healthcare Tax (18%)</span>
                    <span class="fw-bold text-dark" id="invoice-gst">₹0.00</span>
                </div>
                
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Delivery Charges</span>
                    <span class="fw-bold text-success" id="invoice-delivery">FREE</span>
                </div>
                
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Installation Support</span>
                    <span class="fw-bold text-dark" id="invoice-installation">₹0.00</span>
                </div>
                
                <div class="d-flex justify-content-between pb-3 border-bottom mb-3">
                    <span class="text-muted">Dealer / Promo Discount</span>
                    <span class="fw-bold text-danger" id="invoice-discount">- ₹0.00</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark fs-5">Final Payable Amount</span>
                    <span class="fw-bold text-primary fs-3" id="invoice-total">₹0.00</span>
                </div>
            </div>
        </div>

    </div>
</div>
</x-layout>