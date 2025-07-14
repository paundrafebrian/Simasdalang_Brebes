<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // if (Auth::attempt($credentials)) {
        //     $user = Auth::user();

        //     if ($user->role == 'admin') {
        //         return redirect()->route('admin.dashboard');
        //     } elseif ($user->role == 'instansi') {
        //         return redirect()->route('instansi.dashboard');
        //     } else {
        //         return redirect()->route('user.dashboard');
        //     }
        // }
           if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Arahkan berdasarkan role
            if ($user->role == 'admin' || $user->role == 'instansi') {
                return redirect()->route('admin.dashboard'); // Dashboard Admin dan Instansi
            } else {
                return redirect()->route('user.dashboard'); // Dashboard User
            }
        }


        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Menampilkan form register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses register
    public function register(Request $request)
{
    // Validasi input
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        // Hapus validasi role di sini
    ]);

    // Buat user baru dengan role default 'user'
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'user', // ⬅ Hardcode role user
    ]);

    // Otomatis login setelah register
    Auth::login($user);

    return redirect()->route('login')->with('message', 'Silakan periksa email Anda untuk verifikasi.');
}
}
