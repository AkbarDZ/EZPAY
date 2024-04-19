<?php

namespace App\Http\Controllers;

use App\Models\Account_history;
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

    public function register_history()
    {
        $history = Account_history::all();

        return view('pages.auth.history', ['history' => $history]);
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

        // Get the ID of the authenticated user who created the new account
        $creatorId = Auth::id();

        // Create the new user with the selected role
        $newUser = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'), // Set the role based on the selection
            'avatar' => $avatarData, // Store base64 encoded image data in the database
        ]);

        // Record history entry for user creation
        $newUserData = $newUser->toArray(); // Convert user data to array
        unset($newUserData['avatar']); // Remove the 'avatar' field from the array
        Account_history::create([
            'user_id' => $creatorId, // Record the ID of the authenticated user who created the account
            'action' => 'created',
            'new_data' => json_encode($newUserData), // Convert modified array to JSON string
        ]);

        return redirect('/register')->with('success', 'Account has been created.');
    }

    public function edit_register($id)
    {
        // Fetch the user with the given ID
        $user = User::findOrFail($id);

        // Return the edit form view with the user data
        return view('pages.auth.edit',  ['user' => $user]);
    }

    public function update_register(Request $request, $id)
{
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        return redirect('/')->with('error', 'You do not have permission to update accounts.');
    }

    // Fetch the user with the given ID
    $user = User::findOrFail($id);

    // Validate the update data
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'role' => 'required|string|in:admin,employee',
        'password' => 'nullable|string|min:8', // New password validation
        'avatar' => 'nullable|file|mimes:jpeg,png,jpg,gif', // Define validation rules for avatar
        // Add other fields validation rules as needed
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    // Get the original data before update
    $oldData = $user->getOriginal();
    $creatorId = Auth::id();

    // Update the user details
    $user->update([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'role' => $request->input('role'),
        // Update other fields as needed
    ]);

    // Update the password if provided
    $password = $request->input('password');
    if (!empty($password)) {
        $user->update([
            'password' => Hash::make($password),
        ]);
    }

    // Handle avatar update
    if ($request->hasFile('avatar')) {
        $avatar = $request->file('avatar');
        $avatarData = base64_encode(file_get_contents($avatar)); // Encode image data to base64
        $user->update([
            'avatar' => $avatarData,
        ]);
    }

    // Create history entry for user update
    Account_history::create([
        'user_id' => $creatorId,
        'action' => 'updated',
        'old_data' => json_encode($oldData), // Convert array to JSON string
        'new_data' => json_encode($user->toArray()), // Convert array to JSON string
    ]);

    return redirect()->route('account_table')->with('success', 'Account has been updated.');
}


}
