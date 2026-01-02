<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $apiToken = Cache::get('api_access_token');
        return view('admin.dashboard', compact(
            'totalUsers',
            'apiToken'
        ));
    }
    public function generateApiToken()
    {
        $token = Str::random(60);
        Cache::put('api_access_token', $token);

        return redirect()->route('admin.dashboard')
            ->with('success', 'API token regenerated successfully.');
    }
}
