<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('stura.dashboard.title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <section class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="text-gray-900">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold">{{ __('stura.dashboard.hello') }} {{ Auth::user()->name }}!</h2>

                        <p class="mt-2">{{ __('stura.dashboard.description') }}</p>
                    </div>

                    <div class="p-6 border-t border-gray-200">
                        <dl class="grid lg:grid-cols-3">
                            <div>
                                <dt class="font-medium leading-6 text-gray-950">
                                    {{ __('stura.fields.name') }}
                                </dt>
                                <dd class="leading-6">
                                    {{ Auth::user()->name }}
                                </dd>
                            </div>
                            <div>
                                <dt class="font-medium leading-6 text-gray-950">
                                    {{ __('stura.fields.email') }}
                                </dt>
                                <dd class="leading-6">
                                    {{ Auth::user()->email }}
                                </dd>
                            </div>
                            <div>
                                <dt class="font-medium leading-6 text-gray-950">
                                    {{ __('stura.fields.btu_id') }}
                                </dt>
                                <dd class="leading-6">
                                    {{ Auth::user()->btu_id }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="p-6 border-t border-gray-200">
                        <p>
                            <a href="{{ route('requests.dticket') }}"
                                class="font-medium text-blue-500 hover:underline hover:text-blue-700">
                                {{ __('stura.dashboard.dticket_request') }} &rightarrow;
                            </a>
                        </p>
                    </div>

                    <div class="p-6">
                        @if(App::getLocale() === 'en')
                            <form action="{{ route('locale') }}" method="post">
                                @csrf
                                <button type="submit" class="flex items-center">
                                    <x-flag-german class="w-auto h-6" />
                                    <span class="ml-3">auf Deutsch umschalten</span>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('locale') }}" method="post">
                                @csrf
                                <button type="submit" class="flex items-center">
                                    <x-flag-english class="w-auto h-6" />
                                    <span class="ml-3">switch to English</span>
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            </section>

            @if (Auth::user()->dticketRequests->isNotEmpty())
                <section class="mt-12 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-xl font-semibold">
                            {{ __('stura.dashboard.dticket_section_title') }}
                        </h2>

                        <ul role="list" class="grid grid-cols-1 mt-4 gap-x-6 gap-y-8 lg:grid-cols-3 xl:gap-x-8">
                            @foreach (Auth::user()->dticketRequests->reverse() as $dticketRequest)
                                <li
                                    class="relative overflow-hidden transition border border-gray-200 hover:shadow-lg rounded-xl">
                                    <div class="flex items-center p-6 border-b gap-x-4 border-gray-900/5 bg-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="flex-none object-cover w-12 h-12 p-3 bg-white rounded-lg ring-1 ring-gray-900/10">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                                        </svg>

                                        <div class="text-sm font-medium leading-6 text-gray-900">
                                            <h3>
                                                <a href="{{ route('requests.dticket.show', $dticketRequest) }}">
                                                    {{ $dticketRequest->semester }}
                                                    <span class="absolute inset-0"></span>
                                                </a>
                                            </h3>
                                        </div>
                                    </div>
                                    <dl class="px-6 py-4 -my-3 text-sm leading-6 divide-y divide-gray-100">
                                        <div class="flex justify-between py-3 gap-x-4">
                                            <dt class="text-gray-500">
                                                {{ __('stura.fields.created_at') }}
                                            </dt>
                                            <dd class="text-gray-700">
                                                <time datetime="{{ $dticketRequest->created_at->format('Y-m-d') }}">
                                                    {{ $dticketRequest->created_at->format('d.m.Y') }}
                                                </time>
                                            </dd>
                                        </div>
                                        <div class="flex justify-between py-3 gap-x-4">
                                            <dt class="text-gray-500">
                                                {{ __('stura.fields.status') }}
                                            </dt>
                                            <dd class="flex items-start gap-x-2">
                                                <div @class([
                                                    'rounded-md py-1 px-2 text-xs font-medium ring-1 ring-inset',
                                                    'text-green-700 bg-green-50 ring-green-600/20' =>
                                                        $dticketRequest->isApproved() || $dticketRequest->isPaid(),
                                                    'text-yellow-700 bg-yellow-50 ring-yellow-600/20' => $dticketRequest->isPending(),
                                                    'text-red-700 bg-red-50 ring-red-600/20' => $dticketRequest->isRejected(),
                                                ])>
                                                    {{ $dticketRequest->statusLabel() }}
                                                </div>
                                            </dd>
                                        </div>
                                    </dl>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </section>
            @endif

            {{-- @if (Auth::user()->is_admin)
                <section class="mt-12 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        @dump(Auth::user()->toArray())
                    </div>
                </section>
            @endif --}}
        </div>
    </div>
</x-app-layout>
