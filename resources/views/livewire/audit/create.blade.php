<div class="card bg-white w-full border border-base-200 rounded-2xl">
    <div class="p-6 border-b border-base-200">
        <div class="card-title">
            Input Laporan Hasil Pemeriksaan
        </div>
        <span class="text-sm font-light">Upload Data Input & Laporan Audit</span>
    </div>
    <div class="card-body">
        
        {{-- Success Message --}}
        @if (session('message'))
            <div 
                x-data="{ show: true }" 
                x-show="show" 
                x-init="setTimeout(() => show = false, 3000)" 
                class="toast toast-end"
            >
                <div class="alert alert-success flex items-center gap-2 transition duration-500">
                    <i class="fas fa-check"></i>
                    <span>{{ session('message') }}</span>
                </div>
            </div>
        @endif
        
        {{-- Error Message --}}
        @if (session('error'))
            <div class="toast toast-end">
                <div class="alert alert-error flex items-center gap-2">
                    <i class="fas fa-times"></i><span>{{ session('message') }}</span>
                </div>
            </div>
        @endif
        
        <form wire:submit="create" class="grid grid-cols-2 items-center justify-center gap-4">
            <fieldset class="fieldset">
                <legend class="fieldset-legend text-sm">Pilih OPD</legend>
                <select class="select select-bordered border-base-200 bg-base-100/40 w-full @error('selectedOpd') select-error @enderror" wire:model="selectedOpd">
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
                       class="input border border-base-200 w-full bg-base-100/40 @error('lhpNumber') input-error @enderror" 
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
                       class="input w-full border border-base-200 bg-base-100/40 @error('title') input-error @enderror" 
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
                <textarea class="textarea border border-base-200 bg-base-100/40 w-full bg @error('description') textarea-error @enderror" 
                          placeholder="Type here" 
                          wire:model="description"></textarea>
                @error('description')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
            </fieldset>

            <fieldset class="col-span-2" x-data="{ isDragging:false }">
                <legend class="fieldset-legend">Upload Dokumen LHP</legend>

                {{-- Dropzone --}}
                <div
                    class="p-4 rounded-lg border border-dashed transition 
                        border-base-300 hover:border-base-content/30 
                        bg-base-100/40"
                    :class="isDragging ? 'ring-2 ring-primary border-primary bg-primary/5' : ''"
                    x-on:dragover.prevent="isDragging = true"
                    x-on:dragleave.prevent="isDragging = false"
                    x-on:drop.prevent="
                        isDragging = false;
                        $refs.file.files = $event.dataTransfer.files;
                        $refs.file.dispatchEvent(new Event('change'));
                    "
                    x-on:click="$refs.file.click()"
                    role="button"
                    aria-label="Drop files here or click to browse"
                >
                    <div class="flex flex-col items-center justify-center gap-2 py-6 text-center">
                        <i class="fas fa-cloud-upload-alt text-3xl opacity-80"></i>
                        <div class="text-sm">
                            <span class="font-medium">Drag & drop</span> file ke sini, atau
                            <span class="link link-primary">browse</span>
                        </div>
                        <div class="text-xs text-base-content/60">
                            PDF, DOC, DOCX, JPG, JPEG, PNG
                        </div>
                    </div>

                    {{-- Hidden native input (triggered by click/drop) --}}
                    <input
                        type="file"
                        x-ref="file"
                        class="file-input file-input-bordered w-full hidden @error('files.*') file-input-error @enderror"
                        wire:model="files"
                        accept=".pdf,.doc,.docx"
                    />
                </div>

                {{-- Upload Progress --}}
                <div wire:loading wire:target="files" class="mt-2">
                    <div class="flex items-center gap-2">
                        <span class="loading loading-spinner loading-sm"></span>
                        <span class="text-sm">Uploading...</span>
                    </div>
                </div>

                {{-- Preview area --}}
                @if($files && count($files))
                    <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        @foreach($files as $idx => $file)
                            @php
                                $name = $file->getClientOriginalName();
                                $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                            @endphp

                            <div class="card bg-base-100 border border-base-200">
                                <div class="card-body p-3">
                                    {{-- Thumbnail / Icon --}}
                                    <div class="w-full aspect-video rounded-md overflow-hidden bg-base-200/60 flex items-center justify-center">
                                        @if($isImage && method_exists($file, 'temporaryUrl'))
                                            <img src="{{ $file->temporaryUrl() }}"
                                                alt="{{ $name }}"
                                                class="w-full h-full object-cover" />
                                        @elseif($ext === 'pdf')
                                            <i class="fas fa-file-pdf text-4xl"></i>
                                        @elseif(in_array($ext, ['doc','docx']))
                                            <i class="fas fa-file-word text-4xl"></i>
                                        @else
                                            <i class="fas fa-file text-4xl"></i>
                                        @endif
                                    </div>

                                    {{-- Filename --}}
                                    <div class="mt-2 text-xs line-clamp-2" title="{{ $name }}">{{ $name }}</div>

                                    {{-- Actions (optional remove) --}}
                                    <div class="mt-2">
                                        <button type="button"
                                                class="btn btn-xs btn-error text-white"
                                                wire:click="removeFile({{ $idx }})">
                                            <i class="fas fa-times mr-1"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

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
                            class="radio radio-primary size-5" 
                            value="1" 
                            wire:model="findings" />
                        <span>Ada Temuan</span>
                    </label>
                </div>
                <div>
                    <label class="flex items-center gap-2">
                        <input type="radio" 
                            name="findings" 
                            class="radio radio-primary size-5" 
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
            
            <div class="flex justify-end gap-4 col-span-2 mt-6">
                <button type="button" 
                        wire:click="resetForm" 
                        class="btn btn-outline w-full max-w-36 border border-base-300 text-neutral-500 hover:bg-base-100">
                    Reset Form
                </button>
                <button class="btn btn-primary w-full max-w-36" type="submit">
                    <span wire:loading wire:target="create" class="loading loading-spinner loading-sm"></span>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>