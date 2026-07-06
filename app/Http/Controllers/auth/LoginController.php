<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'nipy'     => ['required'],
            'password' => ['required'],
        ]);

        // Hanya user dengan status aktif yang bisa login
        $credentials['status'] = 'aktif';

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            // Redirect sesuai role masing-masing
            return match (Auth::user()->role) {
                'admin'   => redirect()->route('admin.dashboard'),
                'yayasan' => redirect()->route('yayasan.index'),
                'guru'    => redirect()->route('guru.index'),
                default   => redirect('/'),
            };
        }

        return back()->withErrors([
            'nipy' => 'NIPY atau Password salah, atau akun tidak aktif.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}