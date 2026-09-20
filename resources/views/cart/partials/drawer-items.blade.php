@if(count($cart) > 0)

    @if($hasUnavailableItems ?? false)
        <div class="alert alert-warning d-flex align-items-start gap-2 mb-3 small" role="alert">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
            <div>
                <strong>Some items are unavailable.</strong>
                <div class="mt-1">
                    Some items in your cart are not deliverable to your selected pincode.
                    Please remove them or change your location to continue.
                </div>
            </div>
        </div>
    @endif

    <div class="flex-grow-1 overflow-y-auto mb-3 mh-75">

        @foreach($cart as $id => $details)

            @php
                $isDeliverable = $details['is_deliverable'] ?? false;
            @endphp

            <div
                class="d-flex align-items-center justify-content-between p-3 mb-2 bg-white rounded shadow-sm border-start border-3
                    {{ $isDeliverable ? 'border-primary' : 'border-warning opacity-75' }}"
            >

                <div class="border border-light-subtle rounded bg-white flex-shrink-0 d-flex align-items-center justify-content-center p-1 cart-thumb-box">
                    <img
                        src="{{ $details['image'] ?? $details['image_url'] ?? asset('images/default-product.png') }}"
                        class="img-fluid object-fit-contain h-100 w-100"
                        alt="{{ $details['title'] ?? 'Medical Equipment' }}"
                    >
                </div>

                <div class="ms-3 flex-grow-1 me-4 pe-2">

                    <h6
                        class="fw-bold mb-1 text-break lh-base"
                        title="{{ $details['title'] ?? '' }}"
                    >
                        {{ $details['title'] ?? 'Product' }}
                    </h6>

                    @if(!empty($details['package_name']) && $details['package_name'] !== 'Standard Pack')
                        <div class="text-primary fs-7 fw-semibold mb-1">
                            {{ $details['package_name'] }}
                        </div>
                    @endif

                    {{-- Pincode availability --}}
                    @if(!$isDeliverable)
                        <div class="text-warning-emphasis small fw-semibold mb-2">
                            <i class="bi bi-geo-alt-fill me-1"></i>
                            Not deliverable to your location
                        </div>
                    @endif

                    <div class="d-flex align-items-center gap-2 mt-2">

                        <div class="input-group input-group-sm rounded border border-light-subtle cart-qty-group">
                            <button
                                class="btn btn-light btn-sm border-0 px-2 change-drawer-qty-btn shadow-none"
                                type="button"
                                data-action="decrease"
                                data-id="{{ $id }}"
                            >
                                —
                            </button>

                            <input
                                type="text"
                                class="form-control form-control-sm text-center bg-white border-0 p-0 fw-medium text-dark shadow-none"
                                value="{{ $details['quantity'] ?? 1 }}"
                                readonly
                            >

                            <button
                                class="btn btn-light btn-sm border-0 px-2 change-drawer-qty-btn shadow-none"
                                type="button"
                                data-action="increase"
                                data-id="{{ $id }}"
                            >
                                +
                            </button>
                        </div>

                        <span class="text-muted small ps-1">
                            × ₹{{ number_format($details['price'] ?? 0, 0) }}
                        </span>

                    </div>
                </div>

                <div class="flex-shrink-0">

                    <div class="d-flex flex-column align-items-end gap-1">

    <button
        class="btn btn-sm btn-outline-danger border-0 remove-cart-item-btn shadow-none"
        data-id="{{ $id }}"
        type="button"
        title="Remove from cart"
    >
        <i class="bi bi-trash3"></i>
    </button>

    @if(!$isDeliverable)
        <button
            type="button"
            class="btn btn-link btn-sm text-primary text-decoration-none p-0 save-for-later-btn"
            data-id="{{ $id }}"
        >
            Save for Later
        </button>
    @endif

</div>

                </div>

            </div>

        @endforeach

    </div>

    <div class="border-top pt-3 bg-light p-3 rounded mt-auto">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0 text-secondary">
                Total Amount:
            </h5>

            <h4 class="fw-bold text-primary mb-0">
                ₹{{ number_format($total ?? 0, 2) }}
            </h4>
        </div>

        @if($hasUnavailableItems ?? false)

            <button
                type="button"
                class="btn btn-secondary w-100 py-2 rounded-pill fw-bold shadow-sm"
                disabled
            >
                <i class="bi bi-exclamation-circle me-1"></i>
                Remove Unavailable Items To Continue
            </button>

        @else

            <a
                href="{{ route('checkout') }}"
                class="btn btn-dark w-100 py-2 rounded-pill fw-bold shadow-sm text-white d-flex align-items-center justify-content-center text-decoration-none gap-1"
            >
                Proceed To Checkout
                <i class="bi bi-arrow-right ms-1"></i>
            </a>

        @endif

    </div>

@else

    <div class="text-center py-5 my-auto">
        <i class="bi bi-cart-x display-1 text-muted opacity-25"></i>

        <p class="text-muted mt-3 fw-medium">
            Your shopping cart is currently empty.
        </p>

        <button
            type="button"
            class="btn btn-outline-dark btn-sm rounded-pill px-4 mt-2"
            data-bs-dismiss="offcanvas"
        >
            Continue Shopping
        </button>
    </div>

@endif