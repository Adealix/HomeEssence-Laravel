<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Display a listing of products that the user can review.
     *
     * @return \Illuminate\View\View
     */
    public function reviewableProducts()
    {
        $user = Auth::user();
        $products = Product::whereHas('orders', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('status', 'Delivered');
        })->get();

        return view('reviews.index', compact('products'));
    }

    /**
     * Display a listing of the reviews for a product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function index(Product $product)
    {
        $reviews = $product->reviews()->with('user')->get();
        $review = $product->reviews()->where('user_id', Auth::id())->first();

        return view('reviews.product_reviews', compact('product', 'reviews', 'review'));
    }

    /**
     * Store a newly created review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:255',
        ]);

        $product->reviews()->create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('reviews.index', $product->id)->with('success', 'Review submitted successfully.');
    }

    /**
     * Update the specified review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product, Review $review)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:255',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('reviews.index', $product->id)->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified review from storage.
     *
     * @param  \App\Models\Product  $product
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product, Review $review)
    {
        $review->delete();

        return redirect()->route('reviews.index', $product->id)->with('success', 'Review deleted successfully.');
    }
}