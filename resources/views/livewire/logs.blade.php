<div class="space-y-6">
    <div class="card bg-base-100">
        <div class="card-body">
            <h2 class="card-title">Log Activity</h2>
            <p class="text-sm text-base-content/60">Form Monitoring Activities</p>

            <div class="mt-4 flex flex-wrap items-center gap-3">
                <div class="dropdown">
                    <div tabindex="0" role="button" class="btn btn-sm">
                        {{ $month ? date('F', mktime(0,0,0,$month,1)) : 'Bulan' }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
                    </div>
                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52 max-h-80 overflow-auto">
                        <li><a wire:click="$set('month','')">Semua</a></li>
                        @for($i=1;$i<=12;$i++)
                        <li><a wire:click="$set('month',{{ $i }})">{{ date('F', mktime(0,0,0,$i,1)) }}</a></li>
                        @endfor
                    </ul>
                </div>

                <div class="dropdown">
                    <div tabindex="0" role="button" class="btn btn-sm">
                        {{ $year ?: 'Tahun' }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
                    </div>
                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-40 max-h-80 overflow-auto">
                        <li><a wire:click="$set('year','')">Semua</a></li>
                        @foreach($years as $y)
                          <li><a wire:click="$set('year',{{ $y }})">{{ $y }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="ml-auto">
                    <label class="input input-bordered input-sm flex items-center gap-2">
                        <input type="text" class="grow" placeholder="Search" wire:model.debounce.300ms="search" />
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70"><path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" /></svg>
                    </label>
                </div>
            </div>

            <div class="mt-6 space-y-6">
                @forelse($grouped as $date => $items)
                    <div>
                        <div class="font-semibold mb-2 text-base-content/80">
                            {{ \Illuminate\Support\Carbon::parse($date, $tz)->translatedFormat('l, d F Y') }}
                        </div>
                        <div class="space-y-3">
                            @foreach($items as $log)
                                <div class="flex items-start gap-3">
                                    <div class="avatar">
                                        @if($log->user?->avatar_url)
                                            <div class="w-8 h-8 rounded-full ring ring-primary ring-offset-base-100 ring-offset-1 overflow-hidden">
                                                <img src="{{ $log->user->avatar_url }}" alt="avatar" />
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-semibold">
                                                {{ $log->user?->initials ?? 'U' }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 p-3 rounded-box bg-base-200">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold">{{ $log->user->name ?? 'Unknown' }}</span>
                                            <span class="badge badge-ghost badge-sm">{{ ucfirst($log->module) }}</span>
                                            <span class="badge {{ in_array($log->action,['approved','accepted']) ? 'badge-success' : ($log->action=='rejected' ? 'badge-error' : 'badge-info') }} badge-sm">{{ ucfirst($log->action) }}</span>
                                            <span class="ml-auto text-xs opacity-60">{{ $log->created_at->timezone($tz)->format('H:i') }}</span>
                                        </div>
                                        <div class="text-sm mt-1">{{ $log->description }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-center text-sm opacity-60">Belum ada aktivitas.</div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
