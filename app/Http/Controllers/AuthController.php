<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'firstname' => 'required|string|max:20',
            'lastname' => 'required|string|max:20',
            'username' => 'required|string|max:20',
            'number' => 'required|integer',
            'email' => 'required|email|unique:users|string|max:50',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($v->fails()) {
            return response()->json([
                'message' => 'Registering could not be completed',
                'errors' => $v->errors()
            ], 400);
        }

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'number' => $request->number,
            'address' => $request->address,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2,
        ]);

        //$token = $user->createToken('Personal Access Token')->plainTextToken;

        return response()->json([
            'message' => 'Registered successfully',
            'data' => $user,
            //'token' => $token,
        ]);
    }

    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || $user->role_id != 2) 
        {
            return response()->json([
                'message : ' => 'you are  an admin, create a user account and login using it',
                'email : ' => $user->email,
            ],403);
        }

        if (Auth::attempt($credentials)) {
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            return response()->json([
                'message' => 'Logged in successfully',
                'data' => $user,
                'token' => $token
            ]);
        }

        return response()->json([
            'message' => 'Error logging in, check email or password',
            'data' => null,
            'status' => 401
        ], 401);
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || $user->role_id != 1) {
            return response()->json([
                'message : ' => 'you are not an admin',
                'email : ' => $user->email,
            ],403);
        }

        if (Auth::attempt($credentials)) {
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            return response()->json([
                'message' => 'Logged in successfully',
                'data' => $user,
                'token' => $token
            ]);
        }

        return response()->json([
            'message' => 'Error logging in, check email or password',
            'data' => null,
            'status' => 401
        ], 401);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->tokens()->delete();
    
        return response()->json([
            'message : ' => 'You have logged out successfully.',
            'data : ' => $user
        ], 200);
    }

    public function profile(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            return response()->json([
                'data' => $user,
                'status' => 200
            ]);
        }

        return response()->json([
            'message' => 'User not authenticated',
            'status' => 401
        ]);
    }

    public function index()
    {
        return User::all();
    }
}
