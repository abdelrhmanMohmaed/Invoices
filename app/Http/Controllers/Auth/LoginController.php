<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function process_login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->except(['_token']);

        // $user = User::where('email', $request->email)->first();
        $user = User::where('email', $request->email)->where('active', 0)->first();
        if ($user)  return redirect()->back()->with('error', 'الحساب مغلق');

        // return $credentials;
        if (auth()->attempt($credentials)) {

            return redirect('home');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }

    public function logout()
    {
        Auth::logout();
        return  redirect()->route('login');
    }
}
