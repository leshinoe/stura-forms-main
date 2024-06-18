<?php

namespace App\Livewire\Dticket;

use App\Filament\Resources\DticketRequestResource\Schemas\DticketRequestInfolist;
use App\Models\Dticket\DticketRequest;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ViewSubmittedDticketRequest extends Component implements HasForms, HasInfolists
{
    use InteractsWithForms;
    use InteractsWithInfolists;

    #[Locked]
    public DticketRequest $dticket_request;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->dticket_request)
            ->schema(DticketRequestInfolist::schema($infolist));
    }

    public function render()
    {
        return view('livewire.dticket.dticket-request-view');
    }
}
