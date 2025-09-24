@props(['menus' => [], 'field'])

@if(empty($menus))
    <p class="text-sm text-base-content/60">Tidak ada menu tersedia.</p>
@else
    <ul class="space-y-3">
        @foreach($menus as $menu)
            <li class="space-y-2" wire:key="{{ $field }}-{{ $menu['id'] }}">
                <label class="flex items-center gap-3">
                    <input type="checkbox" class="checkbox checkbox-sm" value="{{ $menu['id'] }}" wire:model.defer="{{ $field }}">
                    <span class="font-medium text-base-content">{{ $menu['name'] }}</span>
                </label>

                @if(!empty($menu['children']))
                    <div class="pl-6 ml-3 border-l border-dashed border-base-300 space-y-2">
                        <x-menu-selector :menus="$menu['children']" :field="$field" />
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
@endif
