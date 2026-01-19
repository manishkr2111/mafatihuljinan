<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class SocialLoginController extends Controller
{
    public function redirectToGoogle()
    {
        // return Socialite::driver('google')->with(['prompt' => 'select_account'])->redirect();

        $url = Socialite::driver('google')
            ->redirect()
            ->getTargetUrl();

        // Add prompt=select_account to force account selection
        return redirect($url . '&prompt=select_account');
    }


    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();
        $user = User::where('email', $googleUser->email)->first();
        if (!$user) {
            return redirect()->route('login')->with('error', 'No account found. Please ask admin to create an account for you.');

            // $user = User::create(['name' => $googleUser->name, 'email' => $googleUser->email, 'password' => \Hash::make(rand(100000, 999999))]);
        }
        if (!in_array($user->role, ['admin', 'editor'])) {
            return redirect()->route('login')->with('error', 'You are not allowed to access this page. If you want to be editor please ask admin to give relevent role to you.');
        }
        if ($user->email_verified_at == null) {
            $user->email_verified_at = now();
            $user->save();
        }
        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }
}
