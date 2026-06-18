@props(['product'])
<div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-4">
    <!-- Main Card Body Frame Base with soft border lines -->
    <div class="card h-100 border border-1 border-light-subtle rounded-2 shadow-sm position-relative d-flex flex-column bg-white">
        
        <!-- DYNAMIC TOP LEFT BADGE HOOK LAYER -->
        <!-- CLEAN COMPONENT FIX: Replaced complex inline rules with a clean cached stylesheet class -->
        @if($product->badge_text)
            <span class="badge {{ $product->badge_color ?? 'bg-success' }} position-absolute fw-bold tracking-wider shadow-sm z-3 badge-premium-hanging">
                {{ $product->badge_text }}
            </span>
        @endif

        <!-- IMMUTABLE CENTER SIZED PRODUCT GRAPHIC ANCHOR -->
        <!-- Locked to a uniform 4:3 canvas size using pure Bootstrap 5 ratio utilities -->
        <div class="ratio ratio-4x3 bg-white rounded-top-2 overflow-hidden p-3">
            <a href="#" class="d-flex align-items-center justify-content-center h-100 w-100 open-product-modal pe-auto text-decoration-none"
               data-bs-toggle="modal" 
               data-bs-target="#productModal"
               data-id="{{ $product->id }}"
               data-title="{{ $product->title }}"
               data-price="{{ $product->price }}"
               data-description="{{ $product->description ?? 'No direct product summary context supplied.' }}"
               data-image="{{ $product->image_url }}"
               data-stock="{{ $product->stock ?? 20 }}"
               data-dealers="{{ json_encode($product->dealers ?? []) }}">
                <img src="{{ $product->image_url }}" 
                     class="img-fluid object-fit-contain w-100 h-100 p-2" 
                     alt="{{ $product->title }}">
            </a>
        </div>

        <!-- PRODUCT METADATA INFO REGION CONTAINER -->
        <div class="card-body p-3 d-flex flex-column justify-content-between pt-0 flex-grow-1">
            
            <div class="d-flex flex-column">
                <!-- Title Label Heading mapped securely using a clean link styling pattern -->
                <a href="#" class="fw-bold text-dark text-decoration-none lh-base mb-3 d-block open-product-modal pe-auto fs-6 link-underline-opacity-0" 
                    data-bs-toggle="modal" 
                    data-bs-target="#productModal"
                    data-id="{{ $product->id }}"
                    data-title="{{ $product->title }}"
                    data-price="{{ $product->price }}"
                    data-description="{{ $product->description ?? 'No direct product summary context supplied.' }}"
                    data-image="{{ $product->image_url }}"
                    data-stock="{{ $product->stock ?? 20 }}"
                    data-dealers="{{ json_encode($product->dealers ?? []) }}">
                    {{ $product->title }}
                </a>

                <!-- FEATURE BULLET SECTIONS DYNAMIC ARRAY GENERATOR LOOP -->
                @if(is_array($product->key_features))
                    <ul class="list-unstyled mb-3 text-secondary ps-0 small">
                        @foreach($product->key_features as $feature)
                            <li class="mb-1 d-flex align-items-start">
                                <span class="me-2 text-dark-emphasis opacity-50">•</span>
                                <span class="text-muted">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- CURRENCY PRICING LOGIC MATRIX LAYERING -->
            <div class="mt-auto mb-3">
                <h4 class="fw-bold text-primary mb-0">
                    @if($product->id == 4) From @endif ₹{{ number_format($product->price, 0) }}
                </h4>
                @if($product->emi_starting_price)
                    <div class="text-muted small fw-medium mt-1">
                        EMI from ₹{{ number_format($product->emi_starting_price, 0) }}/month
                    </div>
                @endif
            </div>

        </div>

        <!-- STACKED TRANSACTION INTERFACE BUTTON FOOTER ROW BLOCKS -->
        <div class="card-footer bg-white border-0 p-3 pt-0 mt-auto">
            <div class="d-flex flex-column gap-2">
                <!-- Add to Cart Primary Button -->
                <button class="btn btn-primary w-100 add-to-cart-btn fw-semibold rounded-1 py-2 d-flex align-items-center justify-content-center gap-2 text-white border-0" 
                        data-id="{{ $product->id }}">
                    <i class="bi bi-cart3 fs-6"></i> Add to Cart
                </button>
                
                <!-- Request Demo Secondary Outline Button -->
                <button class="btn btn-outline-primary border-light-subtle text-primary w-100 fw-semibold rounded-1 py-2 small">
                    Request Demo
                </button>
            </div>
        </div>

    </div>
</div>