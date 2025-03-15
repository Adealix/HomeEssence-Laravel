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
     * Update user role and status.
     *
     * This method is used by the admin to update a user's role (user/admin) and status (active/inactive).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id  The ID of the user to update.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRole(Request $request, $id)
{
    $user = User::findOrFail($id);

    // Use the current value if the input is missing
    $role = $request->input('role', $user->role);
    $status = $request->input('status', $user->status);

    // Merge the current values into the request so that validation finds both keys
    $request->merge(['role' => $role, 'status' => $status]);

    // Validate the merged data
    $request->validate([
        'role'   => 'required|string',
        'status' => 'required|string|in:active,inactive',
    ]);

    $user->update([
        'role'   => $role,
        'status' => $status,
    ]);

    return redirect()->back()->with('success', 'User role and status updated successfully.');
}

    

    /**
     * Alias method to support update_role route if needed.
     */
    public function update_role(Request $request, $id)
    {
        return $this->updateRole($request, $id);
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
