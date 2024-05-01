<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\BaseController;

class RegisterController extends BaseController
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'phone_number' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'country' => 'required',
            'profile_picture' => 'nullable',
        ]);

        if ($request->fails()) {
            return $this->handleErrorResponse($request->errors(),400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['role_id'] = 2;
        $user = User::create($input);
        $token = $user->createToken('authToken')->plainTextToken;
        return $this->handleResponse(['token' => $token], 'User registered successfully');

    }    
}
