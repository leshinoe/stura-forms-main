<?php

namespace Database\Factories\Dticket;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dticket\DticketRequest>
 */
class DticketRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => 'pending',
            'semester' => 'SoSe 2099',
            'reason' => 'a',
            'exclude_starts_at' => '2099-04-01',
            'exclude_ends_at' => '2099-09-30',
            'comment' => 'Mein Kommentar',
            'attachments' => [],
            'attachment_filenames' => [],
            'banking_name' => 'Max Mustermann',
            'banking_iban' => 'DE89 3704 0044 0532 0130 00',
            'banking_bic' => 'COBADEFFXXX',
        ];
    }

    /**
     * Indicate that the dticket request has attachments.
     */
    public function withAttachments(): static
    {
        return $this->state([
            'attachments' => [
                'dtickets/1/abcdef.pdf',
                'dtickets/1/ghijkl.jpg',
            ],
            'attachment_filenames' => [
                'dtickets/1/abcdef.pdf' => 'file1.pdf',
                'dtickets/1/ghijkl.jpg' => 'file2.jpg',
            ],
        ]);
    }

    /**
     * Indicate that the dticket request has been approved.
     */
    public function approved(): static
    {
        return $this->state([
            'status' => 'approved',
        ]);
    }

    /**
     * Indicate that the dticket request has been rejected.
     */
    public function rejected(): static
    {
        return $this->state([
            'status' => 'rejected',
            'reason_for_rejection' => [
                'de' => 'Fehlende Dokumente',
                'en' => 'Missing documents',
            ],
        ]);
    }

    /**
     * Indicate that the dticket request has been paid.
     */
    public function paid(): static
    {
        return $this->state([
            'status' => 'paid',
        ]);
    }
}
