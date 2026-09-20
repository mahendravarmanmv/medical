<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SleepQ | Home' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light d-flex flex-column min-vh-100">

{{-- Mandatory Shopping Pincode Modal --}}
<div
    class="modal fade"
    id="pincodeModal"
    tabindex="-1"
    aria-labelledby="pincodeModalLabel"
    aria-hidden="true"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    data-pincode-select-url="{{ route('pincode.select') }}"
    data-has-selected-pincode="{{ session()->has('selected_pincode_id') ? '1' : '0' }}"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-body p-4 p-md-5 text-center">

                <div class="mb-3">

                    <div
                        class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary"
                        style="width: 64px; height: 64px;"
                    >
                        <i class="bi bi-geo-alt fs-3"></i>
                    </div>

                </div>

                <h4
                    class="fw-semibold mb-2"
                    id="pincodeModalLabel"
                >
                    Enter Your Pincode
                </h4>

                <p class="text-muted mb-4">
                    Please enter your pincode to check product availability in your area.
                </p>

                <form id="pincode-form">

                    @csrf

                    <div class="mb-3">

                        <label
                            for="selected-pincode"
                            class="visually-hidden"
                        >
                            Pincode
                        </label>

                        <input
                            type="text"
                            id="selected-pincode"
                            name="pincode"
                            class="form-control form-control-lg text-center"
                            placeholder="Enter 6-digit pincode"
                            inputmode="numeric"
                            maxlength="6"
                            pattern="[0-9]{6}"
                            autocomplete="postal-code"
                            required
                        >

                    </div>

                    <div
                        id="pincode-error"
                        class="text-danger small mb-3 d-none"
                        role="alert"
                    ></div>

                    <button
                        type="submit"
                        id="pincode-submit"
                        class="btn btn-primary btn-lg w-100"
                    >
                        <span class="pincode-submit-text">
                            Continue
                        </span>

                        <span
                            class="pincode-submit-loading d-none"
                        >
                            Checking...
                        </span>
                    </button>

                </form>

            </div>

        </div>
    </div>
</div>

{{-- <section class="bg-primary text-white py-2 small fw-medium shadow-sm">
        <div class="container">

            <div class="d-none d-xl-flex justify-content-between align-items-center text-nowrap">
                <div class="d-flex gap-4">
                    <span><i class="fas fa-shipping-fast me-1"></i> Pan-India Delivery</span>
                    <span><i class="fas fa-percent me-1"></i> EMI & Easy Finance Options</span>
                    <span><i class="fas fa-user-md me-1"></i> Expert Sleep Support</span>
                </div>
                <div>
                    <span>Need Help? Talk to our sleep expert <i class="fas fa-phone ms-2 me-1"></i> 1800-123-4567</span>
                </div>
            </div>

            <div id="topBarCarousel" class="carousel slide carousel-fade d-xl-none text-center" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner">

                    <div class="carousel-item active">
                        <span class="d-inline-flex align-items-center gap-1">
                            <i class="fas fa-shipping-fast"></i> Pan-India Delivery
                        </span>
                    </div>

                    <div class="carousel-item">
                        <span class="d-inline-flex align-items-center gap-1">
                            <i class="fas fa-percent"></i> EMI & Easy Finance Options
                        </span>
                    </div>

                    <div class="carousel-item">
                        <span class="d-inline-flex align-items-center gap-1">
                            <i class="fas fa-user-md"></i> Expert Sleep Support
                        </span>
                    </div>

                    <div class="carousel-item">
                        <span class="d-inline-flex align-items-center gap-1">
                            <span>Need Help? Talk to our expert:</span>
                            <a href="tel:18001234567" class="text-white text-decoration-none fw-bold">1800-123-4567</a>
                        </span>
                    </div>

                </div>
            </div>

        </div>
    </section> --}}

    <header class="bg-white sticky-top shadow-sm">
        <div class="container">
    <nav class="navbar navbar-expand-xl navbar-light py-2">
        <div class="container-fluid px-0 d-flex align-items-center justify-content-between">

            {{-- SleepQ Logo --}}
            <a class="navbar-brand d-flex align-items-center m-0" href="{{ url('/') }}">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="SleepQ Logo"
                    height="65"
                    class="me-2"
                >
            </a>

            {{-- Right Side: Pincode + Account + Cart --}}
            <div class="d-flex align-items-center gap-3 order-xl-3 ms-auto me-3 me-xl-0">

                {{-- Selected Pincode --}}
                @if (session()->has('selected_pincode'))
                    <button
                        type="button"
                        class="btn btn-link text-decoration-none text-dark d-flex align-items-center gap-1 p-0"
                        id="change-pincode-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#pincodeModal"
                    >
                        <i class="bi bi-geo-alt text-primary"></i>

                        <span class="small">
                            {{ session('selected_pincode') }}
                        </span>

                        <span class="small text-primary fw-semibold">
                            Change
                        </span>
                    </button>
                @endif

                {{-- User Account --}}
                @auth
                    <div class="dropdown">
                        <a
                            href="#"
                            class="text-secondary dropdown-toggle no-caret"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <i class="fas fa-user-circle fs-5 text-primary"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-light-subtle rounded-2 mt-2">
                            <li>
                                <h6 class="dropdown-header small text-muted">
                                    Signed in as
                                    <br>
                                    <span class="fw-bold text-dark">
                                        {{ auth()->user()->name }}
                                    </span>
                                </h6>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a
                                    href="{{ route('orders.index') }}"
                                    class="dropdown-item"
                                >
                                    <i class="fas fa-box me-2"></i>
                                    My Orders
                                </a>

                                <form
                                    action="{{ route('logout') }}"
                                    method="POST"
                                    class="d-block m-0"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="dropdown-item text-danger d-flex align-items-center gap-2 py-2 small fw-medium"
                                    >
                                        <i class="fas fa-sign-out-alt"></i>
                                        Sign Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a
                        href="{{ url('/auth') }}"
                        class="text-secondary"
                    >
                        <i class="fas fa-user-circle fs-5"></i>
                    </a>
                @endauth

                {{-- Cart --}}
                <button
                    class="btn btn-dark rounded-pill px-3 px-sm-4 py-1.5 d-flex align-items-center gap-1"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#cartDrawer"
                >
                    <i class="bi bi-cart3"></i>

                    <span class="d-none d-sm-inline small fw-semibold">
                        Cart
                    </span>

                    <span
                        class="badge bg-danger rounded-pill"
                        id="global-cart-count"
                    >
                        {{ session('cart') ? count(session('cart')) : 0 }}
                    </span>
                </button>

            </div>

            {{-- Mobile Navbar Toggle --}}
            <button
                class="navbar-toggler order-xl-2 border-0 p-2"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarContent"
                aria-controls="navbarContent"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Navigation --}}
            <div
                class="collapse navbar-collapse order-xl-1"
                id="navbarContent"
            >
                <ul class="navbar-nav mx-auto mb-2 mb-xl-0 text-center py-3 py-xl-0 gap-2 gap-xl-0">

                    {{--
                    <li class="nav-item">
                        <a
                            class="nav-link active fw-medium px-3 text-nowrap"
                            href="{{ url('/') }}"
                        >
                            Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link fw-medium px-3 text-nowrap"
                            href="#"
                        >
                            CPAP
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link fw-medium px-3 text-nowrap"
                            href="#"
                        >
                            BiPAP
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link fw-medium px-3 text-nowrap"
                            href="#"
                        >
                            Masks
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link fw-medium px-3 text-nowrap"
                            href="#"
                        >
                            Sleep Study
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link fw-medium px-3 text-nowrap"
                            href="#"
                        >
                            Rentals
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link fw-medium px-3 text-nowrap"
                            href="#"
                        >
                            Accessories
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link fw-medium px-3 text-nowrap"
                            href="#"
                        >
                            Blog
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link fw-medium px-3 text-nowrap"
                            href="#"
                        >
                            Contact Us
                        </a>
                    </li>
                    --}}

                </ul>
            </div>

        </div>
    </nav>
</div>
    </header>

    <main class="flex-grow-1">
        {{ $slot }}
    </main>

    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container text-center">
            <p class="mb-0">© 2026 SleepQ Platform.</p>
        </div>
    </footer>

    @include('components.modals.product-details-modal')
    @include('cart.partials.cart-drawer')

    <div class="toast-container position-fixed bottom-0 end-0 p-2" style="z-index: 1085;">
        <div id="cartToast" class="toast border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
            <div class="toast-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-5"></i> Item Added to Cart
                </span>
                <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body bg-white p-3 fw-medium text-success" id="cartToastMessage">
                The item has been successfully added to your cart.
            </div>
        </div>
    </div>

    @stack('scripts')
</body>

</html>