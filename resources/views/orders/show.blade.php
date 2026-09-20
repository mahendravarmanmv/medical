<x-layout :title="'SleepWell | Order ' . $order->order_number">

    <div class="container py-5">

        {{-- PAGE HEADER --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

            <div>
                <h1 class="fw-bold text-dark mb-1">
                    Order Details
                </h1>

                <p class="text-muted mb-0">
                    Order #{{ $order->order_number }}
                </p>
            </div>

            <a
                href="{{ route('orders.index') }}"
                class="btn btn-outline-primary rounded-pill px-4 fw-semibold"
            >
                <i class="fas fa-arrow-left me-2"></i>
                My Orders
            </a>

        </div>


        {{-- ORDER STATUS --}}
        @php
            $statusClass = match (strtolower($order->status)) {
                'pending' => 'text-bg-warning',
                'confirmed' => 'text-bg-primary',
                'processing' => 'text-bg-info',
                'shipped' => 'text-bg-primary',
                'delivered' => 'text-bg-success',
                'completed' => 'text-bg-success',
                'cancelled' => 'text-bg-danger',
                'failed' => 'text-bg-danger',
                default => 'text-bg-secondary',
            };
        @endphp


        <div class="card border border-light-subtle rounded-3 shadow-sm mb-4">

            <div class="card-body p-4">

                <div class="row g-4 align-items-center">

                    <div class="col-md-4">

                        <div class="small text-muted mb-1">
                            Order Number
                        </div>

                        <div class="fw-bold text-dark">
                            {{ $order->order_number }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="small text-muted mb-1">
                            Order Date
                        </div>

                        <div class="fw-semibold text-dark">
                            {{ $order->created_at->format('d M Y, h:i A') }}
                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="small text-muted mb-1">
                            Order Status
                        </div>

                        <span class="badge {{ $statusClass }} px-3 py-2">
                            {{ ucfirst($order->status) }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        <div class="row g-4">

            {{-- =====================================================
                 ORDER ITEMS
            ====================================================== --}}
            <div class="col-lg-8">

                <div class="card border border-light-subtle rounded-3 shadow-sm mb-4">

                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="fw-bold text-dark mb-0">
                            Ordered Products
                        </h5>
                    </div>

                    <div class="card-body p-0">

                        @foreach($order->items as $item)

                            <div class="p-4 {{ !$loop->last ? 'border-bottom' : '' }}">

                                <div class="row g-3 align-items-center">

                                    <div class="col-md-7">

                                        <h6 class="fw-bold text-dark mb-2">
                                            {{ $item->product_name }}
                                        </h6>

                                        @if($item->package_name)
                                            <div class="small text-muted mb-1">
                                                <strong>Package:</strong>
                                                {{ $item->package_name }}
                                            </div>
                                        @endif

                                        @if($item->warranty_years)
                                            <div class="small text-muted mb-1">
                                                <strong>Warranty:</strong>
                                                {{ $item->warranty_years }} Year
                                                @if($item->warranty_price)
                                                    (+ ₹{{ number_format($item->warranty_price, 2) }})
                                                @endif
                                            </div>
                                        @endif

                                        @if($item->dealer)
                                            <div class="small text-muted">
                                                <strong>Dealer:</strong>
                                                {{ $item->dealer->dealer_name }}
                                                @if($item->dealer->city)
                                                    — {{ $item->dealer->city }}
                                                @endif
                                            </div>
                                        @endif

                                    </div>


                                    <div class="col-6 col-md-2">

                                        <div class="small text-muted mb-1">
                                            Quantity
                                        </div>

                                        <div class="fw-semibold">
                                            {{ $item->quantity }}
                                        </div>

                                    </div>


                                    <div class="col-6 col-md-3 text-md-end">

                                        <div class="small text-muted mb-1">
                                            Total
                                        </div>

                                        <div class="fw-bold text-dark">
                                            ₹{{ number_format($item->line_total, 2) }}
                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>


                {{-- =================================================
                     DELIVERY ADDRESS
                ================================================== --}}
                @if($order->address)

                    <div class="card border border-light-subtle rounded-3 shadow-sm mb-4">

                        <div class="card-header bg-white border-bottom p-4">
                            <h5 class="fw-bold text-dark mb-0">
                                Delivery Address
                            </h5>
                        </div>

                        <div class="card-body p-4">

                            <div class="fw-bold text-dark mb-2">
                                {{ $order->address->name }}
                            </div>

                            <div class="text-muted mb-1">
                                {{ $order->address->address }}
                            </div>

                            <div class="text-muted mb-1">
                                {{ $order->address->city }},
                                {{ $order->address->state }}
                                - {{ $order->address->pincode }}
                            </div>

                            <div class="text-muted">
                                <strong>Phone:</strong>
                                {{ $order->address->phone }}
                            </div>

                        </div>

                    </div>

                @endif


                {{-- =================================================
                     PAYMENT
                ================================================== --}}
                <div class="card border border-light-subtle rounded-3 shadow-sm">

                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="fw-bold text-dark mb-0">
                            Payment Information
                        </h5>
                    </div>

                    <div class="card-body p-4">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <div class="small text-muted mb-1">
                                    Payment Method
                                </div>

                                <div class="fw-semibold text-dark">
                                    {{ strtoupper($order->payment_method) }}
                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="small text-muted mb-1">
                                    Payment Status
                                </div>

                                <span class="badge text-bg-light border text-dark">
                                    {{ ucfirst($order->payment_status) }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 ORDER SUMMARY
            ====================================================== --}}
            <div class="col-lg-4">

                <div class="card border border-light-subtle rounded-3 shadow-sm">

                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="fw-bold text-dark mb-0">
                            Order Summary
                        </h5>
                    </div>

                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Subtotal
                            </span>

                            <span class="fw-semibold">
                                ₹{{ number_format($order->subtotal, 2) }}
                            </span>

                        </div>


                        @if($order->gst_amount > 0)

                            <div class="d-flex justify-content-between mb-3">

                                <span class="text-muted">
                                    GST
                                    @if($order->gst_rate !== null)
                                        ({{ rtrim(rtrim(number_format($order->gst_rate, 2, '.', ''), '0'), '.') }}%)
                                    @endif
                                </span>

                                <span class="fw-semibold">
                                    ₹{{ number_format($order->gst_amount, 2) }}
                                </span>

                            </div>

                        @endif


                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Delivery
                            </span>

                            <span class="fw-semibold">
                                {{ $order->delivery_charge > 0
                                    ? '₹' . number_format($order->delivery_charge, 2)
                                    : 'FREE' }}
                            </span>

                        </div>


                        @if($order->installation_charges > 0)

                            <div class="d-flex justify-content-between mb-3">

                                <span class="text-muted">
                                    Installation
                                </span>

                                <span class="fw-semibold">
                                    ₹{{ number_format($order->installation_charges, 2) }}
                                </span>

                            </div>

                        @endif


                        @if($order->discount_amount > 0)

                            <div class="d-flex justify-content-between mb-3">

                                <span class="text-muted">
                                    Discount
                                </span>

                                <span class="fw-semibold text-success">
                                    - ₹{{ number_format($order->discount_amount, 2) }}
                                </span>

                            </div>

                        @endif


                        <hr>


                        <div class="d-flex justify-content-between align-items-center">

                            <span class="fw-bold text-dark">
                                Total
                            </span>

                            <span class="fw-bold text-primary fs-4">
                                ₹{{ number_format($order->total_amount, 2) }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-layout>