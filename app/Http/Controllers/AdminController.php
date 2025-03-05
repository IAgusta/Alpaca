<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('admin.manage-user');
    }
}
