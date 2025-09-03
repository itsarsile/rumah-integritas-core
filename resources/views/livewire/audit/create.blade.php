<div class="card bg-base-100 w-full border border-base-content/20 rounded-xl">
    <div class="card-body">
        <div class="card-title">Input Laporan Hasil Pemeriksaan</div>
        <span>Upload Data Input & Laporan Audit</span>
        <div class="divider"></div>
        
        {{-- Success Message --}}
        @if (session('message'))
            <div class="alert alert-success mb-4">
                {{ session('message') }}
            </div>
        @endif
        
        {{-- Error Message --}}
        @if (session('error'))
            <div class="alert alert-error mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        <form wire:submit="create" class="grid grid-cols-2 items-center justify-center gap-4 mt-4">
            <fieldset class="fieldset">
                <legend class="fieldset-legend">Pilih OPD</legend>
                <select class="select select-bordered w-full @error('selectedOpd') select-error @enderror" wire:model="selectedOpd">
                    <option disabled selected>Pilih OPD</option>
                    @foreach ($opd as $item)
                        <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                    @endforeach
                </select>
                @error('selectedOpd')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
            </fieldset>
            
            <fieldset>
                <legend class="fieldset-legend">No. LHP</legend>
                <input type="text" 
                       class="input w-full @error('lhpNumber') input-error @enderror" 
                       placeholder="Type here" 
                       wire:model="lhpNumber"/>
                @error('lhpNumber')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
            </fieldset>
            
            <fieldset class="col-span-2">
                <legend class="fieldset-legend">Judul Laporan Hasil Pemeriksaan</legend>
                <input type="text" 
                       class="input w-full @error('title') input-error @enderror" 
                       placeholder="Type here" 
                       wire:model="title" />
                @error('title')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
            </fieldset>
            
            <fieldset class="col-span-2">
                <legend class="fieldset-legend">Deskripsi</legend>
                <textarea class="textarea w-full @error('description') textarea-error @enderror" 
                          placeholder="Type here" 
                          wire:model="description"></textarea>
                @error('description')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
            </fieldset>
            
            <fieldset class="col-span-2">
                <legend class="fieldset-legend">Upload Dokumen LHP</legend>
                <div class="p-4 border-[1px] border-base-content/20 rounded-lg">
                    <input type="file" 
                           class="file-input @error('files.*') file-input-error @enderror" 
                           wire:model="files" 
                           multiple
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
                    
                    {{-- Upload Progress --}}
                    <div wire:loading wire:target="files" class="mt-2">
                        <div class="flex items-center gap-2">
                            <span class="loading loading-spinner loading-sm"></span>
                            <span class="text-sm">Uploading...</span>
                        </div>
                    </div>
                    
                    {{-- Show selected files --}}
                    @if($files)
                        <div class="mt-2">
                            @foreach($files as $file)
                                <div class="badge badge-outline mr-2 mb-2">
                                    {{ $file->getClientOriginalName() }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                @error('files')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
            </fieldset>
            
            <fieldset class="col-span-2 space-y-4">
                <legend class="fieldset-legend">Status Temuan</legend>
                <div class="mt-4">
                    <label class="flex items-center gap-2">
                        <input type="radio" 
                               name="findings" 
                               class="radio" 
                               value="1" 
                               wire:model="findings" />
                        <span>Ada Temuan</span>
                    </label>
                </div>
                <div>
                    <label class="flex items-center gap-2">
                        <input type="radio" 
                               name="findings" 
                               class="radio" 
                               value="0" 
                               wire:model="findings"/>
                        <span>Tidak Ada Temuan</span>
                    </label>
                </div>
                @error('findings')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
            </fieldset>
            
            <div class="flex gap-4 col-span-2">
                <button class="btn btn-primary w-full max-w-xs" type="submit">
                    <span wire:loading wire:target="create" class="loading loading-spinner loading-sm"></span>
                    Simpan Data
                </button>
                <button type="button" 
                        wire:click="resetForm" 
                        class="btn btn-outline w-full max-w-xs">
                    Reset
                </button>
            </div>
        </form>
    </div>
</div>