<x-layout :title="'SleepWell | Secure Checkout'">

<div class="container py-5">

    <form id="checkoutForm">

        <div class="row g-4">

            {{-- =========================================================
                 LEFT SIDE
            ========================================================== --}}
            <div class="col-lg-7">

                {{-- CUSTOMER DETAILS --}}
                <div class="card border border-light-subtle rounded-3 shadow-sm bg-white p-4 mb-4">

                    <h4 class="fw-bold text-dark mb-4">
                        Customer Details
                    </h4>

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label for="checkoutName"
                                   class="form-label fw-semibold">
                                Full Name
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="checkoutName"
                                name="name"
                                value="{{ auth()->user()->name ?? '' }}"
                                placeholder="Enter your full name"
                                autocomplete="name"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label for="checkoutPhone"
                                   class="form-label fw-semibold">
                                Phone Number
                            </label>

                            <input
                                type="tel"
                                class="form-control"
                                id="checkoutPhone"
                                name="phone"
                                value="{{ auth()->user()->phone ?? '' }}"
                                placeholder="Enter your phone number"
                                autocomplete="tel"
                                maxlength="15"
                                required
                            >

                        </div>


                        <div class="col-12">

                            <label for="checkoutEmail"
                                   class="form-label fw-semibold">
                                Email Address
                            </label>

                            <input
                                type="email"
                                class="form-control"
                                id="checkoutEmail"
                                name="email"
                                value="{{ auth()->user()->email ?? '' }}"
                                placeholder="Enter your email address"
                                autocomplete="email"
                                required
                            >

                        </div>

                    </div>

                </div>


                {{-- DELIVERY DETAILS --}}
                <div class="card border border-light-subtle rounded-3 shadow-sm bg-white p-4 mb-4">

                    <h4 class="fw-bold text-dark mb-4">
                        Delivery Address
                    </h4>


                    <div class="p-3 bg-light rounded-3 mb-4">

                        <div class="d-flex align-items-center gap-3">

                            <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle">
                                <i class="fas fa-truck fs-5"></i>
                            </div>

                            <div>

                                <div class="small text-muted">
                                    Delivery
                                </div>

                                <div class="fw-bold text-dark">
                                    Pan-India Delivery
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="row g-3">

                        <div class="col-12">

                            <label for="checkoutAddress"
                                   class="form-label fw-semibold">
                                Complete Address
                            </label>

                            <textarea
                                class="form-control"
                                id="checkoutAddress"
                                name="address"
                                rows="3"
                                placeholder="House / Flat No., Street, Area"
                                autocomplete="street-address"
                                required
                            ></textarea>

                        </div>


                        <div class="col-md-6">

                            <label for="checkoutCity"
                                   class="form-label fw-semibold">
                                City
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="checkoutCity"
                                name="city"
                                placeholder="Enter city"
                                autocomplete="address-level2"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label for="checkoutState"
                                   class="form-label fw-semibold">
                                State
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="checkoutState"
                                name="state"
                                placeholder="Enter state"
                                autocomplete="address-level1"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label for="checkoutPincode"
                                   class="form-label fw-semibold">
                                Pincode
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="checkoutPincode"
                                name="pincode"
                                placeholder="Enter pincode"
                                inputmode="numeric"
                                autocomplete="postal-code"
                                maxlength="10"
                                required
                            >

                        </div>

                    </div>

                </div>


                {{-- ADDITIONAL SERVICES --}}
                <div class="card border border-light-subtle rounded-3 shadow-sm bg-white p-4 mb-4">

                    <h5 class="fw-bold text-dark mb-3">
                        Additional Services
                    </h5>


                    <div class="form-check form-switch p-3 border rounded-3 bg-light">

                        <div class="d-flex align-items-start justify-content-between">

                            <div class="pe-3">

                                <label
                                    class="form-check-label fw-bold text-dark cursor-pointer d-block"
                                    for="toggleInstallationSupport"
                                >
                                    Add Premium Installation Support
                                </label>

                                <small class="text-muted d-block mt-1">
                                    An expert technician will visit your location
                                    to unbox, configure, and calibrate your
                                    medical equipment safely.
                                </small>

                            </div>


                            <input
                                class="form-check-input ms-0 mt-1 shadow-none cursor-pointer"
                                type="checkbox"
                                id="toggleInstallationSupport"
                                name="installation_required"
                                value="1"
                                style="width: 2.5em; height: 1.25em;"
                            >

                        </div>

                    </div>

                </div>


                {{-- PAYMENT METHOD --}}
                <div class="card border border-light-subtle rounded-3 shadow-sm bg-white p-4 mb-4">

                    <h5 class="fw-bold text-dark mb-3">
                        Payment Method
                    </h5>


                    <div class="border border-primary rounded-3 p-3 bg-light">

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="payment_method"
                                id="paymentCod"
                                value="cod"
                                checked
                                required
                            >

                            <label
                                class="form-check-label"
                                for="paymentCod"
                            >

                                <span class="fw-bold text-dark d-block">
                                    Cash on Delivery
                                </span>

                                <small class="text-muted">
                                    Pay when your order is delivered.
                                </small>

                            </label>

                        </div>

                    </div>

                </div>


                {{-- ERROR MESSAGE --}}
                <div
                    id="checkoutError"
                    class="alert alert-danger d-none"
                    role="alert"
                ></div>


                {{-- PLACE ORDER --}}
                <button
                    type="submit"
                    id="placeOrderBtn"
                    class="btn btn-primary btn-lg w-100 rounded-pill fw-bold py-3 shadow-sm"
                >
                    <span class="place-order-text">
                        Place Secure Order
                    </span>

                    <span
                        class="place-order-loading d-none"
                    >
                        <span
                            class="spinner-border spinner-border-sm me-2"
                            role="status"
                            aria-hidden="true"
                        ></span>

                        Placing Order...
                    </span>

                    <i class="fas fa-arrow-right ms-2 small"></i>
                </button>

            </div>


            {{-- =========================================================
                 RIGHT SIDE - ORDER SUMMARY
            ========================================================== --}}
            <div class="col-lg-5">

                <div
                    class="card border border-light-subtle rounded-3 shadow-sm bg-white p-4 sticky-top"
                    style="top: 2rem; z-index: 4;"
                >

                    <h5 class="fw-bold text-dark mb-4">
                        Order Summary Matrix
                    </h5>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Unit Price Subtotal
                        </span>

                        <span
                            class="fw-bold text-dark"
                            id="invoice-subtotal"
                        >
                            ₹{{ number_format($subtotal, 2) }}
                        </span>

                    </div>


					<div
					class="d-flex justify-content-between mb-3 {{ $taxEnabled ? '' : 'd-none' }}"
					id="invoice-tax-row"
					>

					<span class="text-muted" id="invoice-tax-label">
					{{ $taxName }}
					({{ rtrim(rtrim(number_format($taxRate, 2, '.', ''), '0'), '.') }}%)
					</span>

					<span
					class="fw-bold text-dark"
					id="invoice-gst"
					>
					₹{{ number_format($gst, 2) }}
					</span>

					</div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Delivery Charges
                        </span>

                        <span
                            class="fw-bold text-success"
                            id="invoice-delivery"
                        >
                            FREE
                        </span>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Installation Support
                        </span>

                        <span
                            class="fw-bold text-dark"
                            id="invoice-installation"
                        >
                            ₹0.00
                        </span>

                    </div>


                    <div class="d-flex justify-content-between pb-3 border-bottom mb-3">

                        <span class="text-muted">
                            Dealer / Promo Discount
                        </span>

                        <span
                            class="fw-bold text-danger"
                            id="invoice-discount"
                        >
                            - ₹0.00
                        </span>

                    </div>


                    <div class="d-flex justify-content-between align-items-center">

                        <span class="fw-bold text-dark fs-5">
                            Final Payable Amount
                        </span>

                        <span
                            class="fw-bold text-primary fs-3"
                            id="invoice-total"
                        >
                            ₹{{ number_format($finalPayable, 2) }}
                        </span>

                    </div>


                    {{-- COD INFORMATION --}}
                    <div class="border-top mt-4 pt-4">

                        <div class="d-flex align-items-start gap-2">

                            <i class="fas fa-shield-alt text-success mt-1"></i>

                            <div>

                                <div class="fw-semibold text-dark">
                                    Secure Cash on Delivery
                                </div>

                                <small class="text-muted">
                                    No online payment is required.
                                    You will pay when the order is delivered.
                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


@push('scripts')

<script type="module">

    $(document).ready(function () {

        /*
         * Trigger the existing invoice calculation immediately.
         */
        if (
            typeof window.jQuery !== 'undefined' &&
            $('#invoice-total').length > 0
        ) {
            $('#toggleInstallationSupport').trigger('change');
        }

    });

</script>

@endpush

</x-layout>