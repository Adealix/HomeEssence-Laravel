{{-- resources/views/search_scout.blade.php --}}
@extends('layouts.base')

@section('body')
<div class="container mt-4">
    <h1 class="mb-3">Search Results (Laravel Scout)</h1>
    <p>Found <strong>{{ $items->total() }}</strong> item(s) for "<strong>{{ $term }}</strong>"</p>

    @if($items->count() > 0)
        <div class="row">
            @foreach($items as $item)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($item->productImages && $item->productImages->count() > 0)
                            <img src="{{ Storage::url($item->productImages->first()->image_path) }}" class="card-img-top" alt="{{ $item->name }}">
                        @else
                            <img src="/images/default.png" class="card-img-top" alt="No Image">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->name }}</h5>
                            <p class="card-text">{{ Str::limit($item->description, 100) }}</p>
                            <p class="card-text"><small class="text-muted">Category: {{ $item->category }}</small></p>
                        </div>
                        <div class="card-footer text-center">
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#itemModal{{ $item->item_id }}">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $items->links() }}
        </div>
        
        {{-- Modals for each item --}}
        @foreach($items as $item)
            @php
                $item->loadMissing('reviews.user');
            @endphp
            <div class="modal fade" id="itemModal{{ $item->item_id }}" tabindex="-1" aria-labelledby="itemModalLabel{{ $item->item_id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="itemModalLabel{{ $item->item_id }}">{{ $item->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Category:</strong> {{ $item->category }}</p>
                            <p><strong>Description:</strong> {{ $item->description }}</p>
                            <p><strong>Price:</strong> ${{ $item->sell_price }}</p>
                            <hr>
                            <h5>Reviews</h5>
                            @if ($item->reviews && $item->reviews->count() > 0)
                                @foreach ($item->reviews as $review)
                                    <div class="mb-2">
                                        <p><strong>{{ $review->user->name }}:</strong> {{ $review->comment }}</p>
                                        <p><small>Rating: {{ $review->rating }}/5</small></p>
                                    </div>
                                @endforeach
                            @else
                                <p>No reviews available.</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p>No items found matching your search.</p>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function(){
        // Additional JavaScript if needed.
    });
</script>
@endpush
