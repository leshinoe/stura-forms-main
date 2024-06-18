<?php

namespace Database\Factories\Dticket;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dticket\DticketConfiguration>
 */
class DticketConfigurationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'semester' => 'SoSe 2099',
            'reasons_for_exemption' => [
                [
                    'key' => 'a',

                    'title_de' => 'a) Aufenthalt außerhalb des Geltungsbereiches für mindestens drei zusammenhängende Monate des Semesters',
                    'description_de' => 'auf Grund ihres Studiums, eines Praxissemesters, eines Auslandssemesters oder im Rahmen der Studienabschlussarbeit (entsprechende Nachweise sind beizufügen).',

                    'title_en' => 'a) Stay outside the validity area for at least three consecutive months of the semester',
                    'description_en' => 'due to their studies, a practical semester, a semester abroad or as part of the final thesis (appropriate evidence must be provided).',
                ],

                [
                    'key' => 'b',

                    'title_de' => 'b) An zwei Hochschulen mit Deutschlandsemesterticket immatrikuliert (entsprechende Nachweise sind beizufügen, sowie die Matrikelnummer an der anderen Hochschule)',
                    'description_de' => 'Der Beitrag kann nur an einer Hochschule erstattet werden.',

                    'title_en' => 'b) Enrolled at two universities with a Germany semester ticket (appropriate evidence must be provided, as well as the matriculation number at the other university)',
                    'description_en' => 'The contribution can only be reimbursed at one university.',
                ],

                [
                    'key' => 'c1',

                    'title_de' => 'c1) nachweislich mehr als einen Monat nach Semesteranfang immatrikuliert werden',
                    'description_de' => '(entsprechene Nachweise sind beizufügen)',

                    'title_en' => 'c1) enrolled more than one month after the start of the semester',
                    'description_en' => '(appropriate evidence must be provided)',
                ],

                [
                    'key' => 'c2',

                    'title_de' => 'c2) im laufenden Semester exmatrikuliert',
                    'description_de' => '(entsprechene Nachweise sind beizufügen)',

                    'title_en' => 'c2) exmatriculated in the current semester',
                    'description_en' => '(appropriate evidence must be provided)',
                ],

                [
                    'key' => 'c3',

                    'title_de' => 'c3) Immatrikulation zurückgenommen',
                    'description_de' => '(entsprechene Nachweise sind beizufügen)',

                    'title_en' => 'c3) Enrollment withdrawn',
                    'description_en' => '(appropriate evidence must be provided)',
                ],

                [
                    'key' => 'c4',

                    'title_de' => 'c4) im laufenden Semester rückwirkend beurlaubt',
                    'description_de' => '(entsprechene Nachweise sind beizufügen)',

                    'title_en' => 'c4) retroactively on leave in the current semester',
                    'description_en' => '(appropriate evidence must be provided)',
                ],

                [
                    'key' => 'c5',

                    'title_de' => 'c5) im laufenden Semester so schwer erkrankt, dass die Gewährung eines Urlaubssemesters berechtigt wäre',
                    'description_de' => '(ein entsprechendes ärztliches Attest ist beizufügen)',

                    'title_en' => 'c5) so seriously ill in the current semester that the granting of a leave semester would be justified',
                    'description_en' => '(a corresponding medical certificate must be submitted)',
                ],

                [
                    'key' => 'd',

                    'title_de' => 'd) aufgrund einer (zeitweiligen) Behinderung den öffentlichen Nahverkehr nicht nutzen können',
                    'description_de' => '(ein entsprechendes ärztliches Attest ist beizufügen)',

                    'title_en' => 'd) due to a (temporary) disability, unable to use public transport',
                    'description_en' => '(a corresponding medical certificate must be submitted)',
                ],

                [
                    'key' => 'e',

                    'title_de' => 'e) Schwerbehinderte Menschen, die nach dem SGB IX Anspruch auf Beförderung haben',
                    'description_de' => 'Besitz des Beiblattes zum Schwerbehindertenausweis und der zugehörigen Wertmarke sind entsprechend beizufügen.',

                    'title_en' => 'e) Severely disabled people who are entitled to transportation according to SGB IX',
                    'description_en' => 'Possession of the supplement to the severely disabled pass and the associated value mark must be attached accordingly.',
                ],
            ],
            'reasons_for_rejection' => [
                [
                    'key' => 'missing_documents',

                    'de' => 'Fehlende oder unvollständige Nachweise (z.B. fehlendes Attest, fehlende Immatrikulationsbescheinigung, fehlende Matrikelnummer an der anderen Hochschule). Der Antrag darf mit den fehlenden Unterlagen erneut gestellt werden.',

                    'en' => 'Missing or incomplete evidence (e.g. missing medical certificate, missing enrollment certificate, missing matriculation number at the other university). The application may be resubmitted with all the needed documents.',
                ],
            ],
        ];
    }

    /**
     * Indicate the semester for the dticket configuration.
     */
    public function semester(string $semester): static
    {
        return $this->state([
            'semester' => $semester,
        ]);
    }

    /**
     * Indicate that the semester is the WiSe 2022/2023.
     */
    public function wise(): static
    {
        return $this->semester('WiSe 2099/2100');
    }

    /**
     * Indicate that the semester is the SoSe 2022.
     */
    public function sose(): static
    {
        return $this->semester('SoSe 2099');
    }
}
