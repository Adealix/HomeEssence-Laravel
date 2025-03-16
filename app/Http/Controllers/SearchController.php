<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Searchable\Search;
use App\Models\Item;
use App\Models\Customer;

class SearchController extends Controller
{
    /**
     * Search products on the home page using a raw LIKE query.
     * (8 pts)
     */
    public function searchLike(Request $request)
    {
        $request->validate([
            'term' => 'required|string|max:255',
        ]);

        $term = trim($request->term);

        // Use Eloquent to search items using LIKE
        $items = Item::where('name', 'LIKE', "%{$term}%")
            ->orWhere('description', 'LIKE', "%{$term}%")
            ->orWhere('category', 'LIKE', "%{$term}%")
            ->get();

        // Return the view located at resources/views/search/search_like.blade.php
        return view('search.search_like', compact('items', 'term'));
    }

    /**
     * Search products on the home page using model search (Spatie Searchable).
     * (10 pts)
     */
    public function searchModel(Request $request)
    {
        $request->validate([
            'term' => 'required|string|max:255',
        ]);

        $term = trim($request->term);

        // Perform the search by registering the model and its searchable fields.
        $searchResults = (new Search())
            ->registerModel(Item::class, 'name', 'description', 'category')
            ->registerModel(Customer::class, 'title', 'fname', 'lname', 'addressline', 'town', 'zipcode')
            ->search($term);

        // Return the view located at resources/views/search/search_model.blade.php
        return view('search.search_model', compact('searchResults', 'term'));
    }

    /**
     * Search products on the home page using Laravel Scout with pagination.
     * (15 pts)
     */
    public function searchScout(Request $request)
    {
        $request->validate([
            'term' => 'required|string|max:255',
        ]);

        $term = trim($request->term);

        // Ensure your Item model uses the Laravel Scout searchable trait.
        // This will paginate the results (10 per page by default)
        $items = Item::search($term)->paginate(10);

        // Return the view located at resources/views/search/search_scout.blade.php
        return view('search.search_scout', compact('items', 'term'));
    }
}
