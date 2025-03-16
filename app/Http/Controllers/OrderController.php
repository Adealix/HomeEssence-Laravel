<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Order;
use App\Mail\SendOrderStatus;
use Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;
use PDF; // Using barryvdh/laravel-dompdf

class OrderController extends Controller
{
    /**
     * Display the order details for admin.
     */
    public function processOrder($id)
    {
        Log::debug('processOrder method entered', ['orderinfo_id' => $id]);
        
        // Get customer and order info
        $customer = DB::table('customer as c')
            ->join('orderinfo as o', 'o.customer_id', '=', 'c.customer_id')
            ->where('o.orderinfo_id', $id)
            ->select(
                'c.lname',
                'c.fname',
                'c.addressline',
                'c.phone',
                'o.orderinfo_id',
                'o.status',
                'o.date_placed'
            )
            ->first();
        Log::debug('Customer retrieved', ['customer' => $customer]);
        
        // Get order items, fetching the first image from product_images for each item.
        $orders = DB::table('customer as c')
            ->join('orderinfo as o', 'o.customer_id', '=', 'c.customer_id')
            ->join('orderline as ol', 'o.orderinfo_id', '=', 'ol.orderinfo_id')
            ->join('item as i', 'ol.item_id', '=', 'i.item_id')
            ->select(
                'i.description',
                'ol.quantity',
                DB::raw("(select image_path from product_images where product_images.item_id = i.item_id limit 1) as image"),
                'i.sell_price'
            )
            ->where('o.orderinfo_id', $id)
            ->get();
        Log::debug('Orders retrieved', ['orders' => $orders]);
        
        // Calculate total order amount.
        $total = $orders->map(function ($item) {
            return $item->sell_price * $item->quantity;
        })->sum();
        Log::debug('Total calculated', ['total' => $total]);
        
        return view('order.processOrder', compact('customer', 'orders', 'total'));
    }

    /**
     * Update the order status and send a notification email with a PDF receipt.
     *
     * This method is used by an administrator to update an order’s status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id  The order ID.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function orderUpdate(Request $request, $id)
    {
        Log::debug('orderUpdate method entered', ['orderinfo_id' => $id, 'request' => $request->all()]);
        
        // Validate the status input
        $request->validate([
            'status' => 'required|in:pending,delivered,canceled',
        ]);

        try {
            // Start transaction
            DB::beginTransaction();
            
            // Retrieve the order using Eloquent
            $order = Order::findOrFail($id);
            
            // Update the order status; if delivered, set date_shipped; if not, clear it.
            $order->status = $request->status;
            if ($request->status === 'delivered') {
                $order->date_shipped = Carbon::now();
            } else {
                $order->date_shipped = null;
            }
            $order->save();
            Log::debug('Order updated', ['order' => $order]);
            
            DB::commit();

            // Reload the order with its customer, user, and orderlines (with associated items)
            $order->load('customer.user', 'orderlines.item');

            if (!$order->customer || !$order->customer->user || !$order->customer->user->email) {
                Log::error('Customer email not found for order update', ['order_id' => $order->orderinfo_id]);
                return redirect()->route('admin.orders')->with('error', 'Customer email not found.');
            }
            
            // Generate a PDF receipt.
            // We pass no cart (null) here so that the PDF view uses orderlines data.
            $pdf = PDF::loadView('pdf.receipt', [
                'order' => $order,
                // Calculate total price based on orderlines plus shipping
                'orderTotal' => number_format(
                    $order->orderlines->sum(function ($ol) {
                        return $ol->item->sell_price * $ol->quantity;
                    }) + $order->shipping, 
                    2
                ),
            ])->output();

            // Send the order status update email with the PDF attached.
            Mail::to($order->customer->user->email)
                ->send(new SendOrderStatus($order, true, $pdf)); // Pass true for update email
            Log::debug('Order status email sent', ['email' => $order->customer->user->email]);
            
            return redirect()->route('admin.orders')->with('success', 'Order updated successfully and notification email sent.');
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Exception thrown during order update', ['error' => $e->getMessage()]);
            return redirect()->route('admin.orders')->with('error', 'Failed to update order: ' . $e->getMessage());
        }
    }
}