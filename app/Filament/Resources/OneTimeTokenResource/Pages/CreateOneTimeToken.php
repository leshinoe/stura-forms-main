<?php

namespace App\Filament\Resources\OneTimeTokenResource\Pages;

use App\Filament\Resources\OneTimeTokenResource;
use App\Models\OneTimeToken;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateOneTimeToken extends CreateRecord
{
    protected static string $resource = OneTimeTokenResource::class;

    protected function handleRecordCreation(array $data): OneTimeToken
    {
        return static::getModel()::create([
            'user_id' => $this->userId($data),
            'token' => Str::random(48),
            'expires_at' => $data['expires_at'],
        ]);
    }

    protected function userId(array $data): int
    {
        $user = User::where('email', $data['email'])->first();

        if ($user) {
            return $user->id;
        }

        $user = User::create([
            'btu_id' => $data['email'],
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        return $user->id;
    }
}
