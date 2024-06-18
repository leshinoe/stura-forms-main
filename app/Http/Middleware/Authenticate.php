<?php

namespace App\Http\Middleware;

use App\Models\OneTimeToken;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as BaseAuthenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class Authenticate extends BaseAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if ($this->validTokenThenRedirect($request)) {
            return Redirect::route('auth.token', ['token' => $request->query('token')]);
        }

        return parent::handle($request, $next, ...$guards);
    }

    /**
     * Check if the token is valid and authenticate the user.
     */
    protected function validTokenThenRedirect(Request $request): bool
    {
        $token = $request->query('token');

        if (! $token) {
            return false;
        }

        $oneTimeToken = OneTimeToken::where('token', $token)
            ->whereDate('expires_at', '>=', today())
            ->first();

        if ($oneTimeToken === null) {
            return false;
        }

        $user = $oneTimeToken->user;

        if ($user->is_admin) {
            $oneTimeToken->delete();

            return false;
        }

        return true;
    }
}
