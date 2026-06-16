<x-layout>
    <section class="py-4">
        <div class="container">
            <div id="dealerSlider" class="carousel slide carousel-fade rounded overflow-hidden shadow" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30" class="d-block w-100 dealer-slider-img" alt="">
                        <div class="carousel-caption text-start">
                            <h2 class="fw-bold">Latest Smart Watches</h2>
                            <p>Best dealer offers available now</p>
                            <a href="#" class="btn btn-primary">Shop Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-dark text-white fw-bold">Categories</div>
                        <div class="card-body p-0">
                            @foreach($categories as $category)
                                <div class="border-bottom">
                                    <button class="btn d-flex justify-content-between align-items-center w-100 rounded-0 py-3" data-bs-toggle="collapse" data-bs-target="#cat-{{ $category->id }}">
                                        <span><i class="bi {{ $category->icon_class }}"></i> {{ $category->name }}</span>
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                    <div class="collapse ps-3 pb-3" id="cat-{{ $category->id }}">
                                        @foreach($category->subcategories as $sub)
                                            <a href="#" class="d-block text-decoration-none py-1 text-secondary">{{ $sub->name }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold mb-0">Featured Products</h3>
                        <form method="GET" action="{{ route('home') }}" id="sort-filter-form">
                            <select name="sort" class="form-select w-auto" onchange="document.getElementById('sort-filter-form').submit();">
                                <option value="latest" {{ $sortOption === 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="low_price" {{ $sortOption === 'low_price' ? 'selected' : '' }}>Low Price</option>
                                <option value="high_price" {{ $sortOption === 'high_price' ? 'selected' : '' }}>High Price</option>
                            </select>
                        </form>
                    </div>
                    <div class="row">
                        @foreach($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold">Product Quick View</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-center">
                            <img src="" id="modalProductImage" class="img-fluid rounded shadow-sm" alt="">
                        </div>
                        <div class="col-md-6">
                            <h3 class="fw-bold text-dark" id="modalProductTitle">--</h3>
                            <h4 class="text-primary fw-bold mb-3" id="modalProductPrice">₹0.00</h4>
                            <p class="text-muted" id="modalProductDescription">Loading verified data metrics...</p>
                            <div class="d-flex gap-2 mt-4">
                                <input type="number" class="form-control w-25" value="1" min="1" id="modal-qty">
                                <button class="btn btn-dark w-75 add-to-cart-btn" data-id="">Add To Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>