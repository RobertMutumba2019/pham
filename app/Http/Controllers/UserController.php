<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\UserCredentialsMail;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search by keyword (e.g., name, email, username)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'like', "%$search%")
                  ->orWhere('user_surname', 'like', "%$search%")
                  ->orWhere('user_email', 'like', "%$search%");
            });
        }

        // Pagination (default 20 per page, can be changed)
        $perPage = $request->input('rowsPerPage', 20);
        $users = $query->orderBy('user_surname')->paginate($perPage);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            
            'user_surname' => 'required|string',
            'user_othername' => 'required|string',
            'user_email' => 'required|email|unique:users,user_email',
            'user_gender' => 'required|string',
            'user_role' => 'required|integer',
        ]);

        // Generate username: first letter of first name + first letter of surname + unique 3-digit number
        $firstLetterFirstName = strtoupper(substr($request->user_othername, 0, 1));
        $firstLetterSurname = strtoupper(substr($request->user_surname, 0, 1));
        
        // Find a unique 3-digit number
        $baseUsername = $firstLetterFirstName . $firstLetterSurname;
        $username = null;
        for ($i = 1; $i <= 999; $i++) {
            $candidate = $baseUsername . str_pad($i, 3, '0', STR_PAD_LEFT);
            if (!User::where('user_name', $candidate)->exists()) {
                $username = $candidate;
                break;
            }
        }
        if (!$username) {
            return back()->withErrors(['user_name' => 'Could not generate a unique username.'])->withInput();
        }

        // Generate random password
        $password = \Illuminate\Support\Str::random(8);
        $hashedPassword = md5($password); // For legacy compatibility

        $user = User::create([
            'user_name' => $username,
            'user_surname' => $request->user_surname,
            'user_othername' => $request->user_othername,
            'user_status' => 1,
            'user_email' => $request->user_email,
            'user_telephone' => $request->user_telephone,
            'user_gender' => $request->user_gender,
            'user_password' => $hashedPassword,
            'user_date_added' => now(),
            'user_added_by' => auth()->id(),
            'user_role' => $request->user_role,
            'user_forgot_password' => 1,
            'user_active' => 1,
            
        ]);

        // Send email notification to the user with username and password
        Mail::to($user->user_email)->send(new UserCredentialsMail($username, $password));

        return redirect()->route('users.index')->with('status', 'User created successfully! Username: ' . $username . ' Password: ' . $password);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('status', 'User deleted successfully!');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string',
            'password' => 'required|string',
        ]);

        // Legacy: passwords are stored as MD5 hashes
        $user = User::where('user_name', $request->user_name)
            ->where('user_password', md5($request->password))
            ->where('user_active', 1)
            ->first();

        if ($user) {
            Auth::login($user);
            $user->user_last_logged_in = now();
            $user->user_online = 1;
            $user->save();

            // If first login (default password), force password change
            if ($user->user_password === md5('password')) {
                return redirect()->route('users.change-password.form');
            }

            return redirect()->intended('/dashboard');
        } else {
            return back()->withErrors(['login' => 'Invalid credentials or account inactive.']);
        }
    }

    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:7',
                'regex:/[A-Z]/', // Uppercase
                'regex:/[a-z]/', // Lowercase
                'regex:/[0-9]/', // Number
                'regex:/[@?#$%\/]/', // Special character
                'confirmed',
            ],
        ]);

        $user = Auth::user();
        $user->user_password = md5($request->password); // For legacy compatibility
        // $user->user_password = Hash::make($request->password); // Recommended for new systems
        $user->save();

        return redirect('/dashboard')->with('status', 'Password changed successfully!');
    }
}
