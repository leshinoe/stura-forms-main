<x-filament-widgets::widget>
    @foreach($semesters as $semester)
    <x-filament::section>
        <h3 class="text-xl font-semibold leading-6 text-gray-900">
            {{ $semester['semester'] }}
        </h3>
        <dl
            class="grid grid-cols-1 mt-5 overflow-hidden bg-white divide-y divide-gray-200 rounded-lg md:grid-cols-5 md:divide-x md:divide-y-0">
            <div class="px-4 py-5">
                <dt class="text-sm font-medium text-gray-500 truncate">
                    Alle
                </dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                    {{ $semester['all'] }}
                </dd>
                <p class="mt-4">
                    <a href="/admin/dticket-requests?activeTab=all" class="text-sm font-medium truncate text-primary-600 hover:text-primary-800">Anzeigen &rightarrow;</a>
                </p>
            </div>
            <div class="px-4 py-5">
                <dt class="text-sm font-medium text-gray-500 truncate">
                    Zu Bearbeiten
                </dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                    {{ $semester['pending'] }}
                </dd>
                <p class="mt-4">
                    <a href="/admin/dticket-requests?activeTab=pending" class="text-sm font-medium truncate text-primary-600 hover:text-primary-800">Anzeigen &rightarrow;</a>
                </p>
            </div>
            <div class="px-4 py-5">
                <dt class="text-sm font-medium text-gray-500 truncate">
                    Angenommen
                </dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                    {{ $semester['approved'] }}
                </dd>
                <p class="mt-4">
                    <a href="/admin/dticket-requests?activeTab=approved" class="text-sm font-medium truncate text-primary-600 hover:text-primary-800">Anzeigen &rightarrow;</a>
                </p>
            </div>
            <div class="px-4 py-5">
                <dt class="text-sm font-medium text-gray-500 truncate">
                    Ausgezahlt
                </dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                    {{ $semester['paid'] }}
                </dd>
                <p class="mt-4">
                    <a href="/admin/dticket-requests?activeTab=paid" class="text-sm font-medium truncate text-primary-600 hover:text-primary-800">Anzeigen &rightarrow;</a>
                </p>
            </div>
            <div class="px-4 py-5">
                <dt class="text-sm font-medium text-gray-500 truncate">
                    Abgelehnt
                </dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                    {{ $semester['rejected'] }}
                </dd>
                <p class="mt-4">
                    <a href="/admin/dticket-requests?activeTab=rejected" class="text-sm font-medium truncate text-primary-600 hover:text-primary-800">Anzeigen &rightarrow;</a>
                </p>
            </div>
        </dl>

        <div class="mt-5">
            <p class="text-sm font-medium text-gray-500">Befreite Monate für die Abrechnung:</p>
            <p class="flex mt-2 space-x-4 text-gray-600">
                <span>
                    Ein Monat: {{ $semester['months'][1] ?? '0' }}
                </span>
                <span>&middot;</span>
                <span>
                    Zwei Monate: {{ $semester['months'][2] ?? '0' }}
                </span>
                <span>&middot;</span>
                <span>
                    Drei Monate: {{ $semester['months'][3] ?? '0' }}
                </span>
                <span>&middot;</span>
                <span>
                    Vier Monate: {{ $semester['months'][4] ?? '0' }}
                </span>
                <span>&middot;</span>
                <span>
                    Fünf Monate: {{ $semester['months'][5] ?? '0' }}
                </span>
                <span>&middot;</span>
                <span>
                    Sechs Monate: {{ $semester['months'][6] ?? '0' }}
                </span>
            </p>
        </div>
    </x-filament::section>
    @endforeach

</x-filament-widgets::widget>
