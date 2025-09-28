<div class="space-y-6">
    <h1 class="text-xl font-semibold">Master Data — Regional Government Organizations</h1>

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
            <label class="label">Address</label>
            <textarea wire:model.defer="address" class="textarea textarea-bordered w-full"></textarea>
            @error('address') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">Phone</label>
            <input type="text" wire:model.defer="phone" class="input input-bordered w-full" />
            @error('phone') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">Email</label>
            <input type="email" wire:model.defer="email" class="input input-bordered w-full" />
            @error('email') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">Website</label>
            <input type="url" wire:model.defer="website" class="input input-bordered w-full" />
            @error('website') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">Status</label>
            <select wire:model.defer="status" class="select select-bordered w-full">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            @error('status') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2">
            <label class="label">Region</label>
            <select wire:model.defer="region_id" class="select select-bordered w-full">
                <option value="">— Select Region —</option>
                @foreach($regions as $r)
                    <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->code }})</option>
                @endforeach
            </select>
            @error('region_id') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2 flex justify-end">
            <button class="btn btn-primary">Save</button>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow p-4">
        <h2 class="font-semibold mb-2">Recent</h2>
        <ul class="list-disc list-inside text-sm">
            @foreach($recent as $row)
                <li>{{ $row->name }} ({{ $row->code }}) — {{ ucfirst($row->status) }}</li>
            @endforeach
        </ul>
    </div>
</div>
