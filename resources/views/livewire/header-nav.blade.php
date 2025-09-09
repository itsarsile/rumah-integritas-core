<div class="sticky top-0 navbar z-10 bg-base-100 border-b-2 border-base-200 {{ @request()->routeIs('login') ? 'hidden' : '' }}">
    <div class="navbar-start">
        <a class="btn btn-ghost text-xl">{{ $title }}</a>
    </div>
    <div class="navbar-center hidden lg:flex">
    </div>
    <div class="navbar-end">
        <label for="my-drawer" class="btn btn-ghost drawer-button lg:hidden">
            <x-feathericon-menu />
        </label>
    </div>
</div>