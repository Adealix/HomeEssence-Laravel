<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Corrected view reference to match the file at resources/views/auth/passwords/customerprofile.blade.php
        return view('auth.passwords.customerprofile');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:4',
            'fname' => 'nullable|string|max:32',
            'lname' => 'required|string|max:32',
            'addressline' => 'nullable|string',
            'town' => 'nullable|string|max:32',
            'zipcode' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:16',
        ]);

        Customer::create([
            'title' => $request->title,
            'fname' => $request->fname,
            'lname' => $request->lname,
            'addressline' => $request->addressline,
            'town' => $request->town,
            'zipcode' => $request->zipcode,
            'phone' => $request->phone,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('home')->with('success', 'Profile created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = Customer::find($id);
        dd($customer);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
