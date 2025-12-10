<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\ResetPasswordMail;
use App\Mail\RegisterUserEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Laravel\Socialite\Facades\Socialite;
use Google\Client;

class AuthController extends Controller
{

    public function registerUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'data' => $validator->errors()
            ], 422);
        }

        $token = Str::random(64);

        // Store name/email temporarily in cache for 15 minutes
        Cache::put('register_' . $token, [
            'name' => $request->name,
            'email' => $request->email
        ], now()->addMinutes(15));

        // Send email with link
        Mail::to($request->email)->send(new RegisterUserEmail($token, $request->email));

        return response()->json([
            'status' => true,
            'message' => 'Check your email to complete registration.',
            'data' => []
        ]);
    }
    public function showSetPasswordForm($token = null)
    {
        $data = Cache::get('register_' . $token);

        return view('auth.set-password', ['token' => $token]);
    }

    public function setPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $data = Cache::get('register_' . $request->token);
        if (!$data) {
            return back()->withErrors(['token' => 'This link has expired or is invalid.']);
        }

        // Create user
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($request->password),
            'email_verified_at' => now()
        ]);

        Cache::forget('register_' . $request->token);

        return redirect()->route('set-password')->with('success', 'Your account has been created successfully.');
    }
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error.',
                    'data' => $validator->errors()
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User created successfully.',
                'data' => $user
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'data' => $e->validator->errors()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'data' => []
            ]);
        }
    }
    /**
     * Login and generate Sanctum token
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            // Check user existence
            $user = User::where('email', $credentials['email'])->first();

            if (! $user || ! Hash::check($credentials['password'], $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid email or password.',
                    'data' => []
                ], 401);
            }

            // Revoke old tokens
            $user->tokens()->delete();

            // Create new token
            $token = $user->createToken('auth_token')->plainTextToken;
            $user['token'] = $token;
            return response()->json([
                'status' => true,
                'message' => 'Login successful.',
                'data' => $user
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'data' => $e->validator->errors()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'data' => []
            ]);
        }
    }

    public function googleLoginIdToken(Request $request)
    {
        try {
            $request->validate([
                'id_token' => 'required',
            ]);

            // $client = new \Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
            $client = new Client(['client_id' => env('GOOGLE_CLIENT_ID')]);


            // Verify the ID token
            $payload = $client->verifyIdToken($request->id_token);

            if (!$payload) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Google ID token',
                    'data' => []
                ], 401);
            }

            $email = $payload['email'] ?? null;
            $name = $payload['name'] ?? 'Unknown User';

            if (!$email) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email not found in Google token',
                    'data' => []
                ], 400);
            }

            // Find or create user
            $user = User::where('email', $email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt(uniqid()),
                    'role' => 'subscriber',
                    'email_verified_at' => now()
                ]);
            }

            // Generate Sanctum token
            $token = $user->createToken('google-login')->plainTextToken;
            $user['token'] = $token;

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'data' => $user,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'error' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function googleLogin(Request $request)
    {
        try {
            $request->validate([
                'access_token' => 'required',
            ]);

            // Get user info using Socialite
            $googleUser = Socialite::driver('google')->userFromToken($request->access_token);

            if (!$googleUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Google token',
                    'data' => []
                ], 401);
            }

            // Find or create user
            $user = User::where('email', $googleUser->email)->first();
            // dd($user, $googleUser);
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => bcrypt(uniqid()),
                    'role' => 'subscriber',
                    'email_verified_at' => now()
                ]);
            }

            // Generate API token (Sanctum)
            $token = $user->createToken('google-login')->plainTextToken;
            $user['token'] = $token;
            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'data'    => $user,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'error' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function GoogleLogin_old(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'google_id' => 'required|string',
                'name' => 'required|string',
                'language' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error.',
                    'data' => $validator->errors()
                ], 422);
            }

            $user = User::where('email', $request->email)->first();
            if ($user) {
                $user->update([
                    'google_id' => $request->google_id,
                ]);
                $token = $user->createToken('auth_token')->plainTextToken;
                $user['token'] = $token;
                return response()->json([
                    'status' => true,
                    'message' => 'Login successful.',
                    'data' => $user
                ]);
            } else {
                $user = User::create([
                    'email' => $request->email,
                    'google_id' => $request->google_id,
                    'name' => $request->name,
                    'language' => $request->language,
                ]);
                $token = $user->createToken('auth_token')->plainTextToken;
                $user['token'] = $token;
                return response()->json([
                    'status' => true,
                    'message' => 'Login successful.',
                    'data' => $user
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'data' => $e->validator->errors()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'data' => 'something went wrong'
            ]);
        }
    }

    public function details()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                    'data' => []
                ], 401);
            }

            // Hide sensitive fields if needed
            $user->makeHidden([
                'password',
                'remember_token',
                'email_verified_at',
                'role',
                'created_at',
                'updated_at',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User details retrieved successfully.',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function updateDetails(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error.',
                    'data' => $validator->errors()
                ], 422);
            }
            $user = Auth::user();
            $user->name = $request->name;
            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'User details updated successfully.',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully.',
                'data' => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'old_password' => 'required|string|min:6',
                'new_password' => 'required|string|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error.',
                    'data' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();

            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid old password.',
                    'data' => []
                ], 401);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Password updated successfully.',
                'data' => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function forgetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error.',
                    'data' => $validator->errors()
                ], 422);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found.',
                    'data' => []
                ], 404);
            }

            $token = Str::random(60);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => $token,
                    'created_at' => now()
                ]
            );

            Mail::to($user->email)->send(new ResetPasswordMail($user, $token));

            return response()->json([
                'status' => true,
                'message' => 'Password reset email sent successfully.',
                'data' => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function showResetForm(Request $request)
    {
        return view('auth.reset_password', [
            'token' => $request->token,
            'email' => $request->email
        ]);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        // Check token table
        $entry = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$entry) {
            return back()->withErrors(['email' => 'Invalid or expired token']);
        }

        // Update password
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete token after success
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        return back()->with('success', 'Password has been reset successfully.');
        return redirect()->back()->with('success', 'Password has been reset successfully.');
    }
}
