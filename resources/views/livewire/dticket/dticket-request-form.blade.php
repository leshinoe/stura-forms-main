<div>
    <form wire:submit="store" class="max-w-2xl py-6 mx-auto">
        {{ $this->form }}

        @if($this->data['semester'] !== null && $this->dticketConfig($this->data['semester']) !== null)
            <x-primary-button class="mt-6">
                Submit
            </x-primary-button>
        @endif
    </form>

    <x-filament-actions::modals />
</div>
