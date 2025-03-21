<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /*
    |-------------------------------------------------------------------------- 
    | Email Verification Controller
    |-------------------------------------------------------------------------- 
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    // Redirect verified users to the customer profile edit form
    protected $redirectTo = '/customerprofile/edit';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * The user has been verified.
     *
     * This method is called after the user clicks the verification link.
     * It redirects the user to the designated route with a success message.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function verified(Request $request)
    {
        return redirect($this->redirectPath())
            ->with('success', 'Your account has been verified.');
    }
}