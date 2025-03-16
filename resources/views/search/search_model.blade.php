{{-- resources/views/search_model.blade.php --}}
@extends('layouts.base')

@section('body')
<div class="container mt-4">
    <h1 class="mb-3">Search Results (Model Search)</h1>
    <p>Found <strong>{{ $searchResults->count() }}</strong> result(s) for "<strong>{{ $term }}</strong>"</p>
    
    @foreach ($searchResults->groupByType() as $type => $modelSearchResults)
        @if ($type === 'App\Models\Item')
            <h2>Items</h2>
            <div class="row">
                @foreach ($modelSearchResults as $searchResult)
                    @php $item = $searchResult->model; @endphp
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
        @elseif ($type === 'App\Models\Customer')
            <h2>Customers</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Address</th>
                        <th>Town</th>
                        <th>Zipcode</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modelSearchResults as $searchResult)
                        @php $customer = $searchResult->model; @endphp
                        <tr>
                            <td>{{ $customer->title }}</td>
                            <td>{{ $customer->fname }}</td>
                            <td>{{ $customer->lname }}</td>
                            <td>{{ $customer->addressline }}</td>
                            <td>{{ $customer->town }}</td>
                            <td>{{ $customer->zipcode }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h2>{{ $type }}</h2>
            <ul class="list-group mb-3">
                @foreach ($modelSearchResults as $searchResult)
                    <li class="list-group-item">
                        <a href="{{ $searchResult->url }}">{{ $searchResult->title }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    @endforeach

    {{-- Generate modals for each unique Item result --}}
    @php
        $itemResults = $searchResults->groupByType()['App\Models\Item'] ?? collect();
    @endphp

    @foreach ($itemResults->unique('model.item_id') as $searchResult)
        @php
            $item = $searchResult->model;
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
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function(){
        // Initialize modals if needed.
    });
</script>
@endpush
