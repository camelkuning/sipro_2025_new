<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        // dd($request);
        $validate = $request->only('username', 'password');

        if (Auth::attempt($validate)) {
            $request->session()->regenerate();
            $role = Auth::user()->role;
            if (Auth::user()->role == 'admin') {
                return redirect('/prokerAdmin')->with('success', ' Selamat Datang ' . $role);
            } elseif (Auth::user()->role == 'user') {
                return redirect('/prokerUser')->with('success', ' Selamat Datang ' . $role);
            } else {
                return redirect('/')->withErrors(['error' => 'Role tidak valid.']);
            }
        }

        return back()->withErrors(['error' => 'Username atau password yang anda masukkan salah.']);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Anda Berhasil Logout!');
    }
}
