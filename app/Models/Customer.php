<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Customer extends Model implements Searchable
{
    use HasFactory;

    protected $table = 'customer';
    protected $primaryKey = 'customer_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string, string>
     */
    protected $fillable = [
        'title',
        'fname',
        'lname',
        'addressline',
        'town',
        'zipcode',
        'phone',
        'profile_picture',
        'user_id',
        'email', // Ensure the email attribute is here
    ];

    /**
     * Get the user that owns the customer profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getSearchResult(): SearchResult
    {
        // This line will generate a URL using a route named "customers.show".
        // Make sure you define this route in your routes file.
        $url = route('customers.show', $this->customer_id);

        return new SearchResult(
            $this,
            $this->fname . " " . $this->lname,
            $url
        );
    }
}