<div class="flex">
    <a class="size-7 flex items-center justify-center rounded-lg hover:bg-neutral-200/70" href="{{ route('dashboard.audit.show', $audit->id) }}">
        <i class="fas fa-eye text-neutral-500"></i>
    </a>
    <a class="size-7 flex items-center justify-center rounded-lg hover:bg-neutral-200/70" href="{{ route('dashboard.audit.chat', $audit->id) }}">
        <i class="fas fa-comment-dots text-neutral-500"></i>
    </a>
</div>