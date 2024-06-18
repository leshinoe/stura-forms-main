<x-guest-layout>

    <h1 class="text-xl font-semibold">
        {{ __('stura.welcome.title_1') }}<br />
        {{ __('stura.welcome.title_2') }}
    </h1>

    <p class="mt-6">
        {{ __('stura.welcome.description') }}
    </p>

    <p class="mt-6">
        <a
            href="{{ route('auth.saml2.redirect') }}"
            class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
            {{ __('stura.welcome.login') }} &rightarrow;
        </a>
    </p>

    <p class="mt-6">
        {{ __('stura.welcome.no_btu_account') }}
    </p>

    <div class="mt-6">
        @if(App::getLocale() === 'en')
            <a href="/" class="flex items-center">
                <x-flag-german class="w-auto h-6" />
                <span class="ml-3">auf Deutsch umschalten</span>
            </a>
        @else
            <a href="/en" class="flex items-center">
                <x-flag-english class="w-auto h-6" />
                <span class="ml-3">switch to English</span>
            </a>
        @endif
    </div>

</x-guest-layout>
