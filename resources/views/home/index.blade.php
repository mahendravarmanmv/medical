<x-layout title="Sleep Better, Live Better | SleepWell">

    <section id="heroBannerCarousel" class="carousel slide carousel-fade w-100 mb-5" data-bs-ride="carousel">

        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroBannerCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">

    <!-- BANNER ITEM 1 -->
    <div class="carousel-item active position-relative w-100">
        <!-- 🖥️ Desktop View: Displays full banner from the top downwards on MD screens and larger -->
        <img src="{{ asset('images/banners/banner1.jpeg') }}" class="w-100 h-auto d-none d-md-block" alt="SleepWell Desktop Banner 1">
        
        <!-- 📱 Mobile View: Displays mobile-optimized version on small screens -->
        <img src="{{ asset('images/banners/banner1.jpeg') }}" class="w-100 h-auto d-block d-md-none" alt="SleepWell Mobile Banner 1">
        
        <!-- Dynamic Interactive Overlay Container (Kept clean as requested) -->
        <div class="position-absolute bottom-0 start-0 w-100 p-4 z-2">
            <div class="container px-0 text-start">
                <div class="d-flex gap-3 mt-4">
                    {{-- <a href="#" class="btn btn-dark btn-lg rounded-pill px-5 fs-6 fw-bold shadow-sm">Shop Now</a> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- BANNER ITEM 2 -->
    <div class="carousel-item position-relative w-100">
        <!-- 🖥️ Desktop View -->
        <img src="{{ asset('images/banners/banner1.jpeg') }}" height="200" class="w-100 h-auto d-none d-md-block" alt="SleepWell Desktop Banner 2">
        
        <!-- 📱 Mobile View -->
        <img src="{{ asset('images/banners/banner1.jpeg') }}" class="w-100 h-auto d-block d-md-none" alt="SleepWell Mobile Banner 2">
        
        <div class="position-absolute bottom-0 start-0 w-100 p-4 z-2">
            <div class="container px-0 text-start">
                <div class="d-flex gap-3 mt-4">
                    {{-- <a href="#" class="btn btn-dark btn-lg rounded-pill px-5 fs-6 fw-bold shadow-sm">Explore Masks</a> --}}
                </div>
            </div>
        </div>
    </div>    

</div>

        <button class="carousel-control-prev w-5 z-3" type="button" data-bs-target="#heroBannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon p-3 bg-dark bg-opacity-25 rounded-circle" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next w-5 z-3" type="button" data-bs-target="#heroBannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon p-3 bg-dark bg-opacity-25 rounded-circle" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </section>

    <section class="container mb-4">
        <div class="row">

            <div class="col-lg-3 mb-4">
                <div class="card border-0 shadow-sm sticky-top pt-2">
                    <div class="card-header bg-dark text-white fw-bold">Shop by Category</div>
                    <div class="card-body p-0">
                        {{-- Default selected category --}}
        <button
            class="btn d-flex align-items-center justify-content-between w-100 py-3 ps-3 pe-3 border-0 rounded-0 bg-primary-subtle text-primary fw-bold">

            <span class="d-flex align-items-center text-start">

                <span class="d-inline-flex justify-content-center align-items-center me-3 fs-5"
                      style="width: 2.5rem;">
                    <i class="fas fa-th-large"></i>
                </span>

                <span>All Products</span>

            </span>

           

        </button>
                        @foreach($categories as $category)
                        <div class="border-bottom">

                            <button class="btn d-flex align-items-center justify-content-between w-100 rounded-0 py-3 ps-3 pe-3 text-secondary fw-semibold fs-7 border-0"
                                data-bs-toggle="collapse"
                                data-bs-target="#cat-{{ $category->id }}"
                                aria-expanded="false">

                                <span class="d-flex align-items-center text-start">
                                    <span class="d-inline-flex justify-content-center align-items-center me-3 text-primary fs-5" style="width: 2.5rem;">
                                        <i class="fas {{ $category->icon_class ?? 'fa-tag' }}"></i>
                                    </span>
                                    <span class="text-dark-emphasis text-wrap">{{ $category->name }}</span>
                                </span>

                                <i class="fas fa-plus fa-xs text-muted ms-auto flex-shrink-0"></i>
                            </button>

                            <div class="collapse ps-5 pb-3" id="cat-{{ $category->id }}">
                                @forelse($category->subcategories as $sub)
                                <a href="#" class="d-block text-decoration-none py-1 text-secondary small">
                                    <i class="fas fa-chevron-right text-primary small"></i>
                                    <span>{{ $sub->name }}</span>
                                </a>
                                @empty
                                <span class="d-block text-muted small py-1 fst-italic">No subcategories</span>
                                @endforelse
                            </div>

                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-9">

                <div class="d-flex justify-content-between align-items-center mb-4 pt-2">
                    <h4 class="fw-bold mb-0 text-primary">
                        Featured Products
                    </h4>
                    <a href="#" class="text-decoration-none fw-semibold d-flex align-items-center gap-1 text-primary small">
                        View All Products <i class="fas fa-chevron-right fs-8"></i>
                    </a>
                </div>

                <div class="row g-3">
                    @foreach($products as $product)
                    <x-product-card :product="$product" />
                    @endforeach
                </div>

            </div>
        </div>
    </section>


    <section class="container pb-5">

        <div class="row g-4 mb-5">

            <div class="col-xl-6">
                <div class="card h-100 border-0 rounded-3 p-4 justify-content-between alert alert-info text-dark">

                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h4 class="fw-bold mb-1 text-primary">Home Sleep Study</h4>
                            <p class="fw-semibold mb-0 text-primary small">Book a Sleep Test in 3 Easy Steps</p>
                        </div>
                        <div class="w-25">
                            <img src="https://images.unsplash.com/photo-1603398938378-e54eab446dde?w=150&q=80" alt="White Sleep Device" class="w-100 object-fit-contain">
                        </div>
                    </div>

                    <div class="row align-items-center justify-content-between text-center g-0 my-3">
                        <div class="col px-1">
                            <i class="far fa-calendar-alt fs-4 mb-2 text-primary"></i>
                            <h6 class="fw-bold mb-1 small">1. Schedule Online</h6>
                            <p class="text-muted mb-0 mx-auto small">Book your sleep test online in minutes</p>
                        </div>
                        <div class="col-auto text-muted opacity-50 px-2 small"><i class="fas fa-chevron-right"></i></div>
                        <div class="col px-1">
                            <i class="fas fa-shipping-fast fs-4 mb-2 text-primary"></i>
                            <h6 class="fw-bold mb-1 small">2. Device Delivered</h6>
                            <p class="text-muted mb-0 mx-auto small">We deliver the device to your home</p>
                        </div>
                        <div class="col-auto text-muted opacity-50 px-2 small"><i class="fas fa-chevron-right"></i></div>
                        <div class="col px-1">
                            <i class="far fa-user fs-4 mb-2 text-primary"></i>
                            <h6 class="fw-bold mb-1 small">3. Report & Consult</h6>
                            <p class="text-muted mb-0 mx-auto small">Get report & consult with our expert</p>
                        </div>
                    </div>

                    <div class="border-top border-2 border-white pt-3 mt-3">
                        <div class="row g-2 text-start justify-content-between align-items-center small fw-semibold">
                            <div class="col-auto d-flex align-items-center gap-1"><i class="fas fa-home text-secondary"></i><span class="text-dark">Home-based Testing</span></div>
                            <div class="col-auto d-flex align-items-center gap-1"><i class="far fa-file-alt text-secondary"></i><span class="text-dark">Certified Analysis</span></div>
                            <div class="col-auto d-flex align-items-center gap-1"><i class="fas fa-shield-alt text-secondary"></i><span class="text-dark">Quick Reports</span></div>
                            <div class="col-auto d-flex align-items-center gap-1"><i class="far fa-comments text-secondary"></i><span class="text-dark">Teleconsultation Support</span></div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-xl-6">
                <div class="card h-100 border-0 rounded-3 p-4 justify-content-between alert alert-success text-dark">

                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h4 class="fw-bold mb-1 text-success">CPAP & BiPAP Rental Solutions</h4>
                            <p class="fw-semibold mb-0 text-success small">Flexible Rental Plans for Your Comfort</p>
                        </div>
                        <div class="w-25">
                            <img src="https://images.unsplash.com/photo-1551076805-e1869033e561?w=150&auto=format&fit=crop&q=60"
                                alt="White Sleep Device"
                                class="img-fluid rounded-3">
                        </div>
                    </div>

                    <div class="row g-2 my-2 text-center">
                        <div class="col-3">
                            <div class="bg-white rounded-3 p-2 border border-light-subtle shadow-sm h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="far fa-calendar-check fs-4 mb-2 text-success"></i>
                                <div class="fw-bold text-success small">Daily</div>
                                <div class="text-muted small">Rental</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-white rounded-3 p-2 border border-light-subtle shadow-sm h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="far fa-calendar-alt fs-4 mb-2 text-success"></i>
                                <div class="fw-bold text-success small">Weekly</div>
                                <div class="text-muted small">Rental</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-white rounded-3 p-2 border border-light-subtle shadow-sm h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-calendar-day fs-4 mb-2 text-success"></i>
                                <div class="fw-bold text-success small">Monthly</div>
                                <div class="text-muted small">Rental</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="bg-white rounded-3 p-2 border border-light-subtle shadow-sm h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="far fa-file-alt fs-4 mb-2 text-success"></i>
                                <div class="fw-bold text-success small">Trial</div>
                                <div class="text-muted small">Program</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3 px-3 py-2 border border-light-subtle shadow-sm mt-3">
                        <div class="row g-1 text-center justify-content-between align-items-center small fw-semibold text-secondary">
                            <div class="col d-flex align-items-center justify-content-center gap-1 border-end border-light-subtle"><i class="fas fa-tags text-success"></i><span class="white-space-nowrap">Affordable Trial</span></div>
                            <div class="col d-flex align-items-center justify-content-center gap-1 border-end border-light-subtle"><i class="fas fa-truck text-success"></i><span class="white-space-nowrap">Home Delivery</span></div>
                            <div class="col d-flex align-items-center justify-content-center gap-1 border-end border-light-subtle"><i class="fas fa-wrench text-success"></i><span class="white-space-nowrap">Installation Support</span></div>
                            <div class="col d-flex align-items-center justify-content-center gap-1"><i class="fas fa-user-cog text-success"></i><span class="white-space-nowrap">Technical Assistance</span></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <section class="container mb-5">
            <h3 class="fw-bold text-center text-primary mb-5">
                Why Choose SleepWell?
            </h3>

            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-4 text-center">

                <div class="col">
                    <div class="d-flex flex-column align-items-center">
                        <i class="fas fa-shield-alt text-primary fs-2 mb-3"></i>
                        <h6 class="fw-semibold mb-0 small">Genuine Products</h6>
                    </div>
                </div>

                <div class="col">
                    <div class="d-flex flex-column align-items-center">
                        <i class="fas fa-user-md text-primary fs-2 mb-3"></i>
                        <h6 class="fw-semibold mb-0 small">Expert Sleep Consultants</h6>
                    </div>
                </div>

                <div class="col">
                    <div class="d-flex flex-column align-items-center">
                        <i class="fas fa-truck text-primary fs-2 mb-3"></i>
                        <h6 class="fw-semibold mb-0 small">Pan-India Delivery</h6>
                    </div>
                </div>

                <div class="col">
                    <div class="d-flex flex-column align-items-center">
                        <i class="fas fa-wallet text-primary fs-2 mb-3"></i>
                        <h6 class="fw-semibold mb-0 small">Easy Financing</h6>
                    </div>
                </div>

                <div class="col">
                    <div class="d-flex flex-column align-items-center">
                        <i class="fas fa-tools text-primary fs-2 mb-3"></i>
                        <h6 class="fw-semibold mb-0 small">Installation & Training</h6>
                    </div>
                </div>

                <div class="col">
                    <div class="d-flex flex-column align-items-center">
                        <i class="fas fa-headset text-primary fs-2 mb-3"></i>
                        <h6 class="fw-semibold mb-0 small">After-Sales Support</h6>
                    </div>
                </div>

            </div>
        </section>

        @php
    $testimonials = [
        [
            'name' => 'Rajesh Kumar',
            'city' => 'Delhi',
            'review' => 'The home sleep test was so convenient and the report was ready quickly. The team guided me throughout the therapy.',
        ],
        [
            'name' => 'Anita Sharma',
            'city' => 'Bangalore',
            'review' => 'Rented a CPAP machine initially and later bought one. Excellent service, installation and support team.',
        ],
        [
            'name' => 'Vikram Mehta',
            'city' => 'Mumbai',
            'review' => 'Great experience with SleepWell. Genuine products and helpful sleep experts. Highly recommended.',
        ],
        [
            'name' => 'Priya Nair',
            'city' => 'Chennai',
            'review' => 'Affordable rental plans and prompt delivery. The consultation process was smooth and helpful.',
        ],
        [
            'name' => 'Arjun Reddy',
            'city' => 'Hyderabad',
            'review' => 'The support team helped me choose the right mask and setup my device at home.',
        ],
    ];

    $slides = array_chunk($testimonials, 3);
@endphp

<section class="mb-5 text-center p-5 card border-0 shadow-sm">
    <h3 class="fw-bold text-primary mb-5">What Our Customers Say</h3>

    <div
        id="testimonialCarousel"
        class="carousel slide"
        data-bs-ride="carousel"
        data-bs-interval="5000"
        style="--bs-carousel-indicator-active-bg: var(--bs-primary);">

        <div class="carousel-inner">

            @foreach($slides as $index => $slide)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="row g-4">

                        @foreach($slide as $testimonial)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="bg-white border rounded-4 shadow-sm p-4 h-100 text-start testimonial-card">

                                    <i class="fas fa-quote-left text-primary fs-4 mb-3"></i>

                                    <p class="text-muted small mb-4">
                                        {{ $testimonial['review'] }}
                                    </p>

                                    <div class="text-warning mb-3">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>

                                    <h6 class="fw-bold mb-1">
                                        {{ $testimonial['name'] }}
                                    </h6>

                                    <p class="small text-muted mb-0">
                                        {{ $testimonial['city'] }}
                                    </p>

                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            @endforeach

        </div>

        @if(count($slides) > 1)
            <div class="carousel-indicators position-static mt-4 mb-0 gap-2">

                @foreach($slides as $index => $slide)
                    <button
                        type="button"
                        data-bs-target="#testimonialCarousel"
                        data-bs-slide-to="{{ $index }}"
                        class="{{ $index === 0 ? 'active' : '' }}"
                        aria-label="Slide {{ $index + 1 }}">
                    </button>
                @endforeach

            </div>
        @endif

    </div>
</section>

        <section class="bg-primary rounded-3 shadow-sm p-4 mb-5">

    <div class="row align-items-center g-3">

        {{-- Left Section --}}
        <div class="col-lg-auto text-white">

            <h4 class="fw-bold mb-4">
                Need Help? We're Here for You!
            </h4>

            <div class="d-flex flex-wrap align-items-center gap-4">

                <div class="d-flex align-items-center gap-2">

                    <div class="bg-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width: 44px; height: 44px;">
                        <i class="fas fa-phone-alt fs-6"></i>
                    </div>

                    <div>
                        <div class="small text-white-50">Call Us</div>
                        <div class="fw-semibold small text-nowrap">
                            1800-123-4567
                        </div>
                    </div>

                </div>

                <div class="d-flex align-items-center gap-2">

                    <div class="bg-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width: 44px; height: 44px;">
                        <i class="fab fa-whatsapp fs-6"></i>
                    </div>

                    <div>
                        <div class="small text-white-50">WhatsApp</div>
                        <div class="fw-semibold small text-nowrap">
                            98765 43210
                        </div>
                    </div>

                </div>

                <div class="d-flex align-items-center gap-2">

                    <div class="bg-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width: 44px; height: 44px;">
                        <i class="fas fa-envelope fs-6"></i>
                    </div>

                    <div>
                        <div class="small text-white-50">Email Us</div>
                        <div class="fw-semibold small text-nowrap">
                            support@sleepwell.com
                        </div>
                    </div>

                </div>

            </div>

        </div>

        {{-- Right Section --}}
        <div class="col d-flex justify-content-lg-end">

            <div class="bg-white rounded-3 p-3 p-lg-4 w-100">

                <h4 class="fw-bold text-primary mb-3">
                    Book an Appointment
                </h4>

                <form>
                    <div class="row g-2 align-items-center">

                        <div class="col-md">
                            <input
                                type="text"
                                class="form-control form-control-sm"
                                placeholder="Your Name">
                        </div>

                        <div class="col-md">
                            <input
                                type="tel"
                                class="form-control form-control-sm"
                                placeholder="Your Phone Number">
                        </div>

                        <div class="col-md">
                            <select class="form-select form-select-sm">
                                <option selected>Select Service</option>
                                <option>CPAP Consultation</option>
                                <option>Sleep Study</option>
                                <option>Rental Solutions</option>
                            </select>
                        </div>

                        <div class="col-md-auto">
                            <button
                                type="submit"
                                class="btn btn-primary btn-sm px-4 w-100 text-nowrap">
                                Book Now
                            </button>
                        </div>

                    </div>
                </form>

            </div>

        </div>

    </div>

</section>

<section class="bg-white rounded-3 px-3 py-4 mb-5">

    <div class="row row-cols-2 row-cols-lg-5 g-3">

        <div class="col">
            <div class="d-flex align-items-start gap-2 h-100" style="min-height: 56px;">

                <i class="bi bi-credit-card text-primary fs-4 flex-shrink-0"></i>

                <div>
                    <div class="fw-semibold small text-dark">
                        Secure
                    </div>

                    <div class="small text-secondary">
                        Payments
                    </div>
                </div>

            </div>
        </div>

        <div class="col">
            <div class="d-flex align-items-start gap-2 h-100" style="min-height: 56px;">

                <i class="bi bi-arrow-repeat text-primary fs-4 flex-shrink-0"></i>

                <div>
                    <div class="fw-semibold small text-dark">
                        7 Days Easy
                    </div>

                    <div class="small text-secondary">
                        Returns
                    </div>
                </div>

            </div>
        </div>

        <div class="col">
            <div class="d-flex align-items-start gap-2 h-100" style="min-height: 56px;">

                <i class="bi bi-wallet2 text-primary fs-4 flex-shrink-0"></i>

                <div>
                    <div class="fw-semibold small text-dark">
                        EMI Options
                    </div>

                    <div class="small text-secondary">
                        Available
                    </div>
                </div>

            </div>
        </div>

        <div class="col">
            <div class="d-flex align-items-start gap-2 h-100" style="min-height: 56px;">

                <i class="bi bi-shield-check text-primary fs-4 flex-shrink-0"></i>

                <div>
                    <div class="fw-semibold small text-dark">
                        100% Genuine
                    </div>

                    <div class="small text-secondary">
                        Products
                    </div>
                </div>

            </div>
        </div>

        <div class="col">
            <div class="d-flex align-items-start gap-2 h-100" style="min-height: 56px;">

                <i class="bi bi-shield-lock text-primary fs-4 flex-shrink-0"></i>

                <div>
                    <div class="fw-semibold small text-dark">
                        Privacy
                    </div>

                    <div class="small text-secondary">
                        Protected
                    </div>
                </div>

            </div>
        </div>

    </div>

</section>

    </section>
</x-layout>