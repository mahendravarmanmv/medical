@if($products->isEmpty())
    <div class="col-12 text-center py-5">
        <div class="text-muted fs-5 fst-italic">
            No medical equipment matches this category filter currently.
        </div>
    </div>
@else
    @foreach($products as $product)
        @php
            $isDeliverable = !session()->has('selected_pincode_id')
                || $product->pincodes->isNotEmpty();
        @endphp

        <x-product-card
            :product="$product"
            :is-deliverable="$isDeliverable"
        />
    @endforeach
@endif