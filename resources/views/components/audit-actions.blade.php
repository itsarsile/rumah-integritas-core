<div>
    <a class="btn btn-xs" href="{{ route('dashboard.audit.show', $audit->id) }}">
        <x-feathericon-eye class="w-4 h-4" />
    </a>
    <a href="{{ route('dashboard.audit.chat', $audit->id) }}" class="btn btn-xs">
        <x-lucide-message-circle class="w-4 h-4" />
    </a>
</div>