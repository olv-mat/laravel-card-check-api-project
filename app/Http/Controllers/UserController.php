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
        $userInfo = $request->all();
        $userInfo["password"] = Hash::make($userInfo["password"]);
        
        $user = User::create($userInfo);

        if (!$user) {
            return response()->json(["message" => "Failed to create user."], 500);
        }

        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            "user" => $user,
            "token" => $token,
        ], 201);

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
        return response()->json(["token" => $token], 200);
    }

    public function user(Request $request)
    {
        return $request->user();
    }
}
