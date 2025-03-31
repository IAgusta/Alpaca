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
                         ->where('role', '!=', 'owner')
                         ->get();
        }

        $totalUsers = $users->count();

        return view('admin.manage-user', compact('users', 'totalUsers'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $authUser = Auth::user();
    
        $request->validate(['role' => 'required|in:admin,trainer,user']);
    
        if ($authUser->role === 'owner') {
            // Owner can set any role
            $user->update([
                'role' => $request->role
            ]);
            return back()->with('success', 'Role updated successfully');
        }
    
        if ($authUser->role === 'admin' && in_array($request->role, ['trainer', 'user'])) {
            // Admin can only set trainer/user
            $user->update([
                'role' => $request->role
            ]);
            return back()->with('success', 'Role updated successfully');
        }
    
        return back()->with('error', 'Unauthorized action');
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