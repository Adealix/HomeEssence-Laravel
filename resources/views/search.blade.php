@extends('layouts.base')
@section('body')
    <h1>Search</h1>

    <p>There are {{ $searchResults->count() }} results.</p>

    @php
        // Prepare a collection to hold Item models from search results.
        $itemResults = collect();
    @endphp

    @foreach ($searchResults->groupByType() as $type => $modelSearchResults)
        @if ($type === 'App\Models\Item')
            <h2>Items</h2>
            <ul>
                @foreach ($modelSearchResults as $searchResult)
                    <li>
                        <!-- Instead of using $searchResult->url, we force a modal pop-up -->
                        <a href="#" data-bs-toggle="modal" data-bs-target="#itemModal{{ $searchResult->model->item_id }}">
                            {{ $searchResult->title }}
                        </a>
                    </li>
                    @php
                        $itemResults->push($searchResult->model);
                    @endphp
                @endforeach
            </ul>
        @elseif ($type === 'App\Models\Customer')
            <h2>Customers</h2>
            <table class="table table-striped">
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
            <ul>
                @foreach ($modelSearchResults as $searchResult)
                    <li>
                        <a href="{{ $searchResult->url }}">{{ $searchResult->title }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    @endforeach

    {{-- Generate modals for each unique Item result --}}
    @foreach ($itemResults->unique('item_id') as $item)
        @php
            // Load reviews (and each review's user) if not already loaded.
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
