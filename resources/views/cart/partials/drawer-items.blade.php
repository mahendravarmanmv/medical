@if(count($cart) > 0)
    <div class="flex-grow-1 overflow-y-auto mb-3" style="max-height: 70vh;">
        @foreach($cart as $id => $details)
            <div class="d-flex align-items-center justify-content-between p-3 mb-2 bg-white rounded shadow-sm border-start border-primary border-3">
                
                <img src="{{ $details['image'] ?? $details['image_url'] ?? asset('images/default-product.png') }}" 
                     class="rounded border bg-light" 
                     style="width: 50px; height: 50px; object-fit: contain; padding: 2px;" 
                     alt="{{ $details['title'] ?? 'Medical Equipment' }}">
                
                <div class="ms-3 flex-grow-1">
                    <h6 class="fw-bold mb-0 text-truncate" style="max-width: 140px;" title="{{ $details['title'] ?? '' }}">
                        {{ $details['title'] ?? 'Product' }}
                    </h6>
                    @if(!empty($details['package_name']) && $details['package_name'] !== 'Standard Pack')
                        <div class="text-primary extra-small fw-semibold mb-0.5" style="font-size: 0.75rem;">{{ $details['package_name'] }}</div>
                    @endif
                    <small class="text-muted">₹{{ number_format($details['price'] ?? 0, 2) }} × {{ $details['quantity'] ?? 1 }}</small>
                </div>
                
                <div>
                    <button class="btn btn-sm btn-outline-danger border-0 remove-cart-item-btn shadow-none" data-id="{{ $id }}">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="border-top pt-3 bg-light p-3 rounded mt-auto">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0 text-secondary">Total Amount:</h5>
            <h4 class="fw-bold text-primary mb-0">₹{{ number_format($total ?? 0, 2) }}</h4>
        </div>
        <a href="{{ route('checkout.view') }}" class="btn btn-dark w-100 py-2.5 rounded-pill fw-bold shadow-sm text-white d-flex align-items-center justify-content-center text-decoration-none gap-1">
            Proceed To Checkout <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
@else
    <div class="text-center py-5 my-auto">
        <i class="bi bi-cart-x display-1 text-muted opacity-25"></i>
        <p class="text-muted mt-3 fw-medium">Your shopping cart is currently empty.</p>
        <button type="button" class="btn btn-outline-dark btn-sm rounded-pill px-4 mt-2" data-bs-dismiss="offcanvas">Continue Shopping</button>
    </div>
@endif