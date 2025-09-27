<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="ri">

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name') }} - {{ $title ?? 'Page Title' }}</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" integrity="sha512-...snipped..." crossorigin="anonymous" referrerpolicy="no-referrer" />
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
        @livewireStyles
</head>

<body>
        <div class="drawer lg:drawer-open max-h-screen overflow-scroll">
                <input id="my-drawer" type="checkbox" class="drawer-toggle" />
                <div class="drawer-content">
                        <livewire:header-nav title="{{ $title ?? 'Page Title' }}" />
                        <div class="p-6 bg-neutral-50 space-y-6 h-full">
                                {{ $slot }}
                        </div>
                </div>
                @auth
                <div class="drawer-side border-r bg-white border-base-200 lg:flex lg:flex-col lg:relative">
                        <div>
                                <div class="flex items-center gap-4 px-6 py-3">
                                        <x-app.logo class="w-8" />
                                        <h1 class="mt-2 font-semibold">
                                                {{ config('app.name') }}
                                        </h1>
                                </div>
                                <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
                                <!-- For menu section -->
                                <livewire:menus />
                        </div>
                        <div class="absolute bottom-0 w-full">
                                <livewire:sidebar-user />
                        </div>
                        <!-- For avatar section -->
                </div>
                @endauth
        </div>
        @livewireScripts
</body>

</html>
