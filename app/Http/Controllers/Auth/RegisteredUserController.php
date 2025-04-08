<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'username' => [
                'required',
                'string',
                'lowercase',
                'max:16',
                'regex:/^[a-z0-9._]+$/',
                'unique:' . User::class,
            ],
            'birth_date' => ['nullable', 'date'], // Validate birth date Optional
            'phone' => ['nullable', 'string', 'max:20'], // Phone is optional
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    
        // Create user (Primary data)
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role
        ]);

        // Create user details (Secondary data)
        UserDetail::create([
            'user_id' => $user->id,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
        ]);
    
        event(new Registered($user));
    
        Auth::login($user);
    
        return redirect(route('dashboard', absolute: false));
    }
}
