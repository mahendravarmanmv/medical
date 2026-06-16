@props(['product'])
<div class="col-md-6 col-xl-4 mb-4">
    <div class="card border-0 shadow-sm h-100">
        <img src="{{ $product->image_url }}" class="card-img-top product-img open-product-modal" data-id="{{ $product->id }}" alt="{{ $product->title }}">
        <div class="card-body">
            <h5 class="fw-bold">{{ $product->title }}</h5>
            <p class="text-muted small">{{ Str::limit($product->description, 60) }}</p>
            <h4 class="text-primary fw-bold">₹{{ number_format($product->price, 2) }}</h4>
        </div>
        <div class="card-footer bg-white border-0">
            <div class="d-flex gap-2">
                <input type="number" class="form-control quantity-input" value="1" min="1" id="qty-{{ $product->id }}">
                <button class="btn btn-dark w-100 add-to-cart-btn" data-id="{{ $product->id }}"><i class="bi bi-cart-plus"></i> Add</button>
            </div>
        </div>
    </div>
</div>