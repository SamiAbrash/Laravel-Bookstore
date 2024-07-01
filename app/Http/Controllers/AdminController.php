<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AdminController extends Controller
{
    public function getAllUsers()
    {
        $users = User::all();
        return response()->json([
            'message : ' => 'Listing Users',
            'Users : ' => $users,200
        ]);
    }

    public function addUser(Request $req)
    {
        $v = Validator::make($req->all(), [
            'firstname' => 'required|string|max:20',
            'lastname' => 'required|string|max:20',
            'username' => 'required|string|max:20',
            'number' => 'required|integer',
            'email' => 'required|email|unique:users|string|max:50',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($v->fails())
        {
            return response()->json([
                'message : ' => 'An error occured',
                'error : ' => $v->errors(),400
            ]);
        }

        $user = User::create([
            'firstname' => 'required|string|max:20',
            'lastname' => 'required|string|max:20',
            'username' => 'required|string|max:20',
            'number' => 'required|integer',
            'email' => 'required|email|unique:users|string|max:50',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 2
        ]);

        return response()->json([
            'message : ' => 'User added successfully',
            'user : ' => $user,
            401
        ]);
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return response()->json([
            'message : ' => 'user deleted successfully',
            'user : ' => $user->username,
            200
        ]);
    }


}
