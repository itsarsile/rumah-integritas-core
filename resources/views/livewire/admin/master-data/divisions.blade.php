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
                        <th>Jumlah User</th>
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
                            <td>
                                <span class="badge badge-ghost">{{ $d->users_count }}</span>
                            </td>
                            <td class="text-right">
                                <button class="btn btn-xs" wire:click="edit({{ $d->id }})">Ubah</button>
                                <button class="btn btn-xs btn-outline ml-2" wire:click="openUsersModal({{ $d->id }})">Kelola User</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Kelola User Divisi --}}
    <div class="modal" x-data="{ open: @entangle('showUsersModal') }" :class="{ 'modal-open': open }" x-cloak>
        <div class="modal-box max-w-3xl">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold">Kelola Anggota Divisi</h3>
                    <p class="text-sm text-base-content/60">Pilih pengguna yang menjadi anggota divisi ini</p>
                </div>
                <button type="button" class="btn btn-sm btn-ghost" wire:click="closeUsersModal">Tutup</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto pr-2">
                @foreach($allUsers as $u)
                    <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-base-200">
                        <input type="checkbox" class="checkbox" value="{{ $u->id }}" wire:model="selectedUserIds">
                        <div>
                            <div class="font-medium">{{ $u->name }}</div>
                            <div class="text-xs text-base-content/60">{{ $u->email }}</div>
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="modal-action">
                <button class="btn btn-ghost" type="button" wire:click="closeUsersModal">Batal</button>
                <button class="btn btn-primary" type="button" wire:click="saveDivisionUsers">Simpan</button>
            </div>
        </div>
        <label class="modal-backdrop" wire:click="closeUsersModal">Close</label>
    </div>
</div>
