<?php

namespace App\Drivers\Saml2;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use LightSaml\Model\Assertion\Attribute;

class Saml2Attributes
{
    public const BTU_ID_PREFIX = 'urn:schac:personalUniqueCode:de:b-tu.de:BTU_ID:';

    public const BTU_ID_SUFFIX = '@b-tu.de';

    public const SEMTIX_PREFIX = 'urn:mace:ride-ticketing.de:entitlement:dticket:timeframe:';

    /**
     * The SAML2 user attributes.
     *
     * @param  array<\Lightsaml\Model\Assertion\Attribute>  $attributes
     */
    public function __construct(
        protected array $attributes
    ) {
    }

    /**
     * Map the SAML2 user attributes to the Application User attributes
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return [
            'btu_id' => $this->asBtuIdentifier('samlSubjectID'),
            'firstname' => $firstname = $this->asString('givenName'),
            'lastname' => $lastname = $this->asString('sn'),
            'name' => trim("$firstname $lastname"),
            'email' => $this->asString('mail'),
            'scoped_affiliations' => $this->asArray('eduPersonScopedAffiliation'),
            'identifiers' => $this->asArray('schacPersonalUniqueCode'),
            'entitlements' => $this->asEntitlements('eduPersonEntitlement'),
        ];
    }

    /**
     * Find the SAML2 user attribute by its friendly name.
     */
    public function find(string $friendlyName): ?Attribute
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getFriendlyName() === $friendlyName) {
                return $attribute;
            }
        }

        return null;
    }

    /**
     * Transform a SAML2 user attribute to an array.
     *
     * @return null|array<string>
     */
    public function asArray(string $friendlyName): ?array
    {
        $attribute = static::find($friendlyName);

        if ($attribute === null) {
            return [];
        }

        return $attribute->getAllAttributeValues();
    }

    /**
     * Transform a SAML2 user attribute to a string.
     */
    public function asString(string $friendlyName): ?string
    {
        $attribute = static::find($friendlyName);

        if ($attribute === null) {
            return null;
        }

        return $attribute->getFirstAttributeValue();
    }

    /**
     * Transform a SAML2 user attribute to a date.
     */
    public function asDate(string $friendlyName): ?Carbon
    {
        $attribute = static::find($friendlyName);

        if ($attribute === null) {
            return null;
        }

        return Carbon::createFromFormat('Ymd', $attribute->getFirstAttributeValue());
    }

    /**
     * Transform a SAML2 user attribute to a date.
     */
    public function asBtuIdentifier(string $friendlyName): string
    {
        $attribute = static::find($friendlyName);

        if ($attribute === null) {
            return $this->asString('mail');
        }

        return Str::before($attribute->getFirstAttributeValue(), self::BTU_ID_SUFFIX);
    }

    public function asEntitlements(string $friendlyName): ?array
    {
        $attribute = static::find($friendlyName);

        if ($attribute === null) {
            return null;
        }

        $entitlements = [];

        foreach ($attribute->getAllAttributeValues() ?? [] as $value) {
            $entitlements[] = $this->transformEntitlement($value);
        }

        return $entitlements;
    }

    protected function transformEntitlement(string $entitlement): string
    {
        if (str_starts_with($entitlement, self::SEMTIX_PREFIX)) {

            $timeframe = Str::after($entitlement, self::SEMTIX_PREFIX);

            $semester = str_ends_with($timeframe, '0930')
                ? 'SoSe '.Str::before($timeframe, '0401')
                : 'WiSe '.Str::before($timeframe, '1001').Str::between($timeframe, '1001-', '0331');

            return 'semesterticket:'.$semester.':'.$timeframe;
        }

        return $entitlement;
    }
}
