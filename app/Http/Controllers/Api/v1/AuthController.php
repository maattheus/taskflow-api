<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

        public function login(Request $request)
        {

            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Invalid credentials.',
                ], 401);
            }

            $token = $user->createToken('taskflow')->plainTextToken;

            return response()->json([
                'message' => 'Login successful.',
                'token' => $token,
            ]);
        }


    public function logout(Request $request)
    {
        if (! $request->user()) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 401);
        }

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logout successfully',
        ], 200);
    }
}
