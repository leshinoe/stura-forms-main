<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Dticket\DticketRequest;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

/**
 * @property-read EloquentCollection<DticketRequest> $dticketRequests
 */
class User extends Authenticatable implements FilamentUser, HasLocalePreference
{
    use HasFactory, Notifiable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
        'dticketRequests',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scoped_affiliations' => 'array',
            'identifiers' => 'array',
            'entitlements' => 'array',
            'permissions' => 'array',
        ];
    }

    /**
     * Determine if the user can go to the admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return ! empty($this->is_admin);
    }

    /**
     * Get the dticket requests submitted by the user.
     *
     * @return HasMany<\App\Models\Dticket\DticketRequest>
     */
    public function dticketRequests(): HasMany
    {
        return $this->hasMany(DticketRequest::class);
    }

    /**
     * Get the dticket requests submitted by the user.
     *
     * @return HasMany<\App\Models\BabyWelcomeRequest>
     */
    public function babyWelcomeRequests(): HasMany
    {
        return $this->hasMany(BabyWelcomeRequest::class);
    }

    /**
     * Get the user's entitlements.
     *
     * @return Collection<string>
     */
    public function entitlements(string $prefix): Collection
    {
        return collect($this->entitlements)
            ->filter(fn (string $entitlement) => str_starts_with($entitlement, $prefix))
            ->values();
    }

    /**
     * Determine if the user is eligible for the Dticket.
     */
    public function isDticketEligable(): bool
    {
        return $this->entitlements('semesterticket:')->isNotEmpty();
    }

    /**
     * Get the user's preferred locale.
     */
    public function preferredLocale(): string
    {
        return $this->locale ?? 'en';
    }

    public function hasLoginLinks(): bool
    {
        return OneTimeToken::where('user_id', $this->id)->exists();
    }
}
