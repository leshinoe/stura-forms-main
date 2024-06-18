<?php

namespace App\Filament\Widgets;

use App\Models\Dticket\DticketConfiguration;
use App\Models\Dticket\DticketRequest;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DticketOveriew extends Widget
{
    protected static string $view = 'filament.widgets.dticket-overiew';

    protected int|array|string $columnSpan = 2;

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'semesters' => $this->semesters(),
        ];
    }

    protected function semesters(): Collection
    {
        return DticketConfiguration::query()
            ->orderByDesc('id')
            ->pluck('semester')
            ->map(fn ($semester) => [
                'semester' => $semester,
                'all' => DticketRequest::where('semester', $semester)->count(),
                'pending' => $this->countOfStatus($semester, 'pending'),
                'approved' => $this->countOfStatus($semester, 'approved'),
                'paid' => $this->countOfStatus($semester, 'paid'),
                'rejected' => $this->countOfStatus($semester, 'rejected'),
                'months' => $this->countMonths($semester),
            ]);
    }

    protected function countOfStatus(string $semester, string $status): int
    {
        return DticketRequest::where('semester', $semester)->where('status', $status)->count();
    }

    protected function countMonths(string $semester): Collection
    {
        return DticketRequest::query()
            ->select('number_of_months', DB::raw('count(*) as count'))
            ->groupBy('number_of_months')
            ->where('semester', $semester)
            ->whereIn('status', ['approved', 'paid'])
            ->pluck('count', 'number_of_months');
    }
}
