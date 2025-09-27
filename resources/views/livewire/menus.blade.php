<ul class="menu text-base-content w-80 p-4 z-100 space-y-2">
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
                @php $isActive = isMenuActive($menu, $menus['children']); @endphp
                <details {{ $isActive ? 'open' : '' }}>
                    <summary class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors duration-150 {{ $isActive ? 'bg-primary/10 text-primary font-semibold' : 'text-base-content/80 hover:bg-base-200' }}">
                        {!! $menu->icon ? svg($menu->icon)->class('w-4 h-4')->toHtml() : '' !!}
                        {{ $menu->name }}
                    </summary>
                    <ul>
                        @foreach ($menus['children'][$menu->id] as $child)
                            @php $childActive = $child->route && request()->routeIs($child->route); @endphp
                            <li>
                                <a href="{{ $child->route ? route($child->route, '.') : '#' }}"
                                   class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors duration-150 {{ $childActive ? 'bg-primary/10 text-primary font-semibold my-1' : 'text-base-content/80 hover:bg-base-200' }}">
                                    {!! $child->icon ? svg($child->icon)->class('w-4 h-4')->toHtml() : '' !!}
                                    {{ $child->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </details>
            @else
                @if ($menu->name === 'Logout')
                    <form action="{{ route('logout') }}" method="POST" class="hidden" >
                        @csrf
                        <button type="submit" class="flex items-center gap-2 w-full text-left rounded-lg">
                            {!! $menu->icon ? svg($menu->icon)->class('w-4 h-4')->toHtml() : '' !!}
                            {{ $menu->name }}
                        </button>
                    </form>
                @else
                    @php $rootActive = $menu->route && request()->routeIs($menu->route); @endphp
                    <a href="{{ $menu->route ? route($menu->route, '.') : '#' }}"
                       class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors duration-150 {{ $rootActive ? 'bg-primary/10 text-primary font-semibold' : 'text-base-content/80 hover:bg-base-200' }}">
                        {!! $menu->icon ? svg($menu->icon)->class('w-4 h-4')->toHtml() : '' !!}
                        {{ $menu->name }}
                    </a>
                @endif
            @endif
        </li>
    @endforeach
</ul>
