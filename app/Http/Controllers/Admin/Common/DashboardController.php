<?php

namespace App\Http\Controllers\Admin\Common;
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
        $apiToken = Setting::get('api_access_token');
        return view('admin.dashboard', compact(
            'totalUsers',
            'apiToken'
        ));
    }
    public function generateApiToken()
    {
        $token = Str::random(60);
        Setting::set('api_access_token', $token);
        return redirect()->route('admin.dashboard')
            ->with('success', 'API token regenerated successfully.');
    }
}
