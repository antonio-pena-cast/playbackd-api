<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller {
    public function register(Request $request): \Illuminate\Http\JsonResponse {
        try {
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ]);

            $token = $user->createToken('auth_token');

            return response()->json(['msg' => $token->plainTextToken]);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request): \Illuminate\Http\JsonResponse {
        try {
            $user = User::where('email', $request->email)->first();

            if ($user == null) {
                return response()->json(['msg' => 'The user with this email does not exist'], 401);
            }

            if (password_verify($request->password, $user->password)) {
                $token = $user->createToken('token');

                return response()->json(['msg' => $token->plainTextToken]);
            }

            return response()->json(['msg' => 'The password does not match the email'], 401);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request) {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json(['msg' => 'Logout correct']);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()], 500);
        }
    }
}
