@extends('layouts.base')

@section('body')
<div class="container my-5">
    <!-- Flash messages for success or error -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h2>Reviews for: {{ $item->name }}</h2>
    
    {{-- List all reviews --}}
    <div class="reviews-list mb-4">
        @if($reviews->count() > 0)
            @foreach($reviews as $rev)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">
                            {{ $rev->user->name }} 
                            <small class="text-muted">- Rated: {{ $rev->rating }}/5</small>
                        </h5>
                        <p class="card-text">{{ $rev->comment }}</p>
                        <p class="card-text">
                            <small class="text-muted">
                                Posted on: {{ $rev->created_at->format('M d, Y') }}
                            </small>
                        </p>
                    </div>
                </div>
            @endforeach
        @else
            <p>No reviews yet. Be the first to review this item.</p>
        @endif
    </div>
    
    {{-- Display validation errors if any --}}
    @if ($errors->any())
      <div class="alert alert-danger">
          <ul class="mb-0">
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    @endif

    {{-- Review form for the logged-in user --}}
    <div class="review-form">
        <h3>{{ isset($review) ? 'Edit Your Review' : 'Write a Review' }}</h3>
        @if(isset($review))
            <form action="{{ route('reviews.update', ['item' => $item->item_id, 'review' => $review->review_id]) }}" method="POST">
                @method('PUT')
        @else
            <form action="{{ route('reviews.store', ['item' => $item->item_id]) }}" method="POST">
        @endif
            @csrf
            <div class="mb-3">
                <label for="rating" class="form-label">Rating (1 to 5)</label>
                <select name="rating" id="rating" class="form-select">
                    <option value="1" {{ (isset($review) && $review->rating == 1) ? 'selected' : '' }}>1</option>
                    <option value="2" {{ (isset($review) && $review->rating == 2) ? 'selected' : '' }}>2</option>
                    <option value="3" {{ (isset($review) && $review->rating == 3) ? 'selected' : '' }}>3</option>
                    <option value="4" {{ (isset($review) && $review->rating == 4) ? 'selected' : '' }}>4</option>
                    <option value="5" {{ (isset($review) && $review->rating == 5) ? 'selected' : '' }}>5</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="comment" class="form-label">Your Review</label>
                <textarea name="comment" id="comment" class="form-control" rows="4">{{ isset($review) ? $review->comment : old('comment') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">
                {{ isset($review) ? 'Update Review' : 'Submit Review' }}
            </button>
        </form>

        {{-- If a review exists, show a delete button --}}
        @if(isset($review))
            <form action="{{ route('reviews.destroy', ['item' => $item->item_id, 'review' => $review->review_id]) }}" method="POST" class="mt-3" onsubmit="return confirm('Are you sure you want to delete your review?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Review</button>
            </form>
        @endif
    </div>
</div>
@endsection
