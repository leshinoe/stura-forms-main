<?php

namespace App\Listeners;

use App\Models\User;
use CodeGreenCreative\SamlIdp\Events\Assertion;
use Illuminate\Support\Facades\Auth;
use LightSaml\ClaimTypes;
use LightSaml\Model\Assertion\Attribute;

class SamlAssertionAttributes
{
    public const EMAIL_ADDRESS = ClaimTypes::EMAIL_ADDRESS;

    public const GIVEN_NAME = ClaimTypes::GIVEN_NAME;

    public const SURNAME = ClaimTypes::SURNAME;

    /** Shibboleth Specific */
    public const SCHAC_PERSONAL_UNIQUE_CODE = '1.3.6.1.4.1.25178.1.2.14';

    public const SAML_SUBJECT_ID = 'urn:oasis:names:tc:SAML:attribute:subject-id';

    public const EDU_PERSON_SCOPED_AFFILIATION = '1.3.6.1.4.1.5923.1.1.1.9';

    public const EDU_PERSON_ENTITLEMENT = '1.3.6.1.4.1.5923.1.1.1.7';

    public function handle(Assertion $event)
    {
        /** @var \LightSaml\Model\Assertion\AttributeStatement */
        $statement = $event->attribute_statement;
        $user = Auth::user();

        foreach ($this->attributes($user) as $attribute) {
            $statement->addAttribute($attribute);
        }
    }

    protected function attributes(User $user): array
    {
        return [
            (new Attribute(
                self::SAML_SUBJECT_ID,
                self::asString($user->btu_id),
            ))->setFriendlyName('samlSubjectID'),

            (new Attribute(
                self::EMAIL_ADDRESS,
                self::asString($user->email),
            ))->setFriendlyName('mail'),

            (new Attribute(
                self::GIVEN_NAME,
                self::asString($user->firstname),
            ))->setFriendlyName('givenName'),

            (new Attribute(
                self::SURNAME,
                self::asString($user->lastname),
            ))->setFriendlyName('sn'),

            (new Attribute(
                self::SCHAC_PERSONAL_UNIQUE_CODE,
                self::asArray($user->identifiers),
            ))->setFriendlyName('schacPersonalUniqueCode'),

            (new Attribute(
                self::EDU_PERSON_SCOPED_AFFILIATION,
                self::asArray($user->scoped_affiliations),
            ))->setFriendlyName('eduPersonScopedAffiliation'),

            (new Attribute(
                self::EDU_PERSON_ENTITLEMENT,
                self::asArray($user->entitlements),
            ))->setFriendlyName('eduPersonEntitlement'),

        ];
    }

    protected static function asString(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_array($value)) {
            return implode(',', $value);
        }

        return strval($value);
    }

    protected static function asArray(mixed $value): array
    {
        if (! is_array($value)) {
            return [
                self::asString($value),
            ];
        }

        return collect($value)
            ->map(fn ($v) => self::asString($v))->toArray();
    }
}
