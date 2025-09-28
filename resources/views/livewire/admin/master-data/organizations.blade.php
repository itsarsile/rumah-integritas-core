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
            <label class="label">Alamat</label>
            <textarea wire:model.defer="address" class="textarea textarea-bordered w-full"></textarea>
            @error('address') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">Telepon</label>
            <input type="text" wire:model.defer="phone" class="input input-bordered w-full" />
            @error('phone') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">Email</label>
            <input type="email" wire:model.defer="email" class="input input-bordered w-full" />
            @error('email') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">Situs Web</label>
            <input type="url" wire:model.defer="website" class="input input-bordered w-full" />
            @error('website') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">Status</label>
            <select wire:model.defer="status" class="select select-bordered w-full">
                <option value="active">Aktif</option>
                <option value="inactive">Tidak Aktif</option>
            </select>
            @error('status') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2">
            <label class="label">Wilayah</label>
            <select wire:model.defer="region_id" class="select select-bordered w-full">
                <option value="">— Pilih Wilayah —</option>
                @foreach($regions as $r)
                    <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->code }})</option>
                @endforeach
            </select>
            @error('region_id') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2 flex justify-end gap-2">
            @if ($editingId)
                <button type="button" class="btn" wire:click="cancelEdit">Batal</button>
            @endif
            <button class="btn btn-primary">{{ $editingId ? 'Perbarui' : 'Simpan' }}</button>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow p-4">
        <h2 class="font-semibold mb-2">Organisasi Perangkat Daerah</h2>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kode</th>
                        <th>Status</th>
                        <th>Wilayah</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->code }}</td>
                        <td>{{ $row->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}</td>
                        <td>{{ $row->regions->name ?? '-' }}</td>
                        <td class="text-right"><button class="btn btn-xs" wire:click="edit({{ $row->id }})">Ubah</button></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-2">{{ $rows->links() }}</div>
    </div>
</div>
