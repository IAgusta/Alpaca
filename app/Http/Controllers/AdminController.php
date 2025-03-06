<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function course()
    {
        return view('admin.course');
    }

    public function manageUsers()
    {
        $users = User::where('id', '!=', Auth::user()->id)
                        ->where('role', '!=', 'admin')
                        ->get();

        return view('admin.manage-user', compact('users'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        // Validate the role input
        $request->validate([
            'role' => 'required|in:teach,user', // Only allow 'teach' or 'user' roles
        ]);
    
        // Check if the role can be changed
        if ($user->canChangeRole()) {
            $user->role = $request->role;
            $user->last_role_change = Carbon::now();
            $user->save();
    
            return redirect()->back()->with('success', 'User role updated successfully.');
        }
    
        return redirect()->back()->with('error', 'You can only change this user’s role once every 24 hours.');
    }

    public function toggleActive($id)
    {
        $user = User::findOrFail($id);
        $user->active = !$user->active;
        $user->save();
    
        return redirect()->back()->with('success', 'User status updated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    
        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
