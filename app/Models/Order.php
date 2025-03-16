<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $table = 'orderinfo';
    protected $primaryKey = 'orderinfo_id';
    public $timestamps = true;

    /**
     * Get the customer associated with the order.
     */
    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id', 'customer_id');
    }

    /**
     * Get the order lines associated with the order.
     */
    public function orderlines()
    {
        return $this->hasMany(\App\Models\OrderLine::class, 'orderinfo_id', 'orderinfo_id');
    }
}