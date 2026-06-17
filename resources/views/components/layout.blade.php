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
                        <a href="#" class="text-secondary"><i class="fas fa-user-circle fs-5"></i></a>
                        
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
            <p class="mb-0 text-muted">© 2026 SleepWell Platform. Built with Laravel 13.</p>
        </div>
    </footer>

    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-3">
            
            <div class="modal-header bg-white border-0 pt-3 pe-3 pb-0 justify-content-end">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4 pt-0">
                <div class="row g-5">
                    
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center justify-content-center bg-white rounded-3 border border-light-subtle mb-3" style="height: 380px;">
                            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff" id="modalProductImage" class="img-fluid object-fit-contain h-100 p-3" alt="Active Frame View">
                        </div>
                        
                        <div class="d-flex gap-2 overflow-x-auto pb-2 scrollbar-thin" style="white-space: nowrap;">
                            <div class="border border-primary border-2 rounded p-1 bg-white cursor-pointer flex-shrink-0" style="width: 70px; height: 70px;">
                                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff" class="img-fluid object-fit-contain h-100 w-100" alt="Thumb 1">
                            </div>
                            <div class="border border-light-subtle rounded p-1 bg-white cursor-pointer flex-shrink-0" style="width: 70px; height: 70px;">
                                <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30" class="img-fluid object-fit-contain h-100 w-100" alt="Thumb 2">
                            </div>
                            <div class="border border-light-subtle rounded p-1 bg-white cursor-pointer flex-shrink-0" style="width: 70px; height: 70px;">
                                <img src="https://images.unsplash.com/photo-1511556532299-8f662fc26c06" class="img-fluid object-fit-contain h-100 w-100" alt="Thumb 3">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 d-flex flex-column justify-content-between">
                        <div>
                            <h2 class="fw-bold text-dark lh-sm fs-3 mb-2" id="modalProductTitle">AirSense™ 10 Autoset™ Tripack 4G CPAP Device with HumidAir and ClimateLineAir</h2>
                            
                            <div class="d-flex align-items-center gap-2 mb-4 small">
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <span class="fw-bold text-dark">4.2</span>
                                <span class="text-muted border-start ps-2">5 Ratings</span>
                            </div>

                            <div class="mb-2">
                                <span class="fs-2 fw-bold text-dark me-2" id="modalProductPrice">₹63,000</span>
                                <span class="text-muted text-decoration-line-through fs-5 me-2">₹86,200</span>
                                <span class="text-primary fw-bold fs-6">(26.91% off)</span>
                            </div>
                            <p class="text-muted small mb-4">MRP (Inclusive of all taxes)</p>
                            
                            <hr class="my-4 text-muted opacity-25">

                            <div class="mb-4">
                                <label class="form-label text-dark fw-bold small mb-2 d-block">Select Option Variant</label>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-2 border border-primary bg-primary bg-opacity-10 cursor-pointer h-100">
                                            <div class="fw-bold text-dark small mb-1">Standard Pack Only</div>
                                            <div class="text-primary fw-bold small">₹63,000.00</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-2 border border-light-subtle bg-white cursor-pointer h-100">
                                            <div class="fw-semibold text-muted small mb-1">Pack & Plus Mask Option</div>
                                            <div class="text-dark fw-bold small">₹67,500.00</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-2 align-items-center pt-3 mt-auto">
                            <div class="col-sm-4">
                                <div class="input-group border border-dark rounded-pill overflow-hidden bg-white px-1">
                                    <button class="btn btn-white border-0 py-2" type="button" id="btn-qty-minus"><i class="fas fa-minus fs-7 text-secondary"></i></button>
                                    <input type="text" class="form-control text-center bg-transparent border-0 fw-bold text-dark py-2" value="1" id="modal-qty" readonly>
                                    <button class="btn btn-white border-0 py-2" type="button" id="btn-qty-plus"><i class="fas fa-plus fs-7 text-secondary"></i></button>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                    <button class="btn btn-dark w-100 rounded-pill py-2.5 fw-bold add-to-cart-btn shadow-sm" data-id="">Add To Cart</button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="border-top border-light-subtle pt-4 mt-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="fw-bold text-dark fs-6">Who other dealers available for this product?</span>
                        <button class="btn btn-outline-primary rounded-pill px-3 fw-semibold text-decoration-none d-flex align-items-center gap-2" 
                                type="button" data-bs-toggle="collapse" data-bs-target="#dealersCollapse" aria-expanded="false" aria-controls="dealersCollapse">
                            View Dealers <i class="fas fa-chevron-down small"></i>
                        </button>
                    </div>
                    
                    <div class="collapse" id="dealersCollapse">
                        <div id="dealerListContainer" class="pt-3">
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
                                        <tr class="border-bottom border-light-subtle">
                                            <td class="fw-semibold text-dark py-3">MedEquip Solutions Delhi</td>
                                            <td class="text-muted py-3">AirSense™ 10 Autoset™ Tripack 4G CPAP Device</td>
                                            <td class="text-success fw-bold py-3 text-end">₹61,800.00</td>
                                        </tr>
                                        <tr class="border-bottom border-light-subtle">
                                            <td class="fw-semibold text-dark py-3">Hyderabadi Sleep Care Center</td>
                                            <td class="text-muted py-3">AirSense™ 10 Autoset™ Tripack 4G CPAP Device</td>
                                            <td class="text-success fw-bold py-3 text-end">₹62,500.00</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-dark py-3">Alpha Medicals Mumbai</td>
                                            <td class="text-muted py-3">AirSense™ 10 Autoset™ Tripack 4G CPAP Device</td>
                                            <td class="text-success fw-bold py-3 text-end">₹64,000.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

    @stack('scripts')
</body>
</html>