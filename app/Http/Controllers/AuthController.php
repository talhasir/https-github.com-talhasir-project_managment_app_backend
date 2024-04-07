<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SignupReq;
use App\Http\Requests\LoginReq;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function signup(SignupReq $request)
    {
        $signupData = $request->validated();
        $user = User::create([
            'name' => $signupData['fullname'],
            'email' => $signupData['email'],
            'password' => bcrypt($signupData['password']),
        ]);

        $token = $user->createToken($user->name);

        return response([
            'user' => $user,
            'token' => $token->plainTextToken,
        ]);
    }
    public function login(LoginReq $request)
    {
        $credentials = $request->validated();
        $credentials['password'] = bcrypt($credentials['password']);
        if (!Auth::attempt($credentials)) {
            return response([
                'error' => 'provided email id or password is incorrect.'
            ], 422);
        };

        $user = Auth::user();
        $token = $user->createToken($user->name)->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $logout = Auth::user()->tokens()->delete();
        if ($logout) {
            return response(['message' => 'user logout successfuly']);
        }
    }
}
