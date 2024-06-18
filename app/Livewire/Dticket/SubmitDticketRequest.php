<?php

namespace App\Livewire\Dticket;

use App\Filament\Fields\BankingAccountSection;
use App\Filament\Fields\UserDetailsSection;
use App\Models\Dticket\DticketConfiguration;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;

/**
 * @property-read bool $isEligable
 */
class SubmitDticketRequest extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function semesters(): Collection
    {
        return DticketConfiguration::semesterOptions();
    }

    protected function defaultSemester(): string
    {
        return DticketConfiguration::defaultSemester();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                UserDetailsSection::make(),

                Section::make(__('stura.dticket.exemption.title'))
                    ->description(__('stura.dticket.exemption.description'))
                    ->schema([

                        Radio::make('semester')
                            ->label(__('stura.fields.semester'))
                            ->options($this->semesters())
                            ->default($this->defaultSemester())
                            ->in($this->semesters()->keys())
                            ->live()
                            ->required(),

                        Grid::make()
                            ->schema([
                                Radio::make('exclude_starts_at')
                                    ->label(__('stura.fields.exclude_starts_at'))
                                    ->options(
                                        fn (Get $get) => $this->dticketConfig($get)->excludeStartsAtOptions()
                                    )
                                    ->required()
                                    ->in(
                                        fn (Get $get) => array_keys(
                                            $this->dticketConfig($get)->excludeStartsAtOptions()
                                        )
                                    )
                                    ->rule('date'),

                                Radio::make('exclude_ends_at')
                                    ->label(__('stura.fields.exclude_ends_at'))
                                    ->options(
                                        fn (Get $get) => $this->dticketConfig($get)->excludeEndsAtOptions()
                                    )
                                    ->required()
                                    ->in(
                                        fn (Get $get) => array_keys(
                                            $this->dticketConfig($get)->excludeEndsAtOptions()
                                        )
                                    )
                                    ->rule('date')
                                    ->after('exclude_starts_at'),
                            ])
                            ->visible($this->validSemesterSelected(...)),

                        Radio::make('reason')
                            ->label(__('stura.fields.reason'))
                            ->helperText(__('stura.dticket.exemption.reason_help_text'))
                            ->options(
                                fn (Get $get) => $this->dticketConfig($get)->exemptionReasonsAsTranslatedOptions()
                            )
                            ->descriptions(
                                fn (Get $get) => $this->dticketConfig($get)->exemptionReasonsAsTranslatedDescriptions()
                            )
                            ->required()
                            ->in(
                                fn (Get $get) => array_keys(
                                    $this->dticketConfig($get)->exemptionReasonsAsTranslatedOptions()
                                )
                            )
                            ->visible($this->validSemesterSelected(...)),

                        Textarea::make('comment')
                            ->label(__('stura.fields.comment'))
                            ->rows(5)
                            ->nullable()
                            ->visible($this->validSemesterSelected(...)),

                        FileUpload::make('attachments')
                            ->label(__('stura.fields.attachments'))
                            ->multiple()
                            ->disk('local')
                            ->directory('attachments/'.Auth::id())
                            ->storeFileNamesIn('attachment_filenames')
                            ->required()
                            ->acceptedFileTypes(['application/pdf', 'image/jpg', 'image/jpeg', 'image/png'])
                            ->maxSize(5120)
                            ->maxFiles(5)
                            ->visible($this->validSemesterSelected(...)),

                    ]),

                BankingAccountSection::make()
                    ->visible($this->validSemesterSelected(...)),

                Section::make(__('stura.dticket.consent.title'))
                    ->schema([
                        Checkbox::make('consent')
                            ->label(__('stura.dticket.consent.privacy'))
                            ->accepted(),

                        Checkbox::make('consent2')
                            ->label(__('stura.dticket.consent.remove_permission'))
                            ->accepted(),

                        Checkbox::make('consent3')
                            ->label(__('stura.dticket.consent.truth'))
                            ->accepted(),
                    ])
                    ->visible($this->validSemesterSelected(...)),

                Section::make(__('stura.dticket.missing_config.title'))
                    ->description(__('stura.dticket.missing_config.description'))
                    ->schema([
                        Placeholder::make('message')
                            ->hiddenLabel()
                            ->content(__('stura.dticket.missing_config.message')),
                    ])
                    ->visible($this->semesterConfigMissing(...)),
            ])
            ->statePath('data');
    }

    protected function validSemesterSelected(Get $get): bool
    {
        $semester = $get('semester');

        return $semester !== null && $this->dticketConfig($semester) !== null;
    }

    protected function semesterConfigMissing(Get $get): bool
    {
        $semester = $get('semester');

        return $semester !== null && $this->dticketConfig($semester) === null;
    }

    public function store()
    {
        $data = $this->form->getState();

        // Semester not configured yet
        if ($data['semester'] === null || $this->dticketConfig($data['semester']) === null) {
            return Redirect::route('dashboard');
        }

        unset($data['consent'], $data['consent2'], $data['consent3']);

        if (Auth::user()->hasLoginLinks()) {
            $data['alerts'] = 'has-login-link';
        } elseif (Auth::user()->entitlements('semesterticket:'.$data['semester'])->isEmpty()) {
            $data['alerts'] = 'no-dticket-entitlement';
        }

        $dticketRequest = Auth::user()->dticketRequests()->create($data);

        return Redirect::route('requests.dticket.show', $dticketRequest);
    }

    public function render()
    {
        return view('livewire.dticket.dticket-request-form');
    }

    protected static function dticketConfig(Get|string $semester): ?DticketConfiguration
    {
        if (! is_string($semester)) {
            $semester = $semester('semester');
        }

        return DticketConfiguration::for($semester);
    }
}
