@props(['category'])

<a href="{{ route('products.index', ['category' => $category->id]) }}"
   class="list-group-item list-group-item-action text-center border-0">
    <div class="d-flex flex-column align-items-center">
        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
             style="width: 80px; height: 80px; overflow: hidden;">
            <img src="{{ asset('storage/' . $category->image) }}"
                 alt="{{ $category->title }}"
                 style="width: 100%; height: auto;">
        </div>
        <span class="mt-2">{{ $category->title }}</span>
    </div>
</a>
