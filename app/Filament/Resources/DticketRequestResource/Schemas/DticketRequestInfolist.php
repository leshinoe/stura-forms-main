<?php

namespace App\Filament\Resources\DticketRequestResource\Schemas;

use App\Models\Dticket\DticketRequest;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

class DticketRequestInfolist
{
    public static function schema(Infolist $infolist): array
    {

        if (Auth::user()->is_admin) {
            $dticketRequests = $infolist->record->user
                ->dticketRequests()
                ->where('semester', $infolist->record->semester)
                ->where('id', '!=', $infolist->record->id)
                ->get();
        } else {
            $dticketRequests = collect();
        }

        return [

            ViewEntry::make('alert_one_time_token')
                ->label(__('stura.fields.alerts'))
                ->view('filament.alerts.one-time-token')
                ->visible(
                    fn (DticketRequest $record) => $record->alerts === 'one-time-token' && Auth::user()->is_admin
                ),

            ViewEntry::make('alert_has_login_link')
                ->label(__('stura.fields.alerts'))
                ->view('filament.alerts.has-login-link')
                ->visible(
                    fn (DticketRequest $record) => $record->alerts === 'has-login-link' && Auth::user()->is_admin
                ),

            ViewEntry::make('alert_no_dticket_entitlement')
                ->label(__('stura.fields.alerts'))
                ->view('filament.alerts.no-dticket-entitlement')
                ->visible(
                    fn (DticketRequest $record) => $record->alerts === 'no-dticket-entitlement' && Auth::user()->is_admin
                ),

            ViewEntry::make('alert_multiple_submissions')
                ->label(__('stura.fields.multiple_submissions'))
                ->view('filament.alerts.multiple-submissions', [
                    'dticketRequests' => $dticketRequests ?? [],
                ])
                ->visible(
                    fn (DticketRequest $record) => Auth::user()->is_admin && $dticketRequests->isNotEmpty()
                ),

            Section::make(__('stura.dticket.status.title'))
                ->description(__('stura.dticket.status.description'))
                ->columns(2)
                ->schema([
                    TextEntry::make('status')
                        ->label(__('stura.fields.status'))
                        ->formatStateUsing(fn (DticketRequest $record) => $record->statusLabel())
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'pending' => 'warning',
                            'rejected' => 'danger',
                            'approved' => 'success',
                            'paid' => 'success',
                        }),

                    TextEntry::make('created_at')
                        ->label(__('stura.fields.created_at'))
                        ->formatStateUsing(
                            fn (DticketRequest $record) => $record->created_at->format('d.m.Y')
                        ),

                    TextEntry::make('reason_for_rejection_text')
                        ->label(__('stura.fields.reason_for_rejection'))
                        ->state(fn (DticketRequest $record) => $record->rejectionText())
                        ->visible(fn (DticketRequest $record) => $record->isRejected())
                        ->columnSpan(2),
                ]),

            Section::make(__('stura.dticket.about.title'))
                ->description(__('stura.dticket.about.description'))
                ->columns(2)
                ->schema([
                    TextEntry::make('name')
                        ->label(__('stura.fields.name'))
                        ->state(fn (DticketRequest $record) => $record->user->name),

                    TextEntry::make('email')
                        ->label(__('stura.fields.email'))
                        ->state(fn (DticketRequest $record) => $record->user->email),
                ]),

            Section::make(__('stura.dticket.exemption.title'))
                ->description(__('stura.dticket.exemption.description'))
                ->schema([

                    TextEntry::make('semester')
                        ->label(__('stura.fields.semester')),

                    TextEntry::make('excluded_months')
                        ->label(__('stura.fields.months'))
                        ->helperText(__('stura.dticket.exemption.months_help_text'))
                        ->state(fn (DticketRequest $record) => $record->monthsLabel()),

                    TextEntry::make('reason_text')
                        ->label(__('stura.fields.reason'))
                        ->state(fn (DticketRequest $record) => $record->reasonText()),

                    TextEntry::make('comment')
                        ->label(__('stura.fields.comment')),

                    RepeatableEntry::make('attachments')
                        ->label(__('stura.fields.attachments'))
                        ->schema([

                            TextEntry::make('')
                                ->label(__('stura.fields.file'))
                                ->formatStateUsing(
                                    fn (DticketRequest $record, string $state) => $record->attachment_filenames[$state]
                                )
                                ->url(fn (string $state) => url($state)),
                        ]),

                ]),

            Section::make(__('stura.dticket.banking.title'))
                ->schema([

                    TextEntry::make('banking_name')
                        ->label(__('stura.fields.banking_name')),

                    TextEntry::make('banking_iban')
                        ->label(__('stura.fields.banking_iban')),

                    TextEntry::make('banking_bic')
                        ->label(__('stura.fields.banking_bic')),

                    TextEntry::make('banking_amount')
                        ->label(__('stura.fields.banking_amount'))
                        ->state(fn (DticketRequest $record) => Number::format(
                            $record->number_of_months * 29.4,
                            precision: 2,
                            locale: 'de'
                        ).' €'),

                    TextEntry::make('banking_reference')
                        ->label(__('stura.fields.banking_reference'))
                        ->state(fn (DticketRequest $record) => "Erstattung SEMTIX {$record->semester}"),
                ]),

            Section::make(__('stura.dticket.consent.title'))
                ->schema([
                    TextEntry::make('consent')
                        ->label(__('stura.dticket.consent.privacy_title'))
                        ->state(__('stura.dticket.consent.privacy')),

                    TextEntry::make('consent2')
                        ->label(__('stura.dticket.consent.remove_permission_title'))
                        ->state(__('stura.dticket.consent.remove_permission')),

                    TextEntry::make('consent3')
                        ->label(__('stura.dticket.consent.truth_title'))
                        ->state(__('stura.dticket.consent.truth')),
                ]),
        ];
    }
}
