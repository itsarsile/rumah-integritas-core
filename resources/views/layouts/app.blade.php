<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="ri">

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name') }} - {{ $title ?? 'Page Title' }}</title>
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
        @livewireStyles
</head>

<body>
        <div class="drawer lg:drawer-open">
                <input id="my-drawer" type="checkbox" class="drawer-toggle" />
                <div class="drawer-content">
                        <livewire:header-nav title="{{ $title ?? 'Page Title' }}" />
                        <div class="p-6 space-y-6 bg-base-200 h-full">
                                {{ $slot }}
                        </div>
                </div>
                @auth
                <div class="drawer-side border-r bg-white">
                        <div class="flex items-center gap-4 p-4">
                                <x-app.logo class="w-10" />
                                <h1 class="mt-2">
                                        {{ config('app.name') }}
                                </h1>
                        </div>
                        <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
                        <!-- For avatar section -->
                        <livewire:sidebar-user />
                        <!-- For menu section -->
                        <livewire:menus />
                </div>
                @endauth
        </div>
        @livewireScripts
</body>

</html>
