<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.signup');
    }

    public function login_logic(Request $request)
    {
        // \Log::info('Login method hit'); 
        
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            \Log::info('Login success for: ' . Auth::user()->email);
            return redirect()->route('user.home');
        }
        
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
            ])->withInput();
    }


    public function register_logic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phoneNumber' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            \Log::error('Validation errors:', $validator->errors()->toArray());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phoneNumber = $request->input('phoneNumber');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        auth()->login($user);
        $request->session()->regenerate();
        \Log::info('Auth check: ', ['user' => auth()->user()]);
        
        return redirect()->route('user.home');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        \Log::info('User logged out successfully.');

        return redirect()->route('login');
    }

}
