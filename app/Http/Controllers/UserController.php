<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\{
    RegisterRequest,
    LoginRequest
};
use Illuminate\Support\Facades\{
    Hash,
    Auth
};

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $userData = $request->all();
        $userData["password"] = Hash::make($userData["password"]);
        
        $user = User::create($userData);
        $token = $user->createToken("auth_token")->plainTextToken;

        $message = [
            "user" => $user,
            "token" => $token
        ];

        return response()->json($message, 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(["email", "password"]);
        if (!Auth::attempt($credentials)) {
            return response()->json(["message" => "Invalid credentials."], 401);
        }

        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken("auth_token")->plainTextToken;

        $message = [
            "token" => $token
        ];

        return response()->json($message, 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        $message = [
            "message" => "Logged out."
        ];

        return response()->json($message, 200);
    }

    public function user(Request $request)
    {
        return $request->user();
    }
}
