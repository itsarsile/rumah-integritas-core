<div class="sticky top-0 navbar z-10 border-b-1 bg-white {{ @request()->routeIs('login') ? 'hidden' : '' }}">
    <div class="navbar-start">
        <h1 class="text-xl font-bold cursor-default select-none ml-4">{{ $title }}</h1>
    </div>
    <div class="navbar-center hidden lg:flex">
    </div>
    <div class="navbar-end">
        <label for="my-drawer" class="btn btn-ghost drawer-button lg:hidden">
            <x-feathericon-menu />
        </label>
    </div>
</div>
