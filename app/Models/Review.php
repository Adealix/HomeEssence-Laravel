<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'reviews';
    
    // Specify the primary key
    protected $primaryKey = 'review_id';

    // Enable timestamps (this is default but included here for clarity)
    public $timestamps = true;

    // The attributes that are mass assignable.
    protected $fillable = [
        'user_id',
        'item_id',
        'rating',
        'comment',
    ];

    /**
     * Get the user that wrote the review.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get the item that this review is for.
     */
    public function item()
    {
        return $this->belongsTo(\App\Models\Item::class, 'item_id', 'item_id');
    }
}
