@extends('layouts.base')

@section('title')
    HomeEssence Shopping Cart
@endsection

@section('body')
    {{-- {{ dd($products) }} --}}
    @if (Session::has('cart'))
        <div class="row">
            <div class="col-sm-6 col-md-6 col-md-offset-3 col-sm-offset-3">
                <ul class="list-group">
                    @foreach ($products as $product)
                        <li class="list-group-item">
                            <span class="badge rounded-pill bg-danger">{{ $product['qty'] }}</span>
                            
                            {{-- Display the new fields: name and category --}}
                            <strong>{{ $product['item']->name }}</strong>
                            <br>
                            <small class="text-muted">Category: {{ $product['item']->category }}</small>
                            <br>
                            <p>{{ $product['item']->description }}</p>
                            
                            <span class="label label-success">{{ $product['price'] }}</span>
                            
                            {{-- Carousel for multiple images --}}
                            <div id="carousel-{{ $product['item']->item_id }}" class="carousel slide mt-2" data-bs-interval="0" style="width: 100px; height: 100px;">
                                <div class="carousel-inner">
                                    @if($product['item']->productImages && $product['item']->productImages->count() > 0)
                                        @foreach($product['item']->productImages as $key => $img)
                                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                                <img src="{{ Storage::url($img->image_path) }}" alt="Product Image" class="d-block w-100" style="width:100px; height:100px;">
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="carousel-item active">
                                            <img src="/images/default.png" alt="No image available" class="d-block w-100" style="width:100px; height:100px;">
                                        </div>
                                    @endif
                                </div>
                                @if($product['item']->productImages && $product['item']->productImages->count() > 1)
                                    <a class="carousel-control-prev" href="#carousel-{{ $product['item']->item_id }}" role="button" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carousel-{{ $product['item']->item_id }}" role="button" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </a>
                                @endif
                            </div>
                            
                            <div class="dropdown mt-2">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton{{ $product['item']->item_id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                  Choose
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $product['item']->item_id }}">
                                   <li><a class="dropdown-item" href="{{ route('reduceByOne', $product['item']->item_id) }}">Reduce By 1</a></li> 
                                   <li><a class="dropdown-item" href="{{ route('removeItem', $product['item']->item_id) }}">Reduce All</a></li>
                                </ul>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-6 col-md-6 col-md-offset-3 col-sm-offset-3">
                <strong>Total: {{ $totalPrice }}</strong>
            </div>
        </div>
        <hr>
        <div class="row mt-3">
            <div class="col-sm-6 col-md-6 col-md-offset-3 col-sm-offset-3">
                <a href="{{ route('checkout') }}" type="button" class="btn btn-success">Checkout</a>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-sm-6 col-md-6 col-md-offset-3 col-sm-offset-3">
                <h2>No Items in Cart!</h2>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function(){
            var carousels = document.querySelectorAll('.carousel');
            carousels.forEach(function(carousel) {
                new bootstrap.Carousel(carousel, {
                    interval: false,
                    pause: 'hover'
                });
            });
        });
    </script>
@endpush
