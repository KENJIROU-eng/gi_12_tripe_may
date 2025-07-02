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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Slabo+27px&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Oswald:wght@200..700&family=Playfair+Display:ital,wght@1,400..900&family=Playwrite+IN&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="icon" href="{{ asset('images/tripeas_logo_20250617.png') }}" type="image/x-icon">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <script src="https://unpkg.com/alpinejs" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        @livewireStyles

        {{-- Stylesheet --}}
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

    </head>
    <body data-user-id="{{ Auth::user()->id }}" class="page-transition">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')
            <!-- Page Content -->

            

            <main class="relative pt-12 min-h-screen" style="background-image: url('/images/mesut-kaya-eOcyhe5-9sQ-unsplash.jpg'); background-size: cover;">
                @if (Str::startsWith(Route::currentRouteName(), 'post.') || Str::startsWith(Route::currentRouteName(), 'group.'))
                    <div class="absolute inset-y-0  left-[3%] right-[3%] md:left-[10%] md:right-[10%] top-12 bottom-0 bg-gray-50 dark:bg-gray-800 z-0 "></div>
                @endif
                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </main>
            @if (!in_array(Route::currentRouteName(), ['message.show']))
                @include('layouts.footer')
            @endif
        </div>
        @stack('scripts')
        <script>
            window.appData = {
                groupIds: @json($groupIds ?? []),
                tripSchedule: @json($tripSchedule ?? []),
                tripName: @json($tripName ?? []),
                tripId: @json($tripId ?? []),
            };
            const routeUrls = @json($routeUrls ?? []);
        </script>
        @livewireScripts
    </body>
</html>
