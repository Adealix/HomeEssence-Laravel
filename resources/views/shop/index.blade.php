@extends('layouts.base')
@section('title')
    HomeEssence Shopping Cart
@endsection
@section('body')
    @include('layouts.flash-messages')

    @foreach ($items->chunk(4) as $itemChunk)
        <div class="row mb-4">
            @foreach ($itemChunk as $item)
                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail border p-2">
                        <div id="carousel-{{ $item->item_id }}" class="carousel slide" data-bs-interval="0">
                            <div class="carousel-inner">
                                @if($item->productImages && $item->productImages->count() > 0)
                                    @foreach($item->productImages as $key => $img)
                                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                            <img src="{{ Storage::url($img->image_path) }}" alt="Item Image" class="d-block w-100" style="width:250px; height:250px;">
                                        </div>
                                    @endforeach
                                @else
                                    <div class="carousel-item active">
                                        <img src="/images/default.png" alt="No image available" class="d-block w-100" style="width:250px; height:250px;">
                                    </div>
                                @endif
                            </div>
                            @if($item->productImages && $item->productImages->count() > 1)
                                <a class="carousel-control-prev" href="#carousel-{{ $item->item_id }}" role="button" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carousel-{{ $item->item_id }}" role="button" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            @endif
                        </div>
                        <div class="caption mt-2">
                            <h3>
                                {{ $item->name }}
                                <span class="text-muted"> ({{ $item->category }})</span>
                            </h3>
                            <p>{{ $item->description }}</p>
                            <h4 class="text-success">${{ $item->sell_price }}</h4>
                            <div class="clearfix">
                                <a href="{{ route('addToCart', $item->item_id) }}" class="btn btn-primary" role="button">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
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
