<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Searchable\Search;
use App\Models\Item;
use App\Models\Customer;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        // Validate that a search term is provided and is not too long.
        $request->validate([
            'term' => 'required|string|max:255',
        ]);

        // Trim any extra spaces from the term.
        $term = trim($request->term);

        // Perform the search by registering the models and their searchable fields.
        $searchResults = (new Search())
            ->registerModel(Item::class, 'name', 'description', 'category')
            ->registerModel(Customer::class, 'title', 'fname', 'lname', 'addressline', 'town', 'zipcode')
            ->search($term);

        // Return the search view with the search results.
        return view('search', compact('searchResults'));
    }
}
