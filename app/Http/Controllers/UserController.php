<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'data' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'email_verified_at' => $request->user()->email_verified_at,
                'remember_token' => $request->user()->remember_token,
                'created_at' => $request->user()->created_at,
                'updated_at' => $request->user()->updated_at,
                'tokens' => $request->user()->tokens,
            ],
        ]);
    }
}
