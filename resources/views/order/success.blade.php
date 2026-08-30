<x-layout :title="'SleepWell | Order Confirmed'">

<div class="container py-5">

    {{-- =========================================================
         SUCCESS HEADER
    ========================================================== --}}
    <div class="text-center mb-5">

        <div
            class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 text-success mb-4"
            style="width: 80px; height: 80px;"
        >
            <i class="fas fa-check fa-2x"></i>
        </div>

        <h1 class="fw-bold text-dark mb-2">
            Order Placed Successfully!
        </h1>

        <p class="text-muted mb-1">
            Thank you for your purchase.
        </p>

        <p class="text-muted">
            Your order has been successfully placed using Cash on Delivery.
        </p>

    </div>


    <div class="row g-4">

        {{-- =====================================================
             LEFT SIDE
        ====================================================== --}}
        <div class="col-lg-8">

            {{-- ORDER INFORMATION --}}
            <div class="card border border-light-subtle rounded-3 shadow-sm bg-white p-4 mb-4">

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

                    <div>

                        <div class="small text-muted mb-1">
                            Order Number
                        </div>

                        <div class="fw-bold text-dark fs-5">
                            {{ $order->order_number }}
                        </div>

                    </div>


                    <div class="text-md-end">

                        <div class="small text-muted mb-1">
                            Order Status
                        </div>

                        <span class="badge bg-warning text-dark px-3 py-2">
                            {{ ucfirst($order->status) }}
                        </span>

                    </div>

                </div>

            </div>


            {{-- PURCHASED PRODUCTS --}}
            <div class="card border border-light-subtle rounded-3 shadow-sm bg-white p-4 mb-4">

                <h5 class="fw-bold text-dark mb-4">
                    Purchased Products
                </h5>


                @foreach($order->items as $item)

                    <div
                        class="d-flex flex-column flex-md-row justify-content-between gap-3 py-3 border-bottom"
                    >

                        <div class="d-flex gap-3">

                            @if($item->product && $item->product->image_url)

                                <div
                                    class="border rounded-3 p-2 flex-shrink-0"
                                    style="width: 80px; height: 80px;"
                                >
                                    <img
                                        src="{{ asset($item->product->image_url) }}"
                                        alt="{{ $item->product_name }}"
                                        class="img-fluid w-100 h-100 object-fit-contain"
                                    >
                                </div>

                            @endif


                            <div>

                                <div class="fw-bold text-dark">
                                    {{ $item->product_name }}
                                </div>


                                @if($item->package_name)

                                    <div class="small text-muted mt-1">
                                        Package:
                                        <span class="fw-semibold">
                                            {{ $item->package_name }}
                                        </span>
                                    </div>

                                @endif


                                @if($item->warranty_years)

                                    <div class="small text-muted mt-1">

                                        Warranty:
                                        <span class="fw-semibold text-primary">

                                            {{ $item->warranty_years }}
                                            Year{{ $item->warranty_years > 1 ? 's' : '' }}

                                        </span>

                                    </div>

                                @endif


                                <div class="small text-muted mt-1">
                                    Quantity:
                                    <span class="fw-semibold">
                                        {{ $item->quantity }}
                                    </span>
                                </div>

                            </div>

                        </div>


                        <div class="text-md-end">

                            <div class="fw-bold text-dark">
                                ₹{{ number_format($item->line_total, 2) }}
                            </div>

                            <div class="small text-muted">
                                ₹{{ number_format($item->unit_price, 2) }}
                                each
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>


            {{-- DELIVERY ADDRESS --}}
            @if($order->address)

                <div class="card border border-light-subtle rounded-3 shadow-sm bg-white p-4 mb-4">

                    <h5 class="fw-bold text-dark mb-4">
                        Delivery Address
                    </h5>


                    <div class="mb-2 fw-bold text-dark">
                        {{ $order->address->name }}
                    </div>


                    <div class="text-muted">
                        {{ $order->address->address }}
                    </div>


                    <div class="text-muted">
                        {{ $order->address->city }},
                        {{ $order->address->state }}
                        - {{ $order->address->pincode }}
                    </div>


                    <div class="text-muted mt-2">
                        <i class="fas fa-phone-alt me-2"></i>
                        {{ $order->address->phone }}
                    </div>


                    @if($order->address->email)

                        <div class="text-muted mt-1">
                            <i class="fas fa-envelope me-2"></i>
                            {{ $order->address->email }}
                        </div>

                    @endif

                </div>

            @endif


            {{-- NOTIFICATION MESSAGE --}}
            <div class="card border border-primary-subtle rounded-3 bg-primary bg-opacity-10 p-4">

                <div class="d-flex align-items-start gap-3">

                    <i class="fas fa-bell text-primary fs-5 mt-1"></i>

                    <div>

                        <div class="fw-bold text-dark mb-1">
                            Order Confirmation
                        </div>

                        <p class="text-muted mb-0">
                            You will receive your order confirmation
                            and purchase details through email and WhatsApp.
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             RIGHT SIDE
        ====================================================== --}}
        <div class="col-lg-4">

            <div
                class="card border border-light-subtle rounded-3 shadow-sm bg-white p-4 sticky-top"
                style="top: 2rem; z-index: 4;"
            >

                <h5 class="fw-bold text-dark mb-4">
                    Order Summary
                </h5>


                <div class="d-flex justify-content-between mb-3">

                    <span class="text-muted">
                        Subtotal
                    </span>

                    <span class="fw-semibold text-dark">
                        ₹{{ number_format($order->subtotal, 2) }}
                    </span>

                </div>


                <div class="d-flex justify-content-between mb-3">

                    <span class="text-muted">
                        GST
                    </span>

                    <span class="fw-semibold text-dark">
                        ₹{{ number_format($order->gst_amount, 2) }}
                    </span>

                </div>


                <div class="d-flex justify-content-between mb-3">

                    <span class="text-muted">
                        Delivery
                    </span>

                    <span class="fw-semibold text-success">

                        @if((float) $order->delivery_charge > 0)
                            ₹{{ number_format($order->delivery_charge, 2) }}
                        @else
                            FREE
                        @endif

                    </span>

                </div>


                @if(isset($order->installation_charges) && (float) $order->installation_charges > 0)

                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Installation
                        </span>

                        <span class="fw-semibold text-dark">
                            ₹{{ number_format($order->installation_charges, 2) }}
                        </span>

                    </div>

                @endif


                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">

                    <span class="text-muted">
                        Discount
                    </span>

                    <span class="fw-semibold text-danger">
                        - ₹{{ number_format($order->discount_amount, 2) }}
                    </span>

                </div>


                <div class="d-flex justify-content-between align-items-center mb-4">

                    <span class="fw-bold text-dark fs-5">
                        Total
                    </span>

                    <span class="fw-bold text-primary fs-3">
                        ₹{{ number_format($order->total_amount, 2) }}
                    </span>

                </div>


                {{-- PAYMENT --}}
                <div class="border rounded-3 p-3 bg-light">

                    <div class="small text-muted">
                        Payment Method
                    </div>

                    <div class="fw-bold text-dark mt-1">
                        <i class="fas fa-money-bill-wave text-success me-2"></i>
                        Cash on Delivery
                    </div>

                    <div class="small text-muted mt-1">
                        Payment status:
                        <span class="fw-semibold">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>

                </div>


                <a
                    href="{{ route('home') }}"
                    class="btn btn-primary w-100 rounded-pill fw-bold mt-4 py-2"
                >
                    Continue Shopping
                </a>

            </div>

        </div>

    </div>

</div>

</x-layout>