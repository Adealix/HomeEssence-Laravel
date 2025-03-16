@extends('layouts.base')

@section('title', 'Items You Can Review')

@section('body')
    @include('layouts.flash-messages')

    @if($items->count() > 0)
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
                                
                                {{-- Review Summary --}}
                                <h1 class="h4">Item Reviews:</h1>
                                @if($item->reviews && $item->reviews->count() > 0)
                                    <div class="review-summary mt-2">
                                        @foreach($item->reviews->take(2) as $review)
                                            <p>
                                                <strong>{{ $review->user->name }}:</strong> 
                                                {{ $review->comment }} (Rated: {{ $review->rating }}/5)
                                            </p>
                                        @endforeach
                                        @if($item->reviews->count() > 2)
                                            <a href="{{ route('reviews.index', ['item' => $item->item_id]) }}" class="btn btn-link">View all reviews</a>
                                        @endif
                                    </div>
                                @endif

                                <div class="clearfix mt-3">
                                    <!-- Instead of Add to Cart, show Write a Review -->
                                    <a href="{{ route('reviews.index', ['item' => $item->item_id]) }}" class="btn btn-primary" role="button">
                                        Write a Review
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    @else
        <p>You have no items to review.</p>
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
