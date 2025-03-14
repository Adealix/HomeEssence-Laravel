<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Stock;
use App\Models\Order;
use App\Models\Customer;
use App\Imports\ItemsImport;
use App\Imports\ItemStockImport;
use App\Cart;
use Validator;
use Storage;
use DB;
use Excel;
use Session;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = DB::table('item')
            ->join('stock', 'item.item_id', '=', 'stock.item_id')
            ->get();
        Log::debug('Index method accessed', ['items_count' => $items->count()]);
        return view('item.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Log::debug('Create method accessed');
        return view('item.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::debug('Store method accessed', ['request' => $request->all()]);
        $rules = [
            'description' => 'required|min:4',
            'image'       => 'mimes:jpg,png'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Log::error('Validation failed in store method', ['errors' => $validator->errors()]);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $path = Storage::putFileAs(
            'public/images',
            $request->file('image'),
            $request->file('image')->hashName()
        );
        $item = Item::create([
            'description' => trim($request->description),
            'cost_price'  => $request->cost_price,
            'sell_price'  => $request->sell_price,
            'image'       => $path
        ]);
        Log::debug('Item created', ['item' => $item]);
        $stock = new Stock();
        $stock->item_id = $item->item_id;
        $stock->quantity = $request->qty;
        $stock->save();
        Log::debug('Stock saved for item', ['stock' => $stock]);
        return redirect()->back()->with('success', 'item added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Log::debug('Show method accessed', ['id' => $id]);
        // Implementation as needed
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Log::debug('Edit method accessed', ['id' => $id]);
        $item = DB::table('item')
            ->join('stock', 'item.item_id', '=', 'stock.item_id')
            ->where('item.item_id', $id)
            ->first();
        return view('item.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    Log::debug('Update method accessed', ['id' => $id, 'request' => $request->all()]);

    // Validate the input data
    $rules = [
        'description' => 'required|min:4',
        'cost_price'  => 'required|numeric|min:0',
        'sell_price'  => 'required|numeric|min:0',
        'quantity'    => 'required|integer|min:0',
        'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:10240', // Max size in kilobytes
    ];
    $validated = $request->validate($rules);    

    // Retrieve the item record using its primary key (item_id)
    $item = Item::findOrFail($id);

    // If a new image is uploaded, delete the old one (if exists) and store the new image
    if ($request->hasFile('image')) {
        if ($item->image && Storage::exists($item->image)) {
            Storage::delete($item->image);
        }
        $path = Storage::putFileAs('public/images', $request->file('image'), $request->file('image')->hashName());
        $item->image = $path;
    }
    // If no new image is uploaded, the existing image remains unchanged

    // Update the item's details
    $item->description = trim($request->description);
    $item->cost_price  = $request->cost_price;
    $item->sell_price  = $request->sell_price;
    $item->save();

    Log::debug('Item updated', ['item' => $item]);

    // Update the related stock record
    $stock = \App\Models\Stock::where('item_id', $item->item_id)->first();
    if ($stock) {
        $stock->quantity = $request->quantity;
        $stock->save();
    } else {
        $stock = new \App\Models\Stock();
        $stock->item_id = $item->item_id;
        $stock->quantity = $request->quantity;
        $stock->save();
    }

    Log::debug('Stock updated', ['stock' => $stock]);

    // Redirect to the items index route with a success message
    return redirect()->route('items.index')->with('success', 'Item updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    /**
 * Remove the specified resource from storage.
 */
public function destroy(string $id)
{
    Log::debug('Destroy method accessed', ['id' => $id]);

    // Retrieve the item by its ID
    $item = Item::findOrFail($id);

    // Delete the associated image file if it exists
    if ($item->image && Storage::exists($item->image)) {
        Storage::delete($item->image);
        Log::debug('Deleted image file', ['path' => $item->image]);
    }

    // Delete the item record from the database
    $item->delete();
    Log::debug('Item deleted', ['id' => $id]);

    // Redirect to the items index route with a success message
    return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
}

    public function import()
    {
        Log::debug('Import method accessed');
        Excel::import(
            new ItemStockImport,
            request()
                ->file('item_upload')
                ->storeAs(
                    'files',
                    request()
                        ->file('item_upload')
                        ->getClientOriginalName()
                )
        );
        Log::debug('Import completed');
        return redirect()->back()->with('success', 'Excel file Imported Successfully');
    }

    public function getItems()
    {
        Log::debug('getItems method accessed');
        $items = DB::table('item')
            ->join('stock', 'item.item_id', '=', 'stock.item_id')
            ->get();
        return view('shop.index', compact('items'));
    }

    public function addToCart($id)
    {
        Log::debug('addToCart method accessed', ['item_id' => $id]);
        $item = Item::find($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($item, $id);
        Session::put('cart', $cart);
        Log::debug('Item added to cart', ['cart' => $cart]);
        return redirect('/')->with('success', 'item added to cart');
    }

    public function getCart()
    {
        Log::debug('getCart method accessed');
        if (!Session::has('cart')) {
            Log::debug('Cart not found in session');
            return view('shop.shopping-cart');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        Log::debug('Cart retrieved', ['cart' => $cart]);
        return view('shop.shopping-cart', ['products' => $cart->items, 'totalPrice' => $cart->totalPrice]);
    }

    public function getReduceByOne($id)
    {
        Log::debug('getReduceByOne method accessed', ['item_id' => $id]);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->reduceByOne($id);
        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }
        return redirect()->route('getCart');
    }

    public function getRemoveItem($id)
    {
        Log::debug('getRemoveItem method accessed', ['item_id' => $id]);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);
        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }
        return redirect()->route('getCart');
    }

    public function postCheckout(){
        Log::debug('postCheckout method entered', ['cart' => Session::get('cart')]);
        if (!Session::has('cart')) {
            Log::debug('Cart not found in session');
            return redirect()->route('getCart')->with('error', 'No cart found.');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        Log::debug('Cart object created', ['cart' => $cart]);
        try {
            DB::beginTransaction();
            $order = new Order();
            $customer = Customer::where('user_id', Auth::id())->first();
            if (!$customer) {
                Log::error('Customer not found for user id', ['user_id' => Auth::id()]);
                return redirect()->route('getCart')->with('error', 'Customer not found.');
            }
            $order->customer_id = $customer->customer_id;
            $order->date_placed = now();
            $order->date_shipped = Carbon::now()->addDays(5);
            $order->shipping = 10.00;
            $order->status = 'pending';
            $order->save();
            Log::debug('Order saved', ['order' => $order]);
            
            foreach($cart->items as $items){
                $id = $items['item']['item_id'];
                DB::table('orderline')->insert([
                    'item_id' => $id,
                    'orderinfo_id' => $order->orderinfo_id,
                    'quantity' => $items['qty']
                ]);
                Log::debug('Inserted orderline', [
                    'item_id' => $id,
                    'orderinfo_id' => $order->orderinfo_id,
                    'quantity' => $items['qty']
                ]);
                
                $stock = Stock::find($id);
                if ($stock) {
                    $stock->quantity = $stock->quantity - $items['qty'];
                    $stock->save();
                    Log::debug('Stock updated', ['item_id' => $id, 'new_stock' => $stock->quantity]);
                } else {
                    Log::error('Stock not found for item', ['item_id' => $id]);
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Exception thrown during checkout', ['error' => $e->getMessage()]);
            return redirect()->route('getCart')->with('error', $e->getMessage());
        }
        DB::commit();
        Session::forget('cart');
        Log::debug('Checkout process completed successfully');
        return redirect('/')->with('success','Successfully Purchased Your Products!!!');
    }
}
