@push('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/main.min.css">
@endpush

<div id="division-calendar-root" class="card bg-white w-full border border-base-200 rounded-2xl mt-6">
    <div class="p-4 border-b border-base-200 flex items-center justify-between">
        <div class="font-semibold">Kalender Divisi (Hanya Lihat)</div>
        <div class="flex items-center gap-2 text-xs">
            <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Konsumsi</span>
            <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Pemeliharaan</span>
            <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-600"></span> Agenda</span>
        </div>
    </div>

    @if(!$divisionId)
        <div class="p-4 text-sm text-base-content/70">Anda belum terasosiasi dengan divisi. Hubungi admin untuk mengatur divisi Anda.</div>
    @else
        <div class="p-4 space-y-3">
            <div id="fc-division-calendar" class="w-full"></div>
            <p class="text-xs text-base-content/60">Penandaan tanggal dilakukan lewat aksi pada tabel di atas. Kalender ini hanya menampilkan tanda.</p>
        </div>
    @endif
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const root = document.getElementById('division-calendar-root');
            const compId = root ? root.getAttribute('wire:id') : null;
            const calEl = document.getElementById('fc-division-calendar');
            if (!calEl) return;

            const eventsUrl = "{{ route('dashboard.calendar.events') }}";
            const toggleUrl = "{{ route('dashboard.calendar.toggle') }}";

            function getState() {
                const comp = compId ? window.Livewire.find(compId) : null;
                return {
                    module: @json($module),
                    divisionId: comp ? comp.get('divisionId') : @json($divisionId),
                };
            }

            const calendar = new FullCalendar.Calendar(calEl, {
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
                    const state = getState();
                    if (!state.divisionId) { success([]); return; }
                    const url = new URL(eventsUrl, window.location.origin);
                    url.searchParams.set('start', fetchInfo.startStr);
                    url.searchParams.set('end', fetchInfo.endStr);
                    url.searchParams.set('module', state.module);
                    url.searchParams.set('division_id', state.divisionId);
                    fetch(url.toString(), { credentials: 'same-origin' })
                        .then(r => r.json()).then(success).catch(failure);
                },
                eventClick: function(info){
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        info.jsEvent.preventDefault();
                    }
                }
            });
            calendar.render();

            Livewire.hook('message.processed', (message, component) => {
                if (component.fingerprint.id === compId) {
                    calendar.refetchEvents();
                }
            });

            // Refresh when mark changed from elsewhere
            window.addEventListener('calendar-mark-changed', () => {
                calendar.refetchEvents();
            });
        });
    </script>
@endpush
