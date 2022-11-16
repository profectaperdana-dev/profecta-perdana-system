<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
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
            'email' => 'required',
            'password' => 'required'
        ]);

        $getIdByEmail = EmployeeModel::where('email', $request->email)->first();
        if ($getIdByEmail == null) {
            return back()->with('error', 'Login failed! Please check your email and password');
        }

        if (Auth::attempt(['employee_id' => $getIdByEmail->id, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended('home');
        }

        return back()->with('error', 'Login failed! Please check your email and password');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
