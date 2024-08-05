<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user(); // Mendapatkan instance user yang berhasil login
            
            if ($user->status == 0) { // Memeriksa status user
                echo 'You are not part of us';
                die();
            } 
            
            $request->session()->regenerate();
            return redirect()->intended('home');
        }

        return back()->with('error', 'Login failed! Please check your Username and Password');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function decrypt()
    {
        $pw = request()->c;
        if (Hash::check($pw, Auth::user()->password)) {
            return response()->json(true);
        } else return response()->json(false);
    }
}
