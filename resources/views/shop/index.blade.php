@extends('layouts.base')
@section('title')
    HomeEssence Shopping Cart
@endsection
@section('body')
    @include('layouts.flash-messages')

    @foreach ($items->chunk(4) as $itemChunk)
        <div class="row mb-4">
            @foreach ($itemChunk as $item)
                <div class="col-sm-6 col-md-4 mb-3">
                    <div class="card h-100">
                        <!-- Carousel -->
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
                                <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $item->item_id }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $item->item_id }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <!-- Clickable item name triggers modal -->
                            <h3 class="card-title">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#itemModal{{ $item->item_id }}" style="text-decoration: none;">
                                    {{ $item->name }}
                                </a>
                            </h3>
                            <!-- Category displayed on a new line -->
                            <p class="mb-2"><small class="text">{{ $item->category }}</small></p>
                            <p class="card-text">{{ Str::limit($item->description, 100) }}</p>
                            <h4 class="text-success">${{ $item->sell_price }}</h4>
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer bg-transparent border-top-0">
                            <a href="{{ route('addToCart', $item->item_id) }}" class="btn btn-primary" role="button">
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

    {{-- Modals for each item --}}
    @foreach ($items as $item)
        <div class="modal fade" id="itemModal{{ $item->item_id }}" tabindex="-1" aria-labelledby="itemModalLabel{{ $item->item_id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="itemModalLabel{{ $item->item_id }}">{{ $item->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Left: Carousel for images -->
                            <div class="col-md-6">
                                <div id="modalCarousel{{ $item->item_id }}" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @if($item->productImages && $item->productImages->count() > 0)
                                            @foreach($item->productImages as $key => $img)
                                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                                    <img src="{{ Storage::url($img->image_path) }}" class="d-block w-100" alt="Item Image">
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="carousel-item active">
                                                <img src="/images/default.png" class="d-block w-100" alt="No image available">
                                            </div>
                                        @endif
                                    </div>
                                    @if($item->productImages && $item->productImages->count() > 1)
                                        <button class="carousel-control-prev" type="button" data-bs-target="#modalCarousel{{ $item->item_id }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#modalCarousel{{ $item->item_id }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <!-- Right: Full details and reviews -->
                            <div class="col-md-6">
                                <p><strong>Category:</strong> {{ $item->category }}</p>
                                <p><strong>Description:</strong> {{ $item->description }}</p>
                                <p><strong>Price:</strong> ${{ $item->sell_price }}</p>
                                <hr>
                                <h5>Reviews</h5>
                                @if($item->reviews && $item->reviews->count() > 0)
                                    @foreach($item->reviews as $review)
                                        <div class="mb-2">
                                            <p><strong>{{ $review->user->name }}:</strong> {{ $review->comment }}</p>
                                            <p><small>Rating: {{ $review->rating }}/5</small></p>
                                        </div>
                                    @endforeach
                                @else
                                    <p>No reviews available.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
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
