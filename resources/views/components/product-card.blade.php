@props(['product'])

<div class="card h-100">
    <img src="{{ asset('storage/' . $product->image_path) }}" class="card-img-top"
         alt="{{ $product->title }}">
    <div class="card-body d-flex flex-column">
        <h6 class="card-title">{{ $product->title }}</h6>
        <p class="card-text fw-bold mt-auto">{{ number_format($product->price, 2) }} </p>
        <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary w-100 mt-2">Detaljnije</a>
    </div>
</div>
