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
use App\DataTables\ItemsDataTable;  // Import the DataTable class
use App\Mail\SendOrderStatus;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf as PDF; // Updated import for laravel-dompdf

class ItemController extends Controller
{
    /**
     * Constructor to restrict admin-only methods.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'admin'])->only([
            'create', 'store', 'edit', 'update', 'destroy', 'destroyImage', 'import'
        ]);
    }

    /**
     * Display a listing of the resource for admin using DataTables.
     */
    public function index(ItemsDataTable $dataTable)
    {
        // Ensure only admin users can access this route
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        Log::debug('Admin Items index accessed via DataTables');
        return $dataTable->render('item.index');
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
        
        // Validate required fields and multiple images (optional)
        $rules = [
            'name'        => 'required|string|max:50',
            'description' => 'required|min:4|max:255',
            'category'    => 'required|string|max:30',
            'cost_price'  => 'required|numeric|min:0',
            'sell_price'  => 'required|numeric|min:0',
            'qty'         => 'required|integer|min:0',
            // Validate each uploaded file; images input name must be images[]
            'images.*'    => 'mimes:jpg,png'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Log::error('Validation failed in store method', ['errors' => $validator->errors()]);
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Create the item record (note: no single image field)
        $item = Item::create([
            'name'        => trim($request->name),
            'description' => trim($request->description),
            'category'    => trim($request->category),
            'cost_price'  => $request->cost_price,
            'sell_price'  => $request->sell_price,
        ]);
        Log::debug('Item created', ['item' => $item]);
        
        // Save stock record
        $stock = new Stock();
        $stock->item_id = $item->item_id;
        $stock->quantity = $request->qty;
        $stock->save();
        Log::debug('Stock saved for item', ['stock' => $stock]);
        
        // Process multiple images (if provided)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = Storage::putFileAs(
                    'public/images',
                    $image,
                    $image->hashName()
                );
                // Insert each image record into product_images table (files remain in storage)
                DB::table('product_images')->insert([
                    'item_id'    => $item->item_id,
                    'image_path' => $path,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        return redirect()->back()->with('success', 'Item added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Log::debug('Show method accessed', ['id' => $id]);
        // Load the item with its stock and all product images
        $item = Item::with(['stock', 'productImages'])->findOrFail($id);
        return view('item.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Log::debug('Edit method accessed', ['id' => $id]);
        // Retrieve the item along with its stock (using a join) for basic info
        $item = DB::table('item')
            ->join('stock', 'item.item_id', '=', 'stock.item_id')
            ->where('item.item_id', $id)
            ->first();
        // Retrieve associated images from product_images table
        $images = DB::table('product_images')->where('item_id', $id)->get();
        return view('item.edit', compact('item', 'images'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Log::debug('Update method accessed', ['id' => $id, 'request' => $request->all()]);

        $rules = [
            'name'        => 'required|string|max:50',
            'description' => 'required|min:4|max:255',
            'category'    => 'required|string|max:30',
            'cost_price'  => 'required|numeric|min:0',
            'sell_price'  => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:0',
            // Allow multiple images upload for update as well.
            'images.*'    => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ];
        $validated = $request->validate($rules);

        // Retrieve the item record
        $item = Item::findOrFail($id);

        // If new images are uploaded, remove all existing image records from the DB (files remain in storage)
        if ($request->hasFile('images')) {
            DB::table('product_images')->where('item_id', $item->item_id)->delete();
            foreach ($request->file('images') as $image) {
                $path = Storage::putFileAs('public/images', $image, $image->hashName());
                DB::table('product_images')->insert([
                    'item_id'    => $item->item_id,
                    'image_path' => $path,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        // Update the item's details
        $item->name        = trim($request->name);
        $item->description = trim($request->description);
        $item->category    = trim($request->category);
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

        return redirect()->route('admin.items')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Log::debug('Destroy method accessed', ['id' => $id]);

        // Retrieve the item by its ID
        $item = Item::findOrFail($id);

        // Remove image records from product_images table (files remain in storage)
        DB::table('product_images')->where('item_id', $id)->delete();

        // Delete the item record
        $item->delete();
        Log::debug('Item deleted', ['id' => $id]);

        return redirect()->route('admin.items')->with('success', 'Item deleted successfully.');
    }

    /**
     * Delete a single product image.
     *
     * This method only deletes the database record, leaving the actual file in storage.
     */
    public function destroyImage($item_id, $image_id)
    {
        // Retrieve the image record from product_images table
        $image = DB::table('product_images')
            ->where('id', $image_id)
            ->where('item_id', $item_id)
            ->first();
        if ($image) {
            // Delete the record from product_images table only
            DB::table('product_images')->where('id', $image_id)->delete();
            Log::debug('Product image record deleted', ['image_id' => $image_id]);
            return redirect()->back()->with('success', 'Image record deleted successfully.');
        }
        return redirect()->back()->with('error', 'Image not found.');
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

    /**
     * Display items to customers (shop view).
     */
    public function getItems(Request $request)
{
    Log::debug('getItems method accessed with filters', $request->all());
    
    $query = Item::with(['stock', 'productImages']);
    
    // Filter by category if provided
    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }
    
    // Filter by price range
    $priceMin = $request->input('price_min');
    $priceMax = $request->input('price_max');
    
    // If only a minimum price is provided, filter items with sell_price >= priceMin
    if ($priceMin !== null && is_numeric($priceMin)) {
        $query->where('sell_price', '>=', floatval($priceMin));
    }
    
    // If only a maximum price is provided, filter items with sell_price <= priceMax
    if ($priceMax !== null && is_numeric($priceMax)) {
        $query->where('sell_price', '<=', floatval($priceMax));
    }
    
    // Optional: If both are provided, you might want to validate that priceMin is less than or equal to priceMax.
    if ($priceMin !== null && $priceMax !== null && is_numeric($priceMin) && is_numeric($priceMax)) {
        if (floatval($priceMin) > floatval($priceMax)) {
            // Optionally, you could return an error message.
            return redirect()->back()->with('error', 'Starting price must be less than or equal to the ending price.');
        }
    }
    
    $items = $query->get();
    Log::debug('Filtered items count: ' . $items->count());
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
        return redirect('/')->with('success', 'Item added to cart');
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

    public function postCheckout()
    {
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
            Log::debug('Customer retrieved', ['customer' => $customer]);
            $order->customer_id = $customer->customer_id;
            $order->date_placed = now();
            $order->date_shipped = Carbon::now()->addDays(5);
            $order->shipping = 10.00;
            $order->status = 'pending';
            $order->save();
            Log::debug('Order saved', ['order' => $order]);
            
            foreach ($cart->items as $items) {
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
                    throw new \Exception('Stock not found for item ID: ' . $id);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Exception thrown during checkout', ['error' => $e->getMessage()]);
            return redirect()->route('getCart')->with('error', $e->getMessage());
        }
        
        // Send the order confirmation email
        try {
            Log::debug('Attempting to send order confirmation email', ['email' => $customer->email]);
            // Generate PDF receipt content using laravel-dompdf directly.
            // Note: Ensure your view file is located at resources/views/email/order_status.blade.php
            $pdf = PDF::loadView('email.order_status', ['order' => $order, 'cart' => $cart])->output();
            // Pass false as the second parameter (not an update), the PDF content, and the cart object.
            Mail::to($customer->email)->send(new SendOrderStatus($order, false, $pdf, $cart));
            Log::debug('Order confirmation email sent', ['email' => $customer->email]);
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email', ['error' => $e->getMessage()]);
            return redirect('/')->with('success', 'Order placed, but failed to send confirmation email.');
        }
        
        Session::forget('cart');
        Log::debug('Checkout process completed successfully');
        return redirect('/')->with('success', 'Successfully Purchased Your Products! An Email of your order has been sent to you.');
    }    
}
