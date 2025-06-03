@extends('layouts.app')

@section('content')

    <div class="row flex-column flex-md-row">
        <div class="col-md-3 mb-4">
            <h5>Kategorije</h5>
            <div class="list-group">
                @foreach ($categories as $category)
                    <x-category-card :category="$category" />
                @endforeach
            </div>
        </div>

        <div class="col">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                @foreach ($products as $product)
                    <div class="col">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
