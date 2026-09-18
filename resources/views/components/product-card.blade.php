@props(['product'])
<div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-4">
    <div class="card h-100 border border-1 border-light-subtle rounded-2 shadow-sm position-relative d-flex flex-column bg-white">

        @if($product->badge_text)
        <span class="badge {{ $product->badge_color ?? 'bg-success' }} position-absolute fw-bold tracking-wider shadow-sm z-3 badge-premium-hanging">
            {{ $product->badge_text }}
        </span>
        @endif

        <div class="ratio ratio-4x3 bg-white rounded-top-2 overflow-hidden p-3">
            <a href="#" class="d-flex align-items-center justify-content-center h-100 w-100 open-product-modal pe-auto text-decoration-none"
                data-bs-toggle="modal"
                data-bs-target="#productModal"
                data-id="{{ $product->id }}"
                data-title="{{ $product->title }}"
                data-price="{{ $product->price }}"
                data-description="{{ $product->description ?? 'No direct product summary context supplied.' }}"
                data-image="{{ $product->image_url }}"
                data-stock="{{ $product->stock_quantity }}"
                data-dealers="{{ json_encode($product->dealers ?? []) }}">
                <img src="{{ $product->image_url }}"
                    class="img-fluid object-fit-contain w-100 h-100 p-2"
                    alt="{{ $product->title }}">
            </a>
        </div>

        <div class="card-body p-3 d-flex flex-column justify-content-between pt-0 flex-grow-1">

            <div class="d-flex flex-column">
                <a href="#" class="fw-bold text-dark text-decoration-none lh-base mb-3 d-block open-product-modal pe-auto fs-6 link-underline-opacity-0"
                    data-bs-toggle="modal"
                    data-bs-target="#productModal"
                    data-id="{{ $product->id }}"
                    data-title="{{ $product->title }}"
                    data-price="{{ $product->price }}"
                    data-description="{{ $product->description ?? 'No direct product summary context supplied.' }}"
                    data-image="{{ $product->image_url }}"
                    data-stock="{{ $product->stock_quantity }}"
                    data-dealers="{{ json_encode($product->dealers ?? []) }}">
                    {{ $product->title }}
                </a>

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

            <div class="mt-auto mb-3">
                @php
                // 1. Establish fallbacks to baseline model values
                $originalPrice = (float) $product->price;
                $displayPrice = $originalPrice;
                $hasDealerPrice = false;

                // 2. Extract the eager-loaded dealer pivot override model
                $matchedDealer = $product->dealers->first();
                if ($matchedDealer && isset($matchedDealer->pivot->price)) {
                $displayPrice = (float) $matchedDealer->pivot->price;
                $hasDealerPrice = true;
                }

                // 3. Compute the drop percentage
                $discountPercentage = 0;
                if ($originalPrice > $displayPrice) {
                $discountPercentage = round((($originalPrice - $displayPrice) / $originalPrice) * 100);
                }
                @endphp

                @if($hasDealerPrice && $discountPercentage > 0)
                <div class="d-flex align-items-baseline flex-wrap gap-2">
                    <span class="h4 fw-bold text-primary mb-0">
                        ₹{{ number_format($displayPrice, 0) }}
                    </span>
                    <span class="text-muted text-decoration-line-through small">
                        ₹{{ number_format($originalPrice, 0) }}
                    </span>
                </div>
                <div class="text-success small fw-bold mt-1" style="font-size: 0.8rem;">
                    Discount: {{ $discountPercentage }}% off
                </div>
                @else
                <h4 class="fw-bold text-primary mb-0">
                    @if($product->id == 4) From @endif ₹{{ number_format($originalPrice, 0) }}
                </h4>
                @endif

                @if($product->emi_starting_price)
                <div class="text-muted small fw-medium mt-1">
                    EMI from ₹{{ number_format($product->emi_starting_price, 0) }}/month
                </div>
                @endif
            </div>

        </div>

        <div class="card-footer bg-white border-0 p-3 pt-0 mt-auto">
            <div class="d-flex flex-column gap-2">
			@if($product->stock_quantity === null || $product->stock_quantity > 0)

			<button
			class="btn btn-primary w-100 add-to-cart-btn fw-semibold rounded-1 py-2 d-flex align-items-center justify-content-center gap-2 text-white border-0"
			data-id="{{ $product->id }}"
			>
			<i class="bi bi-cart3 fs-6"></i> Add to Cart
			</button>

			@else

			<button
			type="button"
			class="btn btn-secondary w-100 fw-semibold rounded-1 py-2 d-flex align-items-center justify-content-center gap-2"
			disabled
			>
			<i class="bi bi-x-circle fs-6"></i> Out of Stock
			</button>

			@endif

                <button class="btn btn-danger text-white border border-light-subtle w-100 fw-semibold rounded-1 py-2 small">
                    Request Demo
                </button>
            </div>
        </div>

    </div>
</div>