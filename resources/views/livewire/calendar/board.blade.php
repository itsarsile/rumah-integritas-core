@php
    $csrf = csrf_token();
    $eventsUrl = route('dashboard.calendar.events');
    $toggleUrl = route('dashboard.calendar.toggle');
@endphp
@push('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/main.min.css">
@endpush

<div id="calendar-board-root" class="card bg-white w-full border border-base-200 rounded-2xl">
    <div class="p-6 border-b border-base-200 flex items-center justify-between">
        <div class="card-title">Kalender Divisi</div>
        <div class="flex items-center gap-2">
            <select class="select select-bordered bg-white border-base-200" wire:model.live="module">
                @foreach($modules as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>

            @if(auth()->user()?->hasRole('admin'))
                <select class="select select-bordered bg-white border-base-200" wire:model.live="selectedDivisionId">
                    <option value="">— Pilih Divisi —</option>
                    @foreach($divisions as $d)
                        <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->code }})</option>
                    @endforeach
                </select>
            @else
                <span class="text-sm text-base-content/60">Divisi Anda:
                    {{ optional($divisions->firstWhere('id', $selectedDivisionId))->name ?? '—' }}</span>
            @endif
        </div>
    </div>

    <div class="p-4">
        <div id="calendar" class="w-full"></div>
        <p class="text-xs text-base-content/60 mt-2">Kalender hanya menampilkan tanda. Tambah/hapus tanda dilakukan via aksi di tabel modul.</p>
    </div>
</div>


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const root = document.getElementById('calendar-board-root');
            const compId = root ? root.getAttribute('wire:id') : null;
            const calendarEl = document.getElementById('calendar');
            let calendar;

            function getParams() {
                if (!compId || !window.Livewire) return { module: 'all', division_id: null };
                const comp = window.Livewire.find(compId);
                const module = comp ? comp.get('module') : 'all';
                const divisionId = comp ? comp.get('selectedDivisionId') : null;
                return { module, division_id: divisionId };
            }

            function buildCalendar() {
                calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    height: 'auto',
                    firstDay: 1,
                    locale: 'id',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,dayGridWeek'
                    },
                    events: function(fetchInfo, success, failure) {
                        const url = new URL("{{$eventsUrl}}", window.location.origin);
                        url.searchParams.set('start', fetchInfo.startStr);
                        url.searchParams.set('end', fetchInfo.endStr);
                        const latest = getParams();
                        if (latest.module) url.searchParams.set('module', latest.module);
                        if (latest.division_id) url.searchParams.set('division_id', latest.division_id);
                        fetch(url.toString(), { credentials: 'same-origin' })
                            .then(r => r.json())
                            .then(data => success(data))
                            .catch(err => failure(err));
                    },
                    eventClick: function(info){
                        if (info.event.url) {
                            window.location.href = info.event.url;
                            info.jsEvent.preventDefault();
                        }
                    }
                });
                calendar.render();
            }

            buildCalendar();

            // Refetch events when Livewire state changes
            Livewire.hook('message.processed', (message, component) => {
                if (component.fingerprint.name === 'calendar.board' && calendar) {
                    calendar.refetchEvents();
                }
            });

            // Refresh when mark changed from elsewhere (e.g., table action)
            window.addEventListener('calendar-mark-changed', () => {
                if (calendar) calendar.refetchEvents();
            });
        });
    </script>
@endpush
