<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function course()
    {
        return view('admin.course');
    }

    public function manageUsers()
    {
        // Allow owner to see all users, including admins
        if (Auth::user()->role === 'owner') {
            $users = User::where('id', '!=', Auth::user()->id)->get();
        } else {
            // For admin, exclude other admins
            $users = User::where('id', '!=', Auth::user()->id)
                         ->where('role', '!=', 'admin')
                         ->get();
        }

        return view('admin.manage-user', compact('users'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate the role input
        $request->validate([
            'role' => 'required|in:admin,teach,user', // Allow 'admin', 'teach', or 'user' roles
        ]);

        // Only allow owner to change roles to admin
        if (Auth::user()->role === 'owner') {
            $user->role = $request->role;
            $user->last_role_change = Carbon::now();
            $user->save();

            return redirect()->back()->with('success', 'User role updated successfully.');
        }

        // For admin, restrict role changes to only 'teach' or 'user'
        if (Auth::user()->role === 'admin' && in_array($request->role, ['teach', 'user'])) {
            $user->role = $request->role;
            $user->last_role_change = Carbon::now();
            $user->save();

            return redirect()->back()->with('success', 'User role updated successfully.');
        }

        return redirect()->back()->with('error', 'You are not authorized to perform this action.');
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

        // Prevent owner from deleting themselves
        if ($user->id === Auth::user()->id) {
            return redirect()->back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}