{{-- resources/views/livewire/components/data-table.blade.php --}}
<div class="w-full">
    <!-- Header Controls -->
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Search -->
        <div class="relative flex-1 max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input 
                wire:model.live.debounce.300ms="search" 
                type="text" 
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                placeholder="Search..."
            >
            @if($search)
                <button 
                    wire:click="clearSearch" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                >
                    <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            @endif
        </div>

        <!-- Controls -->
        <div class="flex items-center space-x-4">
            <!-- Filter Toggle -->
            @if(!empty($filterableColumns))
                <button 
                    wire:click="toggleFilters"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                    </svg>
                    Filters
                    @if(collect($filters)->filter()->count())
                        <span class="ml-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            {{ collect($filters)->filter()->count() }}
                        </span>
                    @endif
                </button>
            @endif

            <!-- Per Page -->
            <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-700">Show:</label>
                <select wire:model.live="perPage" class="select">
                    @foreach($perPageOptions as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Filters Panel -->
    @if($showFilters && !empty($filterableColumns))
        <div class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($filterableColumns as $filter)
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">{{ $filter['label'] }}</label>
                        
                        @if($filter['type'] === 'select')
                            <select wire:model.live="filters.{{ $filter['key'] }}" class="select">
                                <option value="">All</option>
                                @foreach($filter['options'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        
                        @elseif($filter['type'] === 'date')
                            <input wire:model.live="filters.{{ $filter['key'] }}" type="date" class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        
                        @elseif($filter['type'] === 'date_range')
                            <div class="flex space-x-2">
                                <input wire:model.live="filters.{{ $filter['key'] }}.from" type="date" placeholder="From" class="input">
                                <input wire:model.live="filters.{{ $filter['key'] }}.to" type="date" placeholder="To" class="input">
                            </div>
                        
                        @elseif($filter['type'] === 'number_range')
                            <div class="flex space-x-2">
                                <input wire:model.live="filters.{{ $filter['key'] }}.min" type="number" placeholder="Min" class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <input wire:model.live="filters.{{ $filter['key'] }}.max" type="number" placeholder="Max" class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        
                        @else
                            <input wire:model.live="filters.{{ $filter['key'] }}" type="text" class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @endif
                    </div>
                @endforeach
            </div>
            
            @if(collect($filters)->filter()->count())
                <div class="mt-4 flex justify-end">
                    <button wire:click="clearFilters" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Clear Filters
                    </button>
                </div>
            @endif
        </div>
    @endif

    <!-- Loading State -->
    <div wire:loading class="flex justify-center items-center py-4">
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Loading...
    </div>

    <!-- Table -->
    <div class="shadow overflow-x-auto border-b border-gray-200 sm:rounded-lg" wire:loading.class="opacity-50">
        <table class="{{ $tableClass }} table-pin-cols">
            <thead class="bg-gray-50">
                <tr>
                    @foreach($columns as $column)
                        <th class="{{ $headerClass }} {{ $column['class'] ?? '' }}">
                            @if(in_array($column['key'], array_column($sortableColumns, 'key')))
                                <button wire:click="sortBy('{{ $column['key'] }}')" class="group inline-flex items-center space-x-1 text-left font-medium hover:text-gray-900">
                                    <span>{{ $column['label'] }}</span>
                                    <span class="flex-none ml-2 rounded text-gray-400 group-hover:text-gray-700">
                                        @if($sortBy === $column['key'])
                                            @if($sortDirection === 'asc')
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        @else
                                            <svg class="w-3 h-3 opacity-0 group-hover:opacity-100" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path>
                                            </svg>
                                        @endif
                                    </span>
                                </button>
                            @else
                                {{ $column['label'] }}
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="{{ $rowClass }}">
                @forelse($rows as $row)
                    <tr class="hover:bg-gray-50">
                        @foreach($columns as $column)
                            <td class="{{ $cellClass }} {{ $column['class'] ?? '' }}">
                                @if(isset($column['component']))
                                    @livewire($column['component'], ['row' => $row], key($row->id . '-' . $column['key']))
                                @elseif(isset($column['view']))
                                    @php
                                        $viewData = [];
                                        if (isset($column['viewData'])) {
                                            foreach ($column['viewData'] as $key => $value) {
                                                $viewData[$key] = $value === '' ? $row : data_get($row, $value);
                                            }
                                        }
                                    @endphp
                                    @include($column['view'], $viewData)
                                @elseif(isset($column['text']))
                                    {{ $column['text'] }}
                                @elseif(isset($column['format']))
                                    @switch($column['format'])
                                        @case('date')
                                            {{ $row->{$column['key']}?->format('M j, Y') }}
                                            @break
                                        @case('datetime')
                                            {{ $row->{$column['key']}?->format('M j, Y g:i A') }}
                                            @break
                                        @case('currency')
                                            ${{ number_format($row->{$column['key']}, 2) }}
                                            @break
                                        @case('number')
                                            {{ number_format($row->{$column['key']}) }}
                                            @break
                                        @case('status')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($row->{$column['key']} === 'pending')
                                                    bg-yellow-100 text-yellow-800
                                                @elseif($row->{$column['key']} === 'accepted' || $row->{$column['key']} === 'approved')
                                                    bg-green-100 text-green-800
                                                @elseif($row->{$column['key']} === 'rejected')
                                                    bg-red-100 text-red-800
                                                @else
                                                    bg-gray-100 text-gray-800
                                                @endif">
                                                @if($row->{$column['key']} === 'pending')
                                                    Menunggu
                                                @elseif($row->{$column['key']} === 'accepted' || $row->{$column['key']} === 'approved')
                                                    Disetujui
                                                @elseif($row->{$column['key']} === 'rejected')
                                                    Ditolak
                                                @else
                                                    {{ $row->{$column['key']} }}
                                                @endif
                                            </span>
                                            @break
                                        @case('boolean')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $row->{$column['key']} ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $row->{$column['key']} ? 'Yes' : 'No' }}
                                            </span>
                                            @break
                                        @default
                                            {{ data_get($row, $column['key']) }}
                                    @endswitch
                                @else
                                    {{ data_get($row, $column['key']) }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) }}" class="px-6 py-4 text-center text-gray-500">
                            @if($search || collect($filters)->filter()->count())
                                No results found for your search criteria.
                            @else
                                No data available.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($rows->hasPages())
        <div class="mt-4">
            {{ $rows->links() }}
        </div>
    @endif

    <!-- Results Info -->
    <div class="mt-4 flex items-center justify-between text-sm text-gray-700">
        <div>
            Showing {{ $rows->firstItem() ?? 0 }} to {{ $rows->lastItem() ?? 0 }} of {{ $rows->total() }} results
        </div>
        <div>
            Page {{ $rows->currentPage() }} of {{ $rows->lastPage() }}
        </div>
    </div>
</div>
