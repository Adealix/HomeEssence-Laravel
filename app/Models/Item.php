<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Item extends Model implements Searchable
{
    use HasFactory;

    protected $table = 'item';
    protected $primaryKey = 'item_id';

    // Include 'name' and 'category' in the fillable properties
    protected $fillable = ['name', 'description', 'category', 'cost_price', 'sell_price'];

    /**
     * Get the stock associated with the item.
     */
    public function stock()
    {
        return $this->hasOne(\App\Models\Stock::class, 'item_id', 'item_id');
    }

    /**
     * Get all images associated with the item.
     */
    public function productImages()
    {
        return $this->hasMany(\App\Models\ProductImage::class, 'item_id', 'item_id');
    }

    /**
     * Get the first image for the item.
     */
    public function getFirstImageAttribute()
    {
        $first = $this->productImages()->first();
        return $first ? $first->image_path : 'images/default.png';
    }

    /**
     * Get the search result for the item.
     */
    public function getSearchResult(): SearchResult
    {
        $url = route('items.edit', $this->item_id);

        return new SearchResult(
            $this,
            $this->name, // Use 'name' for search result title
            $url
        );
    }
}
