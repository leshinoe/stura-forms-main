<?php

namespace App\Drivers\Saml2;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Saml2\Provider as Saml2Provider;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class Saml2
{
    /**
     * Get the SAML2 driver.
     */
    public static function driver(): Saml2Provider
    {
        return Socialite::driver('saml2');
    }

    /**
     * Return the SAML2 Server Provider metadata.
     */
    public static function getServiceProviderMetadata(): Response
    {
        return static::driver()->getServiceProviderMetadata();
    }

    /**
     * Redirect the user to the SAML2 authentication page.
     */
    public static function redirectToIdentityProvider(): RedirectResponse|SymfonyRedirectResponse
    {
        return static::driver()->stateless()->redirect();
    }

    /**
     * Obtain the user information from SAML2 and transform it into an Application User
     */
    public static function user(): User
    {
        $attributes = static::attributes()->all();

        $user = User::where('btu_id', $attributes['btu_id'])->first();
        $user ??= User::where('btu_id', $attributes['email'])->first();
        $user ??= new User;

        $user->fill($attributes);
        $user->save();

        return $user;
    }

    /**
     * Get the SAML2 attributes.
     */
    public static function attributes(): Saml2Attributes
    {
        return new Saml2Attributes(
            static::driver()->stateless()->user()->getRaw()
        );
    }
}
