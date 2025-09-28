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
        <div>
            <label class="label">Tipe</label>
            <select wire:model.defer="type" class="select select-bordered w-full">
                <option value="province">Provinsi</option>
                <option value="city">Kota</option>
                <option value="district">Kecamatan</option>
                <option value="village">Kelurahan/Desa</option>
            </select>
            @error('type') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">Kode Pos</label>
            <input type="text" wire:model.defer="postal_code" class="input input-bordered w-full" />
            @error('postal_code') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2 flex justify-end gap-2">
            @if ($editingId)
                <button type="button" class="btn" wire:click="cancelEdit">Batal</button>
            @endif
            <button class="btn btn-primary">{{ $editingId ? 'Perbarui' : 'Simpan' }}</button>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow p-4">
        <h2 class="font-semibold mb-2">Daftar Wilayah</h2>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kode</th>
                        <th>Tipe</th>
                        <th>Kode Pos</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                        <tr>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->code }}</td>
                            <td>{{ ucfirst($row->type) }}</td>
                            <td>{{ $row->postal_code }}</td>
                            <td class="text-right">
                                <button class="btn btn-xs" wire:click="edit({{ $row->id }})">Ubah</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-2">{{ $rows->links() }}</div>
    </div>
</div>
