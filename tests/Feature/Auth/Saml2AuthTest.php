<?php

namespace Tests\Feature\Auth;

use App\Drivers\Saml2\Saml2Attributes;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use LightSaml\Model\Assertion\Attribute;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use SocialiteProviders\Saml2\Provider;
use Tests\TestCase;

class Saml2AuthTest extends TestCase
{
    #[Test]
    public function metadata_is_accessible(): void
    {
        $response = $this->get('/auth/saml2');

        $response->assertOk();
    }

    // #[Test]
    // public function redirect_to_btu_shibboleth_is_possible(): void
    // {
    //     config([
    //         'services.saml2.entityid' => 'https://www.b-tu.de/idp/shibboleth',
    //         'services.saml2.metadata' => 'https://www.b-tu.de/idp/shibboleth',
    //     ]);

    //     $response = $this->get('/auth/saml2/redirect');

    //     $response->assertRedirectContains('https://www.b-tu.de/idp/profile/SAML2/Redirect/SSO');
    // }

    #[Test]
    public function login_with_btu_shibboleth_is_possible_if_account_already_exists(): void
    {
        $user = User::factory()->withDticket()->create();

        $provider = $this->mock(Provider::class, function (MockInterface $mock) use ($user) {

            $mock->shouldReceive('stateless->user->getRaw')->andReturn([

                (new Attribute(value: $user->btu_id.Saml2Attributes::BTU_ID_SUFFIX))
                    ->setFriendlyName('samlSubjectID'),

                (new Attribute(value: $user->email))
                    ->setFriendlyName('mail'),

                (new Attribute(value: $user->firstname))
                    ->setFriendlyName('givenName'),

                (new Attribute(value: $user->lastname))
                    ->setFriendlyName('sn'),

                (new Attribute(value: $user->scoped_affiliations))
                    ->setFriendlyName('eduPersonScopedAffiliation'),

                (new Attribute(value: $user->identifiers))
                    ->setFriendlyName('schacPersonalUniqueCode'),

                (new Attribute(value: [
                    'urn:mace:ride-ticketing.de:entitlement:dticket:timeframe:20990401-20990930',
                ]))->setFriendlyName('eduPersonEntitlement'),
            ]);

        });

        Socialite::shouldReceive('driver')
            ->with('saml2')
            ->andReturn($provider);

        $response = $this->get('/auth/saml2/callback');

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'btu_id' => $user->btu_id,
            'email' => $user->email,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'scoped_affiliations' => json_encode($user->scoped_affiliations),
            'identifiers' => json_encode($user->identifiers),
            'entitlements' => json_encode($user->entitlements),
        ]);
    }

    #[Test]
    public function login_with_btu_shibboleth_is_possible_if_account_already_exists_with_email_as_id(): void
    {
        $user = User::factory()->withDticket()->create();
        $btuId = $user->btu_id;

        $user->update([
            'btu_id' => $user->email,
        ]);

        $provider = $this->mock(Provider::class, function (MockInterface $mock) use ($user, $btuId) {

            $mock->shouldReceive('stateless->user->getRaw')->andReturn([

                (new Attribute(value: $btuId.Saml2Attributes::BTU_ID_SUFFIX))
                    ->setFriendlyName('samlSubjectID'),

                (new Attribute(value: $user->email))
                    ->setFriendlyName('mail'),

                (new Attribute(value: $user->firstname))
                    ->setFriendlyName('givenName'),

                (new Attribute(value: $user->lastname))
                    ->setFriendlyName('sn'),

                (new Attribute(value: $user->scoped_affiliations))
                    ->setFriendlyName('eduPersonScopedAffiliation'),

                (new Attribute(value: $user->identifiers))
                    ->setFriendlyName('schacPersonalUniqueCode'),

                (new Attribute(value: [
                    'urn:mace:ride-ticketing.de:entitlement:dticket:timeframe:20990401-20990930',
                ]))->setFriendlyName('eduPersonEntitlement'),
            ]);

        });

        Socialite::shouldReceive('driver')
            ->with('saml2')
            ->andReturn($provider);

        $response = $this->get('/auth/saml2/callback');

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'btu_id' => $btuId,
            'email' => $user->email,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'scoped_affiliations' => json_encode($user->scoped_affiliations),
            'identifiers' => json_encode($user->identifiers),
            'entitlements' => json_encode($user->entitlements),
        ]);
    }

    #[Test]
    public function login_with_btu_shibboleth_is_possible_if_account_doesnt_exists(): void
    {
        $user = User::factory()->withDticket()->make();

        $provider = $this->mock(Provider::class, function (MockInterface $mock) use ($user) {

            $mock->shouldReceive('stateless->user->getRaw')->andReturn([

                (new Attribute(value: $user->btu_id.Saml2Attributes::BTU_ID_SUFFIX))
                    ->setFriendlyName('samlSubjectID'),

                (new Attribute(value: $user->email))
                    ->setFriendlyName('mail'),

                (new Attribute(value: $user->firstname))
                    ->setFriendlyName('givenName'),

                (new Attribute(value: $user->lastname))
                    ->setFriendlyName('sn'),

                (new Attribute(value: $user->scoped_affiliations))
                    ->setFriendlyName('eduPersonScopedAffiliation'),

                (new Attribute(value: $user->identifiers))
                    ->setFriendlyName('schacPersonalUniqueCode'),

                (new Attribute(value: [
                    'urn:mace:ride-ticketing.de:entitlement:dticket:timeframe:20990401-20990930',
                ]))->setFriendlyName('eduPersonEntitlement'),
            ]);

        });

        Socialite::shouldReceive('driver')
            ->with('saml2')
            ->andReturn($provider);

        $response = $this->get('/auth/saml2/callback');

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'btu_id' => $user->btu_id,
            'email' => $user->email,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'scoped_affiliations' => json_encode($user->scoped_affiliations),
            'identifiers' => json_encode($user->identifiers),
            'entitlements' => json_encode($user->entitlements),
        ]);
    }

    #[Test]
    public function login_with_btu_shibboleth_is_possible_if_account_already_exists_without_dticket(): void
    {
        $user = User::factory()->create();

        $provider = $this->mock(Provider::class, function (MockInterface $mock) use ($user) {

            $mock->shouldReceive('stateless->user->getRaw')->andReturn([
                (new Attribute(value: $user->btu_id.Saml2Attributes::BTU_ID_SUFFIX))
                    ->setFriendlyName('samlSubjectID'),

                (new Attribute(value: $user->email))
                    ->setFriendlyName('mail'),

                (new Attribute(value: $user->firstname))
                    ->setFriendlyName('givenName'),

                (new Attribute(value: $user->lastname))
                    ->setFriendlyName('sn'),

                (new Attribute(value: $user->scoped_affiliations))
                    ->setFriendlyName('eduPersonScopedAffiliation'),

                (new Attribute(value: $user->identifiers))
                    ->setFriendlyName('schacPersonalUniqueCode'),

                (new Attribute(value: []))
                    ->setFriendlyName('eduPersonEntitlement'),
            ]);

        });

        Socialite::shouldReceive('driver')
            ->with('saml2')
            ->andReturn($provider);

        $response = $this->get('/auth/saml2/callback');

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'btu_id' => $user->btu_id,
            'email' => $user->email,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'scoped_affiliations' => json_encode($user->scoped_affiliations),
            'identifiers' => json_encode($user->identifiers),
            'entitlements' => json_encode($user->entitlements),
        ]);
    }

    #[Test]
    public function login_with_btu_shibboleth_is_possible_if_account_doesnt_exists_with_dticket(): void
    {
        $user = User::factory()->make();

        $provider = $this->mock(Provider::class, function (MockInterface $mock) use ($user) {

            $mock->shouldReceive('stateless->user->getRaw')->andReturn([
                (new Attribute(value: $user->btu_id.Saml2Attributes::BTU_ID_SUFFIX))
                    ->setFriendlyName('samlSubjectID'),

                (new Attribute(value: $user->email))
                    ->setFriendlyName('mail'),

                (new Attribute(value: $user->firstname))
                    ->setFriendlyName('givenName'),

                (new Attribute(value: $user->lastname))
                    ->setFriendlyName('sn'),

                (new Attribute(value: $user->scoped_affiliations))
                    ->setFriendlyName('eduPersonScopedAffiliation'),

                (new Attribute(value: $user->identifiers))
                    ->setFriendlyName('schacPersonalUniqueCode'),

                (new Attribute(value: []))
                    ->setFriendlyName('eduPersonEntitlement'),
            ]);

        });

        Socialite::shouldReceive('driver')
            ->with('saml2')
            ->andReturn($provider);

        $response = $this->get('/auth/saml2/callback');

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'btu_id' => $user->btu_id,
            'email' => $user->email,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'scoped_affiliations' => json_encode($user->scoped_affiliations),
            'identifiers' => json_encode($user->identifiers),
            'entitlements' => json_encode($user->entitlements),
        ]);
    }
}
