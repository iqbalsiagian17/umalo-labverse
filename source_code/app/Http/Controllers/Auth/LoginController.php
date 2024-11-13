<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

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
    // Validate the incoming request data
    $this->validate($request, [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $email = $request->input('email');
    $maxAttempts = 5;
    $lockoutTime = 60; // In minutes (1 hour)

    // Check if the user is currently locked out
    if (Cache::has('login_attempts_' . $email)) {
        $attemptData = Cache::get('login_attempts_' . $email);
        
        if ($attemptData['attempts'] >= $maxAttempts) {
            $lockoutUntil = Carbon::parse($attemptData['lockout_time']);
            if (Carbon::now()->lessThan($lockoutUntil)) {
                $remainingMinutes = Carbon::now()->diffInMinutes($lockoutUntil);
                return redirect()->route('login')->with('error', __('Too many login attempts. Please try again in ') . $remainingMinutes . __(' minutes.'));
            }
        }
    }

    // Prepare the credentials
    $credentials = $request->only('email', 'password');

    // Attempt to log in with the credentials and remember me option
    if (Auth::attempt($credentials, $request->filled('remember'))) {
        
        // Login successful, clear login attempts
        Cache::forget('login_attempts_' . $email);

        // Update the last login time
        $user = Auth::user();
        DB::table('t_users')
            ->where('id', $user->id)
            ->update(['last_login_at' => now()]);


            $welcomeMessages = [
                'Selamat datang kembali, ' . $user->name . '!',
                'Senang bertemu lagi denganmu, ' . $user->name . '!',
                'Halo, ' . $user->name . '! Kami merindukanmu!',
                'Selamat bergabung kembali, ' . $user->name . '!',
                'Senang kamu kembali, ' . $user->name . '!',
                'Hai, ' . $user->name . '! Siap untuk hari yang produktif?',
                'Apa kabar, ' . $user->name . '? Senang melihatmu lagi!',
                'Selamat datang, ' . $user->name . '! Kami berharap harimu menyenangkan!',
                'Kembali lagi ya, ' . $user->name . '! Ayo kita mulai!',
                'Kami selalu senang melihatmu kembali, ' . $user->name . '!',
                'Hello, ' . $user->name . '! Terima kasih sudah kembali!',
                'Hai, ' . $user->name . '! Yuk, mulai lagi dengan penuh semangat!',
                'Luar biasa, ' . $user->name . '! Senang melihatmu aktif lagi!',
                'Selamat datang di hari baru, ' . $user->name . '!',
                'Senang melihatmu, ' . $user->name . '! Yuk lanjutkan aktivitas!',
                'Semoga harimu menyenangkan, ' . $user->name . '!',
                'Ayo, ' . $user->name . '! Kami siap membantumu hari ini!',
                'Selamat datang, ' . $user->name . '! Semoga harimu penuh keberhasilan!',
                'Hai, ' . $user->name . '! Senang kamu ada di sini lagi!',
                'Halo lagi, ' . $user->name . '! Mari kita buat hari ini luar biasa!'
            ];
            

            $randomMessage = $welcomeMessages[array_rand($welcomeMessages)];

            session()->flash('welcome_message', $randomMessage);


        // Redirect based on user role
        if ($user->role == 'admin') {
            return redirect()->route(route: 'dashboard');
        } elseif ($user->role == 'costumer') {
            return redirect()->route('home');
        }
    }

    // Authentication failed, record the failed attempt
    $attempts = 0;
    $lockoutTime = Carbon::now()->addMinutes($lockoutTime);

    if (Cache::has('login_attempts_' . $email)) {
        $attemptData = Cache::get('login_attempts_' . $email);
        $attempts = $attemptData['attempts'] + 1;
    } else {
        $attempts = 1;
    }

    if ($attempts >= $maxAttempts) {
        Cache::put('login_attempts_' . $email, ['attempts' => $attempts, 'lockout_time' => $lockoutTime], $lockoutTime);
        return redirect()->route('login')->with('error', __('Too many login attempts. Please try again in 1 hour.'));
    }

    Cache::put('login_attempts_' . $email, ['attempts' => $attempts, 'lockout_time' => $lockoutTime], $lockoutTime);

    // Authentication failed, redirect back with an error message
    return redirect()->route('login')
        ->with('error', __('Email-Address and Password are wrong. Attempts remaining: ') . ($maxAttempts - $attempts));
}
    




public function logout(Request $request)
{
    $user = Auth::user();  // Capture the user before logging out

    // Daftar pesan logout acak
    $logoutMessages = [
        'Sampai jumpa lagi, ' . $user->name . '!',
        'Terima kasih sudah berkunjung, ' . $user->name . '!',
        'Kami harap bisa bertemu lagi, ' . $user->name . '!',
        'Logout berhasil. Sampai jumpa, ' . $user->name . '!',
        'Selamat tinggal, ' . $user->name . '!',
        'Jangan ragu untuk kembali lagi, ' . $user->name . '!'
    ];

    // Pilih pesan secara acak
    $randomMessage = $logoutMessages[array_rand($logoutMessages)];

    // Simpan pesan logout ke session
    session()->flash('logout_message', $randomMessage);

    // Logout the user
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Update last login time
    if ($user) {
        DB::table('t_users')
            ->where('id', $user->id)
            ->update(['last_login_at' => now()]);
    }

    // Redirect berdasarkan role pengguna
    if ($user->role == 'admin') {
        return redirect('/login');  // Redirect admin users to the login page
    } elseif ($user->role == 'costumer') {
        return redirect('/');  // Redirect customer users to the home page
    }

    return redirect('/');  // Fallback to home for other roles
}


    


}
