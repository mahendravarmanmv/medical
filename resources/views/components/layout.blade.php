<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SleepWell | Home' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    <section class="bg-primary text-white py-2 small fw-medium shadow-sm">
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
    </section>

    <header class="bg-white sticky-top shadow-sm">
        <div class="container">
            <nav class="navbar navbar-expand-xl navbar-light py-2">
                <div class="container-fluid px-0 d-flex align-items-center justify-content-between">

                    <a class="navbar-brand d-flex align-items-center m-0" href="{{ url('/') }}">
                        <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff" alt="SleepWell Logo" height="30" class="me-2 rounded">
                        <span class="fs-4 fw-bold text-dark">SleepWell</span>
                    </a>

                    <div class="d-flex align-items-center gap-3 order-xl-3 ms-auto me-3 me-xl-0">
                        <a href="#" class="text-secondary"><i class="fas fa-search"></i></a>
                        <a href="{{ url('/auth') }}" class="text-secondary"><i class="fas fa-user-circle fs-5"></i></a>

                        <button class="btn btn-dark rounded-pill px-3 px-sm-4 py-1.5 d-flex align-items-center gap-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartDrawer">
                            <i class="bi bi-cart3"></i>
                            <span class="d-none d-sm-inline small fw-semibold">Cart</span>
                            <span class="badge bg-danger rounded-pill" id="global-cart-count">
                                {{ session('cart') ? count(session('cart')) : 0 }}
                            </span>
                        </button>
                    </div>

                    <button class="navbar-toggler order-xl-2 border-0 p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse order-xl-1" id="navbarContent">
                        <ul class="navbar-nav mx-auto mb-2 mb-xl-0 text-center py-3 py-xl-0 gap-2 gap-xl-0">
                            <li class="nav-item"><a class="nav-link active fw-medium px-3 text-nowrap" href="#">Home</a></li>
                            <li class="nav-item"><a class="nav-link fw-medium px-3 text-nowrap" href="#">CPAP</a></li>
                            <li class="nav-item"><a class="nav-link fw-medium px-3 text-nowrap" href="#">BiPAP</a></li>
                            <li class="nav-item"><a class="nav-link fw-medium px-3 text-nowrap" href="#">Masks</a></li>
                            <li class="nav-item"><a class="nav-link fw-medium px-3 text-nowrap" href="#">Sleep Study</a></li>
                            <li class="nav-item"><a class="nav-link fw-medium px-3 text-nowrap" href="#">Rentals</a></li>
                            <li class="nav-item"><a class="nav-link fw-medium px-3 text-nowrap" href="#">Accessories</a></li>
                            <li class="nav-item"><a class="nav-link fw-medium px-3 text-nowrap" href="#">Blog</a></li>
                            <li class="nav-item"><a class="nav-link fw-medium px-3 text-nowrap" href="#">Contact Us</a></li>
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
            <p class="mb-0">© 2026 SleepWell Platform. Built with CCLPS.</p>
        </div>
    </footer>

    @include('components.modals.product-details-modal')
    @include('components.modals.eligibility-modal')
    

    @stack('scripts')
</body>

</html>