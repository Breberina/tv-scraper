@extends('layouts.app')

@section('content')

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 rounded shadow-sm px-3">
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.index') }}">Početna</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.index') }}">Sve kategorije</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="row flex-column flex-md-row">
        <div class="col-md-3 mb-4">
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
                {{ $products->withQueryString()->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
