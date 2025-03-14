<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Order;
use App\Mail\SendOrderStatus;
use Mail;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function processOrder($id)
    {
        Log::debug('processOrder method entered', ['orderinfo_id' => $id]);
        $customer = DB::table('customer as c')
            ->join('orderinfo as o', 'o.customer_id', '=', 'c.customer_id')
            ->where('o.orderinfo_id', $id)
            ->select('c.lname', 'c.fname', 'c.addressline', 'c.phone', 'o.orderinfo_id',  'o.status', 'o.date_placed')
            ->first();
        Log::debug('Customer retrieved', ['customer' => $customer]);
        $orders = DB::table('customer as c')
            ->join('orderinfo as o', 'o.customer_id', '=', 'c.customer_id')
            ->join('orderline as ol', 'o.orderinfo_id', '=', 'ol.orderinfo_id')
            ->join('item as i', 'ol.item_id', '=', 'i.item_id')
            ->where('o.orderinfo_id', $id)
            ->select('i.description', 'ol.quantity', 'i.image', 'i.sell_price')
            ->get();
        Log::debug('Orders retrieved', ['orders' => $orders]);
        $total = $orders->map(function ($item, $key) {
            return $item->sell_price * $item->quantity;
        })->sum();
        Log::debug('Total calculated', ['total' => $total]);
        return view('order.processOrder', compact('customer', 'orders', 'total'));
    }

    public function orderUpdate(Request $request, $id)
    {
        Log::debug('orderUpdate method entered', ['orderinfo_id' => $id, 'request' => $request->all()]);
        $order = Order::where('orderinfo_id', $id)
            ->update(['status' => $request->status]);
        Log::debug('Order updated', ['result' => $order]);
        if ($order > 0) {
            $myOrder = DB::table('customer as c')
                ->join('orderinfo as o', 'o.customer_id', '=', 'c.customer_id')
                ->join('orderline as ol', 'o.orderinfo_id', '=', 'ol.orderinfo_id')
                ->join('item as i', 'ol.item_id', '=', 'i.item_id')
                ->where('o.orderinfo_id', $id)
                ->select('c.user_id', 'i.description', 'ol.quantity', 'i.image', 'i.sell_price')
                ->get();
            Log::debug('Order details for mail retrieved', ['myOrder' => $myOrder]);
            $user =  DB::table('users as u')
                ->join('customer as c', 'u.id', '=', 'c.user_id')
                ->join('orderinfo as o', 'o.customer_id', '=', 'c.customer_id')
                ->where('o.orderinfo_id', $id)
                ->select('u.id', 'u.email')
                ->first();
            Log::debug('User for mail retrieved', ['user' => $user]);
            Mail::to($user->email)
                ->send(new SendOrderStatus($myOrder));
            Log::debug('Order status email sent', ['email' => $user->email]);
            return redirect()->route('admin.orders')->with('success', 'order updated');
        }
        Log::error('Order update failed');
        return redirect()->route('admin.orders')->with('error', 'email not sent');
    }
}
