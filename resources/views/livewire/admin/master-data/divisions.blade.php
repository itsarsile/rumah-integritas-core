<div class="space-y-6">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-4 rounded-xl shadow">
        <div>
            <label class="label">Nama</label>
            <input type="text" wire:model.defer="name" class="input input-bordered w-full" />
            @error('name') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">Kode</label>
            <input type="text" wire:model.defer="code" class="input input-bordered w-full" />
            @error('code') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2">
            <label class="label">Divisi Induk (opsional)</label>
            <select wire:model.defer="parent_div_id" class="select select-bordered w-full">
                <option value="">— Tanpa Induk —</option>
                @foreach($divisions as $d)
                    <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->code }})</option>
                @endforeach
            </select>
            @error('parent_div_id') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2 flex justify-end gap-2">
            @if ($editingId)
                <button type="button" class="btn" wire:click="cancelEdit">Batal</button>
            @endif
            <button class="btn btn-primary">{{ $editingId ? 'Perbarui' : 'Simpan' }}</button>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow p-4">
        <h2 class="font-semibold mb-2">Semua Divisi/Tim</h2>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kode</th>
                        <th>Induk</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($divisions as $d)
                        <tr>
                            <td>{{ $d->name }}</td>
                            <td>{{ $d->code }}</td>
                            <td>
                                {{ optional($divisions->firstWhere('id', $d->parent_div_id))->name ?? '—' }}
                            </td>
                            <td class="text-right">
                                <button class="btn btn-xs" wire:click="edit({{ $d->id }})">Ubah</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
