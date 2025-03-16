<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    use HasFactory;

    protected $table = 'orderline';

    /**
     * Get the order that owns the order line.
     */
    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'orderinfo_id', 'orderinfo_id');
    }

    /**
     * Get the item associated with the order line.
     */
    public function item()
    {
        return $this->belongsTo(\App\Models\Item::class, 'item_id', 'item_id');
    }
}