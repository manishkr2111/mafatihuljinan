<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
     public function dashboard()
    {
        $totalUsers = User::count();
        return view('admin.dashboard', compact(
            'totalUsers'
        ));
    }
}
