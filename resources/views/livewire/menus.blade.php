<ul class="menu text-base-content min-h-full w-80 p-4">
    @php
        function isMenuActive($menu, $children) {
            $currentRoute = request()->route()->getName();
            $menuRoute = $menu->route ? $menu->route : '';
            if ($menuRoute && $currentRoute === $menuRoute) {
                return true;
            }
            if (isset($children[$menu->id])) {
                foreach ($children[$menu->id] as $child) {
                    $childRoute = $child->route ? $child->route : '';
                    if ($childRoute && $currentRoute === $childRoute) {
                        return true;
                    }
                }
            }
            return false;
        }
    @endphp


        

    @foreach ($menus['root'] as $menu)
        <li>
            @if (isset($menus['children'][$menu->id]))
                <details {{ isMenuActive($menu, $menus['children']) ? 'open' : '' }}>
                    <summary>
                        {!! $menu->icon ? svg($menu->icon)->class('w-4 h-4')->toHtml() : '' !!}
                        {{ $menu->name }}
                    </summary>
                    <ul>
                        @foreach ($menus['children'][$menu->id] as $child)
                            <li>
                                <a href="{{ $child->route ? route($child->route, '.') : '#' }}"
                                   class="{{ $child->route && request()->routeIs($child->route) ? 'menu-active' : '' }}">
                                    {!! $child->icon ? svg($child->icon)->class('w-4 h-4')->toHtml() : '' !!}
                                    {{ $child->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </details>
            @else
                @if ($menu->name === 'Logout')
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 w-full text-left">
                            {!! $menu->icon ? svg($menu->icon)->class('w-4 h-4')->toHtml() : '' !!}
                            {{ $menu->name }}
                        </button>
                    </form>
                @else
                    <a href="{{ $menu->route ? route($menu->route, '.') : '#' }}"
                       class="{{ $menu->route && request()->routeIs($menu->route) ? 'menu-active' : '' }}">
                        {!! $menu->icon ? svg($menu->icon)->class('w-4 h-4')->toHtml() : '' !!}
                        {{ $menu->name }}
                    </a>
                @endif
            @endif
        </li>
    @endforeach
</ul>