<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Laravel\Scout\Searchable as ScoutSearchable; // Added for Laravel Scout integration

class Item extends Model implements Searchable
{
    use HasFactory;
    use ScoutSearchable; // Added for Laravel Scout integration

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
     * Get the reviews for the item.
     */
    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class, 'item_id', 'item_id');
    }

    public function getRouteKeyName()
    {
        return 'item_id';
    }

    /**
     * Get the orders associated with the item via the pivot table "orderline".
     */
    public function orders()
    {
        return $this->belongsToMany(
            \App\Models\Order::class,
            'orderline',
            'item_id',
            'orderinfo_id',
            'item_id',
            'orderinfo_id'
        );
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
