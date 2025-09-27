<div class="card bg-white w-full border border-base-200 rounded-2xl">
    <div class="p-6 border-b border-base-200">
        <div class="card-title">
            Manajemen Pemeliharaan
        </div>
        <span class="text-sm font-light">Form permintaan pemeliharaan peralatan</span>
    </div>    
    <div class="card-body space-y-4">

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

        <form wire:submit.prevent="submit" class="space-y-4">
            <!-- Jenis Peralatan -->
            <div class="form-control space-y-2">
                <label class="label">
                    <span class="label-text font-medium">Jenis Peralatan</span>
                </label>
                <select class="select select-bordered w-full border border-base-200 bg-base-100/40" wire:model="assetId">
                    <option value="">Pilih Peralatan</option>
                    @foreach ($assets as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Deskripsi -->
            <div class="form-control space-y-2">
                <label class="label">
                    <span class="label-text font-medium">Deskripsi Masalah</span>
                </label>
                <textarea class="textarea textarea-bordered w-full border border-base-200 bg-base-100/40" wire:model="description" rows="4"
                    placeholder="Jelaskan masalah peralatan"></textarea>
            </div>

            <!-- File -->
            {{-- <div class="form-control space-y-2">
                <label class="label">
                    <span class="label-text">Upload Bukti (Optional)</span>
                </label>
                <div class="border p-3 border-base-content/20 rounded-xl">
                    <input type="file" multiple accept="pdf,docx,doc,xlsx,xls,ppt,pptx" class="file-input file-input-ghost w-full" wire:model="files" multiple />   
                </div>
            </div> --}}

            <fieldset class="col-span-2 space-y-2" x-data="{ isDragging:false }">
                <label class="label label-text font-medium">Upload Bukti (Optional)</label>

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
                        multiple
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
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

            <div class="grid lg:grid-cols-2 gap-4 grid-cols-1">
                <!-- Tingkat Prioritas -->
                <div class="form-control space-y-2">
                    <label class="label">
                        <span class="label-text font-medium">Tingkat Prioritas</span>
                    </label>
                    <select class="select select-bordered w-full border border-base-200 bg-base-100/40" wire:model="priority">
                        <option value="">Pilih Prioritas</option>
                        <option value="low">Rendah</option>
                        <option value="medium">Normal</option>
                        <option value="high">Tinggi</option>
                    </select>
                </div>

                <!-- Divisi -->
                <div class="form-control space-y-2">
                    <label class="label">
                        <span class="label-text font-medium">Divisi</span>
                    </label>
                    <select class="select select-bordered w-full border border-base-200 bg-base-100/40" wire:model="divisionId">
                        <option value="">Pilih Divisi</option>
                        @foreach ($divisions as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <!-- Action Buttons -->
            <div class="flex gap-2 justify-end pt-2">
                <button type="reset" class="btn btn-outline border border-base-300 text-neutral-500 w-full max-w-36 hover:bg-base-100">Reset Form</button>
                <button type="submit" class="btn btn-primary w-full md:w-fit">Ajukan Pemeliharaan</button>
            </div>
        </form>
    </div>
</div>