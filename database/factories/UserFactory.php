<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $btu_id = fake()->md5();
        $firstname = fake()->firstName();
        $lastname = fake()->lastName();

        return [
            'btu_id' => $btu_id,
            'is_admin' => false,
            'name' => "{$firstname} {$lastname}",
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => "{$firstname}.{$lastname}@b-tu.de",
            'scoped_affiliations' => [
                'student@b-tu.de',
                'member@b-tu.de',
            ],
            'identifiers' => [
                "urn:schac:personalUniqueCode:de:b-tu.de:BTU_ID:{$btu_id}",
            ],
            'entitlements' => [],
        ];
    }

    /**
     * Indicate that the model is an administrator.
     */
    public function admin(): static
    {
        return $this->state([
            'is_admin' => true,
        ]);
    }

    /**
     * Indicate that the model has a semesterticket
     */
    public function withDticket(?string $semester = null, ?string $timeframe = null): static
    {
        if ($semester !== null) {
            return $this->state([
                'entitlements' => [
                    "semesterticket:{$semester}:{$timeframe}",
                ],
            ]);
        }

        return $this->state([
            'entitlements' => [
                'semesterticket:SoSe 2099:20990401-20990930',
            ],
        ]);
    }
}
