<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LoginController extends BaseController
{
    public function login(Request $request)
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                
                if ($user->status == 'B') {
                    return $this->handleError('Your account is banned', 403);
                }

                $token = $user->createToken('LaravelSanctumAuth');
                $plainToken = $token->plainTextToken;
    
                $expirationHours= 12;
                $expiresAt = now()->addHours($expirationHours)->toDateTimeString();
    
                return $this->handleResponseNoPagination([
                    'user' => [
                        'username' => $user->username,
                        'email' => $user->email,
                        'role_name' => $user->role->name ?? 'User'
                    ],  
                    'access_token'=> $plainToken, 
                    'token_type' => 'Bearer', 
                    'expires_at' => $expiresAt
                ], 'User logged in successfully', 200);
            } else {
                return $this->handleError('Invalid email or password', 401);
            }
        } catch (Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }
}

