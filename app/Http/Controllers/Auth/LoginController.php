<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;

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
            $token = $request->user()->createToken('invoiceAuth', [], Carbon::now()->addHours(24))->plainTextToken;
            $user = $request->user();
            return $this->handleResponseNoPagination(['token' => $token, 
            'user' => [
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role->name ?? 'User',
            ],
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::now()->addHours(24)->toDateTimeString(),
        ], 
            'Login successful', 200);
        } else {
            return $this->handleError('Invalid credentials', 401);
        }    
    }
}

