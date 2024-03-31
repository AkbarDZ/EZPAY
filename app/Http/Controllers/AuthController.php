<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        // Validate login credentials
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            // Authentication passed, redirect to the intended page or dashboard
            return redirect()->intended('/');
        }

        // Authentication failed, redirect back with error message
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function index_register()
    {
        $user = User::all();

        return view('pages.auth.index', ['user' => $user]);
    }

    public function create_register()
    {
        $user = User::all();

        return view('pages.auth.add', ['user' => $user]);
    }

    public function store_register(Request $request)
    {
        // Check if the current user is an admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'You do not have permission to register new accounts.');
        }

        // Validate the registration data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,employee', // Validate role selection
            'avatar' => 'nullable|file|mimes:jpeg,png,jpg,gif', // Define validation rules for avatar
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarData = base64_encode(file_get_contents($avatar)); // Encode image data to base64
        } else {
            // If no avatar provided, set avatar data to null
            $avatarData = null;
        }

        // Create the new user with the selected role
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'), // Set the role based on the selection
            'avatar' => $avatarData, // Store base64 encoded image data in the database
        ]);

        return redirect('/register')->with('success', 'Account has been created.');
    }


}
