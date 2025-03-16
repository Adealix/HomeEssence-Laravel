<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;

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
            'title'           => 'nullable|string|max:4',
            'fname'           => 'nullable|string|max:32',
            'lname'           => 'required|string|max:32',
            'addressline'     => 'nullable|string',
            'town'            => 'nullable|string|max:32',
            'zipcode'         => 'nullable|string|max:10',
            'phone'           => 'nullable|string|max:16',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        // Process the profile picture
        if ($request->hasFile('profile_picture')) {
            // Store the file in storage/app/public/profile_pictures
            $profilePicturePath = $request->file('profile_picture')->store('public/profile_pictures');
            // Get only the file name
            $profilePicture = basename($profilePicturePath);
        } else {
            // Default profile picture
            $profilePicture = 'defaultprofile.jpg';
        }

        Customer::create([
            'title'           => $request->title,
            'fname'           => $request->fname,
            'lname'           => $request->lname,
            'addressline'     => $request->addressline,
            'town'            => $request->town,
            'zipcode'         => $request->zipcode,
            'phone'           => $request->phone,
            'user_id'         => Auth::id(),
            'profile_picture' => $profilePicture,
        ]);

        // Redirect based on email verification status:
        if (!Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('success', 'Profile created successfully. Please verify your email before accessing the home page.');
        } else {
            return redirect()->route('home')
                ->with('success', 'Profile created successfully.');
        }
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
     * Now uses the currently authenticated user's customer profile.
     */
    public function edit()
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        if (!$customer) {
            return redirect()->route('customerprofile.create')->with('error', 'Please create a profile first.');
        }
        return view('auth.passwords.customerprofile', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     * Uses the authenticated user's customer profile.
     */
    public function update(Request $request)
    {
        $request->validate([
            'title'           => 'nullable|string|max:4',
            'fname'           => 'nullable|string|max:32',
            'lname'           => 'required|string|max:32',
            'addressline'     => 'nullable|string',
            'town'            => 'nullable|string|max:32',
            'zipcode'         => 'nullable|string|max:10',
            'phone'           => 'nullable|string|max:16',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $customer = Customer::where('user_id', Auth::id())->first();
        if (!$customer) {
            return redirect()->route('customerprofile.create')->with('error', 'Profile not found.');
        }

        // Process the profile picture upload if a new file is provided.
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('public/profile_pictures');
            $profilePicture = basename($profilePicturePath);
        } else {
            // If no new file, keep the existing profile picture.
            $profilePicture = $customer->profile_picture;
        }

        $customer->update([
            'title'           => $request->title,
            'fname'           => $request->fname,
            'lname'           => $request->lname,
            'addressline'     => $request->addressline,
            'town'            => $request->town,
            'zipcode'         => $request->zipcode,
            'phone'           => $request->phone,
            'profile_picture' => $profilePicture,
        ]);

        // After updating, redirect to home (or any page of your choice)
        return redirect('/')->with('success', 'Profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
