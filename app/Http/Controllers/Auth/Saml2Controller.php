<?php

namespace App\Http\Controllers\Auth;

use App\Drivers\Saml2\Saml2;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class Saml2Controller extends Controller
{
    /**
     * Return the SAML2 Server Provider metadata.
     */
    public function metadata(): Response
    {
        return Saml2::getServiceProviderMetadata();
    }

    /**
     * Redirect the user to the SAML2 authentication page.
     */
    public function redirect(): RedirectResponse|SymfonyRedirectResponse
    {
        return Saml2::redirectToIdentityProvider();
    }

    /**
     * Obtain the user information from SAML2 and log the user in.
     */
    public function callback(Request $request): RedirectResponse
    {
        $user = Saml2::user();

        Auth::login($user, remember: $user->is_admin ?? false);
        $request->session()->regenerate();

        return Redirect::route('dashboard');
    }
}
