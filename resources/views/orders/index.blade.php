<x-layout :title="'SleepWell | My Orders'">

    <div class="container py-5">

        {{-- =====================================================
             PAGE HEADER
        ====================================================== --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

            <div>
                <h1 class="fw-bold text-dark mb-1">
                    My Orders
                </h1>

                <p class="text-muted mb-0">
                    View your previous orders and order details.
                </p>
            </div>

            <a
                href="{{ route('home') }}"
                class="btn btn-outline-primary rounded-pill px-4 fw-semibold"
            >
                <i class="fas fa-shopping-bag me-2"></i>
                Continue Shopping
            </a>

        </div>


        {{-- =====================================================
             ORDERS
        ====================================================== --}}
        @if($orders->count())

            <div class="row g-4">

                @foreach($orders as $order)

                    <div class="col-12">

                        <div class="card border border-light-subtle rounded-3 shadow-sm">

                            <div class="card-body p-4">

                                {{-- ORDER HEADER --}}
                                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">

                                    <div>

                                        <div class="small text-muted mb-1">
                                            Order Number
                                        </div>

                                        <div class="fw-bold text-dark fs-5">
                                            {{ $order->order_number }}
                                        </div>

                                        <div class="small text-muted mt-1">
                                            {{ $order->created_at
                                                ? $order->created_at->format('d M Y, h:i A')
                                                : '—' }}
                                        </div>

                                    </div>


                                    <div class="d-flex flex-wrap align-items-center gap-2">

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

                                        <span class="badge {{ $statusClass }} px-3 py-2">
                                            {{ ucfirst($order->status) }}
                                        </span>

                                        {{-- PAYMENT STATUS --}}
                                        <span class="badge text-bg-light border text-dark px-3 py-2">
                                            Payment:
                                            {{ ucfirst($order->payment_status) }}
                                        </span>

                                    </div>

                                </div>


                                <hr class="my-4">


                                {{-- ORDER SUMMARY --}}
                                <div class="row g-3 align-items-center">

                                    <div class="col-6 col-md-4">

                                        <div class="small text-muted mb-1">
                                            Items
                                        </div>

                                        <div class="fw-semibold text-dark">
                                            {{ $order->items_count }}
                                            {{ $order->items_count == 1 ? 'Item' : 'Items' }}
                                        </div>

                                    </div>


                                    <div class="col-6 col-md-4">

                                        <div class="small text-muted mb-1">
                                            Payment Method
                                        </div>

                                        <div class="fw-semibold text-dark">
                                            {{ strtoupper($order->payment_method) }}
                                        </div>

                                    </div>


                                    <div class="col-12 col-md-4">

                                        <div class="small text-muted mb-1">
                                            Total Amount
                                        </div>

                                        <div class="fw-bold text-primary fs-5">
                                            ₹{{ number_format($order->total_amount, 2) }}
                                        </div>

                                    </div>

                                </div>


                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mt-4">

                                    <div class="small text-muted">
                                        @if($order->items_count > 0)
                                            {{ $order->items_count }}
                                            {{ $order->items_count == 1 ? 'product' : 'products' }}
                                            in this order
                                        @endif
                                    </div>

                                    <a
                                        href="{{ route('orders.show', $order->order_number) }}"
                                        class="btn btn-primary rounded-pill px-4 fw-semibold"
                                    >
                                        <i class="fas fa-eye me-2"></i>
                                        View Order
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>


            {{-- =================================================
                 PAGINATION
            ================================================== --}}
            @if($orders->hasPages())

                <div class="d-flex justify-content-center mt-4">

                    {{ $orders->links() }}

                </div>

            @endif


        @else

            {{-- =================================================
                 EMPTY ORDERS
            ================================================== --}}
            <div class="card border border-light-subtle rounded-3 shadow-sm">

                <div class="card-body text-center py-5 px-4">

                    <div
                        class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary mb-4"
                        style="width: 80px; height: 80px;"
                    >
                        <i class="fas fa-shopping-bag fa-2x"></i>
                    </div>

                    <h4 class="fw-bold text-dark mb-2">
                        No Orders Yet
                    </h4>

                    <p class="text-muted mb-4">
                        You haven't placed any orders yet.
                        Explore our products and place your first order.
                    </p>

                    <a
                        href="{{ route('home') }}"
                        class="btn btn-primary rounded-pill px-4 fw-semibold"
                    >
                        <i class="fas fa-shopping-cart me-2"></i>
                        Start Shopping
                    </a>

                </div>

            </div>

        @endif

    </div>

</x-layout>