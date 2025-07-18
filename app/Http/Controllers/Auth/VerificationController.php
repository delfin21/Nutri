<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Support\Facades\Log;
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
    protected $redirectTo = '/home';

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
    public function resend(Request $request)
{
    if ($request->user()->hasVerifiedEmail()) {
        return redirect()->intended($this->redirectPath());
    }

    $request->user()->sendEmailVerificationNotification();

    // ✅ Write to laravel.log
    Log::info('Verification email resent for user: ' . $request->user()->email, [
        'user_id' => $request->user()->id,
        'timestamp' => now()->toDateTimeString(),
        'ip' => $request->ip(),
    ]);

    return back()->with('status', 'verification-link-sent');
}

}
