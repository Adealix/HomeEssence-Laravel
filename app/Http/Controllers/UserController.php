<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create new user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Optionally, you can trigger an event here
        // event(new Registered($user));

        Auth::login($user);

        // Redirect to the customer profile creation form (route name: customerprofile.create)
        return redirect()->route('customerprofile.create');
    }

    /**
     * Update user role.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function updateRole(Request $request)
    {
        $request->validate([
            'role' => 'required|string',
        ]);
        
        $user = auth()->user();
        $user->role = $request->role;
        $user->save();
        
        return redirect()->back()->with('success', 'User role updated successfully.');
    }

    /**
     * Log the user out.
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
