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
        try {
            $request->validate([
                'username' => 'required|string|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'confirm_password' => 'required|same:password',
            ]);


            $input = $request->all();

            $input['role_id'] = 2;
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);

            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['user'] = $user;

            return $this->handleResponseNoPagination('User registered successfully', $success, 201);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }
}
