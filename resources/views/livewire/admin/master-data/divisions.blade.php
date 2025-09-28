<div class="space-y-6">
    <h1 class="text-xl font-semibold">Master Data — Divisions</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-4 rounded-xl shadow">
        <div>
            <label class="label">Name</label>
            <input type="text" wire:model.defer="name" class="input input-bordered w-full" />
            @error('name') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">Code</label>
            <input type="text" wire:model.defer="code" class="input input-bordered w-full" />
            @error('code') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2">
            <label class="label">Parent Division (optional)</label>
            <select wire:model.defer="parent_div_id" class="select select-bordered w-full">
                <option value="">— None —</option>
                @foreach($divisions as $d)
                    <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->code }})</option>
                @endforeach
            </select>
            @error('parent_div_id') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2 flex justify-end">
            <button class="btn btn-primary">Save</button>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow p-4">
        <h2 class="font-semibold mb-2">All Divisions</h2>
        <ul class="list-disc list-inside text-sm">
            @foreach($divisions as $d)
                <li>{{ $d->name }} ({{ $d->code }})</li>
            @endforeach
        </ul>
    </div>
</div>
