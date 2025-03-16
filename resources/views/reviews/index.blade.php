@extends('layouts.base')

@section('content')
<div class="container my-5">
    <h2>Products You Can Review</h2>
    
    <div class="products-list mb-4">
        @if($products->count() > 0)
            @foreach($products as $product)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <a href="{{ route('reviews.index', ['product' => $product->id]) }}" class="btn btn-primary">Write a Review</a>
                    </div>
                </div>
            @endforeach
        @else
            <p>You have no products to review.</p>
        @endif
    </div>
</div>
@endsection