<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // Make sure this is the correct Request class
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Get the needed authorization credentials from the request.
     * This modification ensures that only active accounts can log in.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'), ['status' => 'active']);
    }

    /**
     * Override the method to send the failed login response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        // Check if a user exists with the given email.
        $user = User::where($this->username(), $request->{$this->username()})->first();

        // If user exists and their status is not 'active', return the custom message.
        if ($user && $user->status !== 'active') {
            throw ValidationException::withMessages([
                $this->username() => ['The account is Inactive. Please try contacting an administrator.'],
            ]);
        }

        // Otherwise, return the default failed message.
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
}
