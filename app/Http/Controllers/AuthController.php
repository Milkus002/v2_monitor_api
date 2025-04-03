<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserCreateUserRequest;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(UserCreateUserRequest $request){
        $fields = $request->validated(); // Usa la validación de UserCreateUserRequest
        $fields['password'] = Hash::make($fields['password']);

        $user = User::create($fields);
        // $token = $user->createToken($request->name);
        $token = $user->createToken('authToken')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(UserLoginRequest $request){
        $fields= $request->validate();

        $user = User::where('email',$fields['email'])->first();
        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ],401);

        }
        $token =$user->createToken('authToken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);

    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return ['message' => 'Logged out'];

    }

}
