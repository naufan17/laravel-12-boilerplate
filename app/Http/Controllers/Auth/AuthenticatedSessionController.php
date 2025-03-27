<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request): JsonResponse
    {
        try {
            $request->authenticate();

            $user = Auth::user();

            $expiresIn = Carbon::now()->addDays(30);

            $token = $user->createToken('access_token', ['*'], $expiresIn)->plainTextToken;

            return response()->json([
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => $expiresIn->timestamp,    
                ]
            ], 200);

        } catch (ValidationException $e) {
            Log::error('Login failed: ' . $e->getMessage());

            return response()->json([
                'message' => 'Email or password is not valid',
                'errors' => $e->errors(),
            ], 401);

        } catch (\Exception $e) {
            Log::error('Login failed: ' . $e->getMessage());

            return response()->json([
            'message' => 'Login failed',
            ], 500);
        }
    }

    public function destroy(Request $request): JsonResponse
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'message' => 'Logged out successfully',
            ]);

        } catch (\Exception $e) {
            Log::error('Logout failed: ' . $e->getMessage());

            return response()->json([
                'message' => 'Logout failed',
            ], 500);
        }
    }
}
