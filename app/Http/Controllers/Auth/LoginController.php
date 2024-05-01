<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\BaseController;

class LoginController extends BaseController
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $request->user()->tokens()->delete();
            $token = $request->user()->createToken('invoiceAuth')->plainTextToken;
            return $this->handleResponse(['token' => $token], 'Login successful', 200);
        } else {
            return $this->handleErrorResponse('Invalid credentials', 401);
        }    
    }
}

