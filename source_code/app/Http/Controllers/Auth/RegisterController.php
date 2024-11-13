<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:t_users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Buat pengguna baru
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Daftar pesan selamat datang acak untuk pengguna baru
        $welcomeMessages = [
            'Selamat datang, ' . $user->name . '! Terima kasih telah bergabung.',
            'Hai, ' . $user->name . '! Kami sangat senang kamu ada di sini.',
            'Selamat datang di komunitas kami, ' . $user->name . '!',
            'Halo, ' . $user->name . '! Selamat bergabung, semoga harimu menyenangkan!',
            'Selamat datang di platform kami, ' . $user->name . '!',
            'Hai ' . $user->name . '! Senang bertemu denganmu di sini!'
        ];

        // Pilih pesan acak
        $randomMessage = $welcomeMessages[array_rand($welcomeMessages)];

        // Set flash message untuk welcome message
        session()->flash('welcome_message', $randomMessage);

        return $user;
    }
}
