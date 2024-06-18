<?php

namespace App\Models\Dticket;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class DticketConfiguration extends Model
{
    use HasFactory;

    /**
     * Cached configurations for the current request.
     *
     * @var array<string, DticketConfiguration|null>
     */
    protected static array $cachedConfiguration = [];

    /**
     * Get the configuration for the given semester.
     */
    public static function for(string $semester): ?DticketConfiguration
    {
        if (! array_key_exists($semester, static::$cachedConfiguration)) {
            static::$cachedConfiguration[$semester] = DticketConfiguration::where('semester', $semester)->first();
        }

        return static::$cachedConfiguration[$semester];
    }

    /**
     * Get all ongoing or future semesters with their basic config details.
     */
    public static function ongoingOrFuture(): Collection
    {
        return static::basic()->filter(fn ($data) => $data['ends_at']->isFuture());
    }

    /**
     * Get all semesters with their basic config details.
     */
    public static function basic(): Collection
    {
        return DticketConfiguration::query()->pluck('semester', 'semester')
            ->map(fn ($semester) => [
                'semester' => $semester,
                'starts_at' => static::startOfSemester($semester),
                'ends_at' => static::endOfSemester($semester),
            ]);
    }

    /**
     * Calculate the start of the semester.
     */
    protected static function startOfSemester(string $semester): Carbon
    {
        if (str_starts_with($semester, 'SoSe')) {
            return Carbon::createFromFormat('Y', Str::after($semester, 'SoSe '))->month(4)->day(1);
        }

        $yearStart = (string) Str::of($semester)->after('WiSe ')->before('/');

        return Carbon::createFromFormat('Y', $yearStart)->month(10)->day(1);
    }

    /**
     * Calculate the end of the semester.
     */
    protected static function endOfSemester(string $semester): Carbon
    {
        if (str_starts_with($semester, 'SoSe')) {
            return Carbon::createFromFormat('Y', Str::after($semester, 'SoSe '))->month(9)->day(30);
        }

        $yearEnd = (string) Str::of($semester)->after('/');

        return Carbon::createFromFormat('Y', $yearEnd)->month(3)->day(31);
    }

    /**
     * Get all ongoing or future semesters as options
     */
    public static function semesterOptions(): Collection
    {
        return static::ongoingOrFuture()->pluck('semester', 'semester');
    }

    /**
     * Get the default semester (currently the first one in the list)
     */
    public static function defaultSemester(): ?string
    {
        return static::semesterOptions()->first() ?? null;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'reasons_for_exemption' => 'array',
            'reasons_for_rejection' => 'array',
        ];
    }

    /**
     * Get the reasons for exemption for the semester.
     *
     * @return array<string, string>
     */
    public function exemptionReasonsAsTranslatedOptions(): array
    {
        return collect($this->reasons_for_exemption)
            ->mapWithKeys(
                fn ($data) => [$data['key'] => $data['title_'.App::getLocale()]]
            )
            ->toArray();
    }

    /**
     * Get the reason descriptions for exemption for the semester.
     *
     * @return array<string, string>
     */
    public function exemptionReasonsAsTranslatedDescriptions(): array
    {
        return collect($this->reasons_for_exemption)
            ->mapWithKeys(
                fn ($data) => [$data['key'] => $data['description_'.App::getLocale()]]
            )
            ->toArray();
    }

    /**
     * Get the translated reasons for rejection for the semester.
     *
     * @return array<string, string>
     */
    public function rejectionReasonsAsTranslatedOptions(): array
    {
        return collect($this->reasons_for_rejection)
            ->mapWithKeys(
                fn ($data) => [$data['key'] => $data[App::getLocale()]]
            )
            ->toArray();
    }

    /**
     * Get the reasons for rejection for the semester.
     *
     * @return array<string, array<string, string>>
     */
    public function rejectionReasonsAsOptions(): array
    {
        return collect($this->reasons_for_rejection)
            ->mapWithKeys(
                fn ($data) => [$data['key'] => $data]
            )
            ->toArray();
    }

    /**
     * Get the exclude starts at options for the semester.
     *
     * @return string[]
     */
    public function excludeStartsAtOptions(): array
    {
        if (str_starts_with($this->semester, 'SoSe')) {

            $year = Str::after($this->semester, 'SoSe ');

            return [
                "{$year}-04-01" => "01.04.{$year}",
                "{$year}-05-01" => "01.05.{$year}",
                "{$year}-06-01" => "01.06.{$year}",
                "{$year}-07-01" => "01.07.{$year}",
                "{$year}-08-01" => "01.08.{$year}",
                "{$year}-09-01" => "01.09.{$year}",
            ];
        }

        if (str_starts_with($this->semester, 'WiSe')) {

            $yearStart = (string) Str::of($this->semester)->after('WiSe ')->before('/');
            $yearEnd = (string) Str::of($this->semester)->after('/');

            return [
                "{$yearStart}-10-01" => "01.10.{$yearStart}",
                "{$yearStart}-11-01" => "01.11.{$yearStart}",
                "{$yearStart}-12-01" => "01.12.{$yearStart}",
                "{$yearEnd}-01-01" => "01.01.{$yearEnd}",
                "{$yearEnd}-02-01" => "01.02.{$yearEnd}",
                "{$yearEnd}-03-01" => "01.03.{$yearEnd}",
            ];
        }

        return [];
    }

    /**
     * Get the exclude ends at options for the semester.
     *
     * @return string[]
     */
    public function excludeEndsAtOptions(): array
    {
        if (str_starts_with($this->semester, 'SoSe')) {

            $year = Str::after($this->semester, 'SoSe ');

            return [
                "{$year}-04-30" => "30.04.{$year}",
                "{$year}-05-31" => "31.05.{$year}",
                "{$year}-06-30" => "30.06.{$year}",
                "{$year}-07-31" => "31.07.{$year}",
                "{$year}-08-31" => "31.08.{$year}",
                "{$year}-09-30" => "30.09.{$year}",
            ];
        }

        if (str_starts_with($this->semester, 'WiSe')) {

            $yearStart = (string) Str::of($this->semester)->after('WiSe ')->before('/');
            $yearEnd = (string) Str::of($this->semester)->after('/');

            return [
                "{$yearStart}-10-31" => "31.10.{$yearStart}",
                "{$yearStart}-11-30" => "30.11.{$yearStart}",
                "{$yearStart}-12-31" => "31.12.{$yearStart}",
                "{$yearEnd}-01-31" => "31.01.{$yearEnd}",
                "{$yearEnd}-02-28" => "28.02.{$yearEnd}",
                "{$yearEnd}-03-31" => "31.03.{$yearEnd}",
            ];
        }

        return [];
    }
}
