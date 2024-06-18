<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OneTimeToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class TokenController extends Controller
{
    /**
     * Login the user using a login token
     */
    public function login(Request $request, string $token)
    {
        $oneTimeToken = $this->findOneTimeToken($token);

        // No valid token found
        if ($oneTimeToken === null) {
            return Redirect::route('welcome');
        }

        $user = $oneTimeToken->user;

        // admins should not use one time tokens
        if ($user->is_admin) {
            $oneTimeToken->delete();

            return Redirect::route('welcome');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return Redirect::route('dashboard');
    }

    /**
     * Find a valid one time token
     */
    protected function findOneTimeToken(string $token): ?OneTimeToken
    {
        return OneTimeToken::where('token', $token)
            ->whereDate('expires_at', '>=', today())
            ->first();
    }
}
