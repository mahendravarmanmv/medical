@props(['product'])
<div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-4">
    <!-- Main Card Body Frame Base with soft border lines -->
    <div class="card h-100 border border-1 border-light-subtle rounded-2 shadow-sm position-relative d-flex flex-column bg-white">
        
        <!-- DYNAMIC TOP LEFT BADGE HOOK LAYER -->
        @if($product->badge_text)
            <span class="badge {{ $product->badge_color ?? 'bg-secondary' }} position-absolute top-0 start-0 m-3 px-2 py-1 rounded-1 fw-bold tracking-wider fs-8 small shadow-sm" style="z-index: 5;">
                {{ $product->badge_text }}
            </span>
        @endif

        <!-- IMMUTABLE CENTER SIZED PRODUCT GRAPHIC ANCHOR -->
        <div class="p-4 d-flex align-items-center justify-content-center bg-white rounded-top-2" style="height: 200px;">
            <img src="{{ $product->image_url }}" 
                 class="img-fluid open-product-modal object-fit-contain h-100 w-100" 
                 data-id="{{ $product->id }}" 
                 style="cursor: pointer; transition: transform 0.2s;"
                 onmouseover="this.style.transform='scale(1.03)'"
                 onmouseout="this.style.transform='scale(1.0)'"
                 alt="{{ $product->title }}">
        </div>

        <!-- PRODUCT METADATA INFO REGION CONTAINER -->
        <div class="card-body p-3 d-flex flex-column justify-content-between pt-0">
            
            <div>
                <!-- Title Label Heading -->
                <h6 class="fw-bold text-dark lh-base mb-3 open-product-modal" data-id="{{ $product->id }}" style="cursor: pointer; min-height: 44px;">
                    {{ $product->title }}
                </h6>

                <!-- FEATURE BULLET SECTIONS DYNAMIC ARRAY GENERATOR LOOP -->
                @if(is_array($product->key_features))
                    <ul class="list-unstyled mb-4 text-secondary ps-0" style="font-size: 0.825rem; min-height: 88px;">
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
                    <div class="text-muted small fw-medium mt-1" style="font-size: 0.775rem;">
                        EMI from ₹{{ number_format($product->emi_starting_price, 0) }}/month
                    </div>
                @endif
            </div>

        </div>

        <!-- STACKED TRANSACTION INTERFACE BUTTON FOOTER ROW BLOCKS -->
        <div class="card-footer bg-white border-0 p-3 pt-0 mt-auto">
            <div class="d-flex flex-column gap-2">
                <!-- Add to Cart Primary Button -->
                <button class="btn btn-primary w-100 add-to-cart-btn fw-semibold rounded-1 py-2 d-flex align-items-center justify-content-center gap-2 text-white" 
                        data-id="{{ $product->id }}" 
                        style="background-color: #0b4d8c; border-color: #0b4d8c; font-size: 0.875rem;">
                    <i class="bi bi-cart3 fs-6"></i> Add to Cart
                </button>
                
                <!-- Request Demo Secondary Outline Button -->
                <button class="btn btn-outline-secondary w-100 fw-semibold rounded-1 py-2 text-primary" 
                        style="border-color: #cbd5e1; font-size: 0.875rem;"
                        onmouseover="this.style.backgroundColor='#f8fafc'"
                        onmouseout="this.style.backgroundColor='transparent'">
                    Request Demo
                </button>
            </div>
        </div>

    </div>
</div>