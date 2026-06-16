<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? 'ShopZone | Home' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <section class="bg-white shadow-sm sticky-top">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light py-3">
                <a class="navbar-brand fw-bold fs-3" href="{{ url('/') }}">ShopZone</a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link active fw-semibold" href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold" href="#">Products</a>
                        </li>
                        
                        <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                            <button class="btn btn-dark rounded-pill px-4" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartDrawer" aria-controls="cartDrawer">
                                <i class="bi bi-cart3"></i> Cart 
                                <span class="badge bg-danger ms-1 rounded-pill" id="global-cart-count">
                                    {{ session('cart') ? count(session('cart')) : 0 }}
                                </span>
                            </button>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </section>

    <main class="flex-grow-1">
        {{ $slot }}
    </main>

    <section class="bg-dark text-white py-4 mt-auto">
        <div class="container text-center">
            <p class="mb-0 text-light opacity-75">ShopZone Enterprise Ecommerce Platform Built via Laravel 13.</p>
        </div>
    </section>

    <div class="offcanvas offcanvas-end border-0 shadow" tabindex="-1" id="cartDrawer" aria-labelledby="cartDrawerLabel">
        <div class="offcanvas-header bg-dark text-white">
            <h5 class="offcanvas-title fw-bold" id="cartDrawerLabel">
                <i class="bi bi-bag-check me-2"></i>Your Shopping Cart
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-3 d-flex flex-column" id="cart-drawer-body">
            <div class="text-center py-5 my-auto">
                <div class="spinner-border text-dark" role="status"></div>
                <p class="text-muted mt-2 small fw-medium">Synchronizing secure session basket...</p>
            </div>
        </div>
    </div>

</body>
</html>