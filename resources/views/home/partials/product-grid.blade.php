@if($products->isEmpty())
    <div class="col-12 text-center py-5">
        <div class="text-muted fs-5 fst-italic">No medical equipment matches this category filter currently.</div>
    </div>
@else
    @foreach($products as $product)
        <x-product-card :product="$product" />
    @endforeach
@endif