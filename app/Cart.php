<?php

namespace App;

class Cart
{
    public $items = [];
    public $totalQty = 0;
    public $totalPrice = 0;

    public function __construct($oldCart = null)
    {
        if ($oldCart) {
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
            $this->totalPrice = $oldCart->totalPrice;
        }
    }

    public function add($item, $id)
    {
        // If the item is not already in the cart, initialize its stored data.
        if (!isset($this->items[$id])) {
            $this->items[$id] = [
                'qty'   => 0,
                'price' => 0,
                'item'  => $item
            ];
        }

        // Increase the quantity
        $this->items[$id]['qty']++;

        // Recalculate the price for this item (quantity * sell price)
        $this->items[$id]['price'] = $this->items[$id]['qty'] * $item->sell_price;

        // Recalculate totals for the cart
        $this->recalcTotals();
    }

    public function reduceByOne($id)
    {
        if (isset($this->items[$id])) {
            // Decrease the quantity
            $this->items[$id]['qty']--;

            // Recalculate the item's total price
            $this->items[$id]['price'] = $this->items[$id]['qty'] * $this->items[$id]['item']->sell_price;

            // Remove the item if quantity drops to zero or less
            if ($this->items[$id]['qty'] <= 0) {
                unset($this->items[$id]);
            }

            // Recalculate totals for the cart
            $this->recalcTotals();
        }
    }

    public function removeItem($id)
    {
        if (isset($this->items[$id])) {
            unset($this->items[$id]);
            $this->recalcTotals();
        }
    }

    /**
     * Recalculate the total quantity and total price from the current items.
     */
    private function recalcTotals()
    {
        $this->totalQty = 0;
        $this->totalPrice = 0;
        foreach ($this->items as $storedItem) {
            $this->totalQty += $storedItem['qty'];
            $this->totalPrice += $storedItem['price'];
        }
    }
}
