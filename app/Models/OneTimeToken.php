<?php

namespace App\Models;

use App\Notifications\OneTimeLinkCreatedNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OneTimeToken extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'date',
        ];
    }

    protected static function booted()
    {
        static::created(function (OneTimeToken $oneTimeToken) {
            $oneTimeToken->user->notify(new OneTimeLinkCreatedNotification($oneTimeToken));
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function findFor(User $user, ?string $token): ?OneTimeToken
    {
        if (! $token) {
            return null;
        }

        return static::where('token', $token)
            ->where('user_id', $user->id)
            ->whereDate('expires_at', '>=', today())
            ->first();
    }
}
