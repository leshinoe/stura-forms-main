<?php

namespace App\Filament\Resources\DticketRequestResource\Pages;

use App\Filament\Resources\DticketRequestResource;
use App\Models\Dticket\DticketRequest;
use Filament\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\MaxWidth;

class ViewDticketRequest extends ViewRecord
{
    protected static string $resource = DticketRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('approve')
                ->label('Annehmen')
                ->requiresConfirmation()
                ->action(fn () => $this->record->update(['status' => 'approved']))
                ->color('success')
                ->visible(fn () => $this->record->status === 'pending'),

            Action::make('reject')
                ->label('Ablehnen')
                ->requiresConfirmation()
                ->slideOver()
                ->modalWidth(MaxWidth::ThreeExtraLarge)
                ->form([

                    Radio::make('reason_for_rejection')
                        ->label('Grund für Ablehnung')
                        ->helperText('Der Grund der Ablehnung wird automatisch in Deutsch oder Englisch angezeigt.')
                        ->options(fn (DticketRequest $record) => [
                            ...$record->dticket_config->rejectionReasonsAsTranslatedOptions(),
                            'other' => 'Anderer Grund',
                        ])
                        ->live()
                        ->required(),

                    Textarea::make('reason_for_rejection_other_de')
                        ->label('Anderer Grund (DE)')
                        ->requiredIf('reason_for_rejection', 'other')
                        ->rows(5)
                        ->visible(fn (Get $get) => $get('reason_for_rejection') === 'other'),

                    Textarea::make('reason_for_rejection_other_en')
                        ->label('Anderer Grund (EN)')
                        ->requiredIf('reason_for_rejection', 'other')
                        ->rows(5)
                        ->visible(fn (Get $get) => $get('reason_for_rejection') === 'other'),

                ])
                ->action(function (array $data, DticketRequest $record): void {

                    $rejectionReason = $data['reason_for_rejection'];

                    if ($rejectionReason === 'other') {
                        $rejectionReason = [
                            'de' => $data['reason_for_rejection_other_de'],
                            'en' => $data['reason_for_rejection_other_en'],
                        ];
                    } else {
                        $rejectionReason = $record->dticket_config->rejectionReasonsAsOptions()[$rejectionReason];
                    }

                    $record->update([
                        'status' => 'rejected',
                        'reason_for_rejection' => $rejectionReason,
                    ]);
                })
                ->color('danger')
                ->visible(fn () => $this->record->status === 'pending'),

            Action::make('reopen')
                ->label('In Bearbeitung zurücksetzen')
                ->requiresConfirmation()
                ->action(fn () => $this->record->update(['status' => 'pending']))
                ->color('primary')
                ->visible(fn () => in_array($this->record->status, ['approved', 'rejected'])),

            Action::make('pay')
                ->label('Als Bezahlt markieren')
                ->requiresConfirmation()
                ->action(fn () => $this->record->update(['status' => 'paid']))
                ->color('success')
                ->visible(fn () => $this->record->status === 'approved'),

            Action::make('unpay')
                ->label('Als nicht bezahlt markieren')
                ->requiresConfirmation()
                ->action(fn () => $this->record->update(['status' => 'approved']))
                ->color('danger')
                ->visible(fn () => $this->record->status === 'paid'),
        ];
    }
}
