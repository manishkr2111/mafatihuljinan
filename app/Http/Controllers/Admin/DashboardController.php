<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $apiToken = Cache::get('api_access_token');
         if (!$apiToken) {
            $apiToken = Setting::get('api_access_token', Str::random(60));
            Cache::put('api_access_token', $apiToken);
        }
        return view('admin.dashboard', compact(
            'totalUsers',
            'apiToken'
        ));
    }
    public function generateApiToken()
    {
        $token = Str::random(60);
        Cache::put('api_access_token', $token);

        Setting::set('api_access_token', $token);

        return redirect()->route('admin.dashboard')
            ->with('success', 'API token regenerated successfully.');
    }
}
