<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="wireframe">

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name') }} - {{ $title ?? 'Page Title' }}</title>
        @vite('resources/css/app.css')
        @livewireStyles
</head>

<body>
        <div class="drawer lg:drawer-open">
                <input id="my-drawer" type="checkbox" class="drawer-toggle" />
                <div class="drawer-content">
                        <livewire:header-nav title="{{ $title }}" />
                        <div class="p-6 space-y-6">
                                {{ $slot }}
                        </div>
                </div>
                <div class="drawer-side bg-base-200">
                        <div class="flex items-center gap-4 p-4">
                                <x-app.logo class="w-10" />
                                <h1 class="mt-2">
                                        {{ config('app.name') }}
                                </h1>
                        </div>
                        <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
                        <!-- For avatar section -->
                        <div class="flex items-center gap-4 p-4">
                                <div class="avatar">
                                        <div class="w-12 rounded-full">
                                                <img
                                                        src="https://img.daisyui.com/images/profile/demo/spiderperson@192.webp" />
                                        </div>
                                </div>
                                <div class="flex flex-col">
                                        <span class="font-semibold">{{ Auth::user()->name }}</span>
                                        <span
                                                class="text-gray-600">{{ Auth::user()->hasRole('admin') ? 'Administrator' : (Auth::user()->hasRole('user') ? 'Pengaju' : '')}}</span>
                                </div>

                        </div>
                        <!-- For menu section -->                      
                         <livewire:menus />


                </div>
        </div>
        </div>
        @livewireScripts
</body>

</html>
