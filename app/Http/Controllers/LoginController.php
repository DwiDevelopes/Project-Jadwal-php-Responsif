<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ✅ WAJIB: tambahkan baris ini

class LoginController extends Controller
{
    public function index()
    {
        return view('backend.v_login.login');
    }

    public function proses_login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Ambil data login
        $credentials = $request->only('email', 'password');

        // Proses autentikasi
        if (Auth::attempt($credentials)) {
            // Jika user aktif
            if (Auth::user()->status == 1) {
                return redirect()->route('beranda');
            } else {
                Auth::logout();
                return back()->withErrors(['status' => 'Status User tidak aktif']);
            }
        }

        // Jika gagal login
        return back()->withErrors(['login' => 'Login Gagal']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
