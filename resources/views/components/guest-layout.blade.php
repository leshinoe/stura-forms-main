<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css'])
    </head>
    <body class="font-sans antialiased text-gray-900">
        <div class="flex flex-col items-center min-h-screen pt-6 bg-gray-100 sm:justify-center sm:pt-0">

            <div class="w-full overflow-hidden bg-white shadow-md sm:max-w-lg sm:rounded-lg">
                <div class="flex items-center justify-center p-6">
                    <a href="/">
                        <x-application-logo class="w-auto h-10 text-gray-500 fill-current" />
                    </a>
                </div>

                <main class="p-6 border-t border-gray-200">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
