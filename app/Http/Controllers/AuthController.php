<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            // Validate the request data    
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            // Attempt authentication
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('auth_token')->plainTextToken;
                Log::info('User authenticated successfully', ['user' => $user->id]);

                return response()->json([
                    'message' => 'Login successful',
                    'token' => $token,
                ], 200);
            }

            // Log invalid credentials
            Log::warning('Failed login attempt', [
                'email' => $credentials['email'],
                'reason' => 'Invalid credentials',
            ]);

            return response()->json(['message' => 'Invalid credentials'], 401);

        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error('Error during login', [
                'exception_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['message' => 'An error occurred during login'], 500);
        }
    }
}
