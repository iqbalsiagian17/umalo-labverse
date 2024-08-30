<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(Request $request)
{
    $input = $request->all();

    $this->validate($request, [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (auth()->attempt(['email' => $input['email'], 'password' => $input['password']])) {
        $user = auth()->user();

        // Use query builder to update last_login_at
        DB::table('users')
            ->where('id', $user->id)
            ->update(['last_login_at' => now()]);

        if ($user->role == 'admin') {
            return redirect()->route('dashboard');
        } else if ($user->role == 'costumer') {
            return redirect()->route('home');
        }
    } else {
        return redirect()->route('login')
            ->with('error', 'Email-Address And Password Are Wrong.');
    }
}



public function logout(Request $request)
{
    $user = Auth::user();  // Capture the user before logging out

    Auth::logout();  // Log the user out
    $request->session()->invalidate();  // Invalidate the session
    $request->session()->regenerateToken();  // Regenerate the CSRF token

    // Update the last_login_at to now to indicate they are no longer online
    if ($user) {
        DB::table('users')
            ->where('id', $user->id)
            ->update(['last_login_at' => now()]);
    }

    // Redirect based on user role
    if ($user->role == 'admin') {
        return redirect('/login');  // Redirect admin users to the login page
    } elseif ($user->role == 'costumer') {
        return redirect('/');  // Redirect customer users to the home page
    }

    return redirect('/');  // Fallback to home for other roles
}

    


}
