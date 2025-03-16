<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Display a listing of items that the user can review.
     *
     * @return \Illuminate\View\View
     */
    public function reviewableItems()
    {
        $user = Auth::user();
        // Eager load reviews (with user) if needed.
        $items = Item::with('reviews.user')
            ->whereHas('orders', function ($query) use ($user) {
                $query->where('customer_id', $user->id)
                      ->where('status', 'delivered'); // Adjust to 'Delivered' if needed
            })->get();

        return view('reviews.index', compact('items'));
    }

    /**
     * Display a listing of the reviews for an item.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\View\View
     */
    public function index(Item $item)
    {
        // Ensure the item is loaded with its reviews and their users.
        $item->load('reviews.user');
        $reviews = $item->reviews;
        $review = $item->reviews()->where('user_id', Auth::id())->first();

        return view('reviews.item_reviews', compact('item', 'reviews', 'review'));
    }

    /**
     * Store a newly created review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Item $item)
    {
        // Strict validation rules with regex for the comment:
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => [
                'required',
                'string',
                'min:10',
                'max:255',
                // Allow letters, numbers, spaces, and common punctuation.
                'regex:/^[\pL\pN\s.,!?\'"()-]+$/u'
            ],
        ]);

        $item->reviews()->create([
            'user_id' => Auth::id(),
            'rating'  => $request->rating,
            'comment' => trim($request->comment),
        ]);

        return redirect()->route('reviews.index', ['item' => $item->item_id])
                         ->with('success', 'Review submitted successfully.');
    }

    /**
     * Update the specified review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Item $item, Review $review)
    {
        // Strict validation rules with regex for the comment:
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => [
                'required',
                'string',
                'min:10',
                'max:255',
                'regex:/^[\pL\pN\s.,!?\'"()-]+$/u'
            ],
        ]);

        $review->update([
            'rating'  => $request->rating,
            'comment' => trim($request->comment),
        ]);

        return redirect()->route('reviews.index', ['item' => $item->item_id])
                         ->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified review from storage.
     *
     * @param  \App\Models\Item  $item
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Item $item, Review $review)
    {
        $review->delete();

        return redirect()->route('reviews.index', ['item' => $item->item_id])
                         ->with('success', 'Review deleted successfully.');
    }
}
