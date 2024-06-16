<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;


/**
 * @group Auth
 */
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
                // Generate le token d'authentification
                $token = $user->createToken('LaravelSanctumAuth');
                $plainToken = $token->plainTextToken;
                // Définir la durée de validité du token
                $expirationHours = 12;
                $expiresAt = now()->addHours($expirationHours)->toDateTimeString();
                // Retourner la réponse
                return $this->handleResponseNoPagination([
                    'user' => [
                        'username' => $user->username,
                        'email' => $user->email,
                        'role_name' => $user->role->name ?? 'User'
                    ],
                    'access_token' => $plainToken,
                    'token_type' => 'Bearer',
                    'expires_at' => $expiresAt
                ], 'User logged in successfully', 200);
            } else {
                return $this->handleError('Invalid email or password', 401);
            }
            // Gérer les erreurs
        } catch (Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }
}
