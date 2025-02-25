<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => ['required','email'],
            'password' => 'required'
        ]);

        $user = User::where('email',$request->email)->first();

        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid email or password'
            ],400);
        }

        $token = $user->createToken('auth')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'login successfully',
            'token' => $token
        ]);
    }

    public function register(Request $request){
        $request->validate([
            'name' => ['required','max:255','string'],
            'email' => ['required','string','email','max:255'],
            'username' => ['required','regex:/^[a-z0-9_]+$/','string','max:255'],
            'password' => ['required','max:255','string']
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User register successfully'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }
}
