<div class="flex items-center gap-1">
    <a class="size-7 flex items-center justify-center rounded-lg hover:bg-neutral-200/70" href="{{ route('dashboard.maintenance.show', $maintenance->id) }}" title="Lihat">
        <i class="fas fa-eye text-neutral-500"></i>
    </a>
    @php $canMark = auth()->user()?->hasRole('admin') || auth()->user()?->hasRole('manager'); @endphp
    @if($canMark)
        <button type="button" class="size-7 flex items-center justify-center rounded-lg hover:bg-neutral-200/70" title="Tandai kalender"
            onclick="(function(){if(!confirm('Tambah/hapus tanda kalender untuk tanggal ini?')) return; fetch('{{ route('dashboard.calendar.toggle') }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},credentials:'same-origin',body:JSON.stringify({module:'maintenance',date:'{{ $maintenance->created_at->format('Y-m-d') }}',division_id: {{ (int)($maintenance->division_id ?? auth()->user()->division_id) }},entity_id: {{ (int)$maintenance->id }},entity_type: 'maintenance' })}).then(r=>r.json()).then(()=>{ window.dispatchEvent(new CustomEvent('calendar-mark-changed')); window.dispatchEvent(new CustomEvent('ri-toast',{detail:{text:'Kalender diperbarui'}})); }).catch(()=>{});})();">
            <i class="fas fa-calendar-check text-primary"></i>
        </button>
    @else
        <button type="button" class="size-7 flex items-center justify-center rounded-lg opacity-40 cursor-not-allowed" title="Hanya admin/manager yang dapat menandai" disabled>
            <i class="fas fa-calendar-check"></i>
        </button>
    @endif
</div>
