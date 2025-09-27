<div>
    <!-- Flash Messages -->
    @if (session()->has('success'))
            <div 
                x-data="{ show: true }" 
                x-show="show" 
                x-init="setTimeout(() => show = false, 3000)" 
                class="toast toast-end"
            >
                <div class="alert alert-success flex items-center gap-2 transition duration-500">
                    <i class="fas fa-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
    @endif

    @if (session()->has('error'))
        <div class="toast toast-end">
            <div class="alert alert-error flex items-center gap-2">
                <i class="fas fa-times"></i><span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div class="card bg-white w-full border border-base-200 rounded-2xl">
        <div class="p-6 border-b border-base-200">
            <div class="card-title">
                Sistem Audit & Pengawasan
            </div>
            <span class="text-sm font-light">Upload Data Input & Laporan Audit</span>
        </div>        
        <div class="card-body">
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Judul LHP</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : <p> {{ $audit->report_title }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Nomor LHP</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : <p> {{ $audit->lhp_number }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Pengaju</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : <div class="flex items-center gap-3 border border-base-200 rounded-full p-0.5 w-fit pr-3">
                        @if ($audit->creator->avatar)
                        @php
                        $src = $audit->creator->avatar;
                        if (!\Illuminate\Support\Str::startsWith($src, ['http://', 'https://'])) {
                            $src = \Illuminate\Support\Facades\Storage::url($src);
                        }
                        @endphp
                            <div class="avatar">
                                <div class="size-5 rounded-full border border-base-200 overflow-hidden">
                                    <img src="{{ $src }}" alt="Avatar of {{ $audit->creator->name }}" />
                                </div>
                            </div>
                            @else
                                <div class="size-5 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-semibold">
                                    {{ strtoupper(substr($audit->creator->name, 0, 1)) }}
                                </div>
                            @endif
                        <span class="font-light">{{ $audit->creator->name }}</span>
                    </div>
                </div>
                
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">OPD Penerima</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : <p> {{ $audit->regionalGovernmentOrganization->name }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Tanggal Pengajuan</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : <p> {{ $audit->created_at->locale('id')->translatedFormat('j F Y') }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Deskripsi</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : <p> {{ $audit->report_description }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                @php
                    $statusLabel = match ($audit->status) {
                        'pending' => 'Menunggu',
                        'rejected' => 'Ditolak',
                        'accepted' => 'Disetujui',
                        default => 'Tidak diketahui',
                    };
                    $statusClass = match ($audit->status) {
                        'pending' => 'bg-yellow-100 border border-yellow-200 text-yellow-800',
                        'rejected' => 'bg-red-100 border border-red-200 text-red-800',
                        'accepted' => 'bg-green-100 border border-green-200 text-green-800',
                        default => 'bg-gray-100 border border-gray-200 text-gray-800',
                    };
                @endphp
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">Status</span></label>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    : <span class="{{ $statusClass }} text-xs font-light mr-2 px-2.5 py-0.5 rounded-full"> {{ $statusLabel }}</span>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label class="label"><span class="label-text font-medium">File</span></label>
                </div>
                <div class="col-span-2 flex gap-2 flex-col">
                    : 
                    @php
                        $hasFile   = $audit->hasLhpDocument();
                        $fileUrl   = route('audit.files', $audit->id);
                        $mime      = $audit->lhp_document_mime_type ?? null;
                        $isImage   = $mime && str_starts_with($mime, 'image/');
                        $isPdf     = $mime === 'application/pdf';
                        $isOffice  = in_array($mime, [
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ], true);
                    @endphp

                    @if ($hasFile)
                        <div class="flex flex-col gap-3">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <a href="{{ $fileUrl }}" target="_blank" class="link link-primary break-all">
                                        {{ $audit->lhp_document_name }}
                                    </a>
                                    <span class="text-sm text-gray-500 ml-2">
                                        ({{ number_format(($audit->lhp_document_size ?? 0) / 1024, 1) }} KB)
                                    </span>
                                    @if ($mime)
                                        <span class="badge badge-ghost ml-2 text-xs">{{ $mime }}</span>
                                    @endif
                                </div>
                                <div class="flex gap-2 shrink-0">
                                    <button wire:click="showUploadForm" class="btn btn-sm btn-outline btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        Ganti File
                                    </button>
                                    <button wire:click="deleteFile"
                                            class="btn btn-sm btn-outline btn-error hover:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4
                                                    a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>

                            <div class="rounded-lg border border-base-200 bg-base-100">
                                @if ($isImage)
                                    <div class="p-3">
                                        <img src="{{ $fileUrl }}" alt="{{ $audit->lhp_document_name }}"
                                            class="w-full max-h-96 object-contain rounded-md">
                                    </div>
                                @elseif ($isPdf)
                                    <div class="aspect-video">
                                        <iframe
                                            src="{{ $fileUrl }}"
                                            class="w-full h-full rounded-md"
                                            loading="lazy"
                                        ></iframe>
                                    </div>
                                    <div class="px-3 py-2 text-xs text-gray-500">
                                        Jika PDF tidak tampil, klik <a class="link" href="{{ $fileUrl }}" target="_blank">buka di tab baru</a>.
                                    </div>
                                @else
                                    <div class="p-4 flex items-center gap-3">
                                        {{-- Icon dokumen sederhana --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-neutral-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 3h6l5 5v11a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                        </svg>
                                        <div class="text-sm">
                                            Pratinjau tidak tersedia untuk file tipe ini.
                                            <a href="{{ $fileUrl }}" target="_blank" class="link link-primary ml-1">Download / Buka</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-gray-500">Belum ada dokumen.</span>
                            <button wire:click="showUploadForm" class="btn btn-sm btn-outline btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Upload File
                            </button>
                        </div>
                    @endif

                    {{-- Form Upload (drag & drop) --}}
                    @if ($showFileUpload == 1)
                        <div class="mt-4 p-4 border border-gray-200 rounded-lg bg-gray-50"
                            x-data="{drag:false}"
                            x-on:clear-file-input.window="$refs.uploader && ($refs.uploader.value='')"
                            x-on:dragover.prevent="drag=true"
                            x-on:dragleave.prevent="drag=false"
                            x-on:drop.prevent="
                                drag=false;
                                $refs.uploader.files = $event.dataTransfer.files;
                                $refs.uploader.dispatchEvent(new Event('change'));
                            ">
                            <h3 class="text-lg font-medium mb-3">Upload File Baru</h3>

                            <div class="rounded-md border border-dashed p-6 text-center cursor-pointer transition
                                        hover:border-base-300"
                                :class="drag ? 'ring-2 ring-primary border-primary bg-primary/5' : ''"
                                x-on:click="$refs.uploader.click()">
                                <div class="flex flex-col items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 opacity-80" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <div class="text-sm">
                                        <span class="font-medium">Drag & drop</span> file ke sini, atau
                                        <span class="link link-primary">browse</span>
                                    </div>
                                    <div class="text-xs text-base-content/60">
                                        PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG (maks 10MB)
                                    </div>
                                </div>
                                <input type="file"
                                    x-ref="uploader"
                                    class="hidden"
                                    wire:model="newFile"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" />
                            </div>

                            <div wire:loading wire:target="newFile" class="mt-2 text-sm text-gray-500">
                                Mengupload file...
                            </div>

                            @if ($newFile)
                                @php
                                    $tempName = $newFile->getClientOriginalName();
                                    $tempSize = number_format($newFile->getSize() / 1024, 1) . ' KB';
                                    $tempMime = $newFile->getMimeType();
                                    $tempIsImage = str_starts_with($tempMime ?? '', 'image/');
                                @endphp
                                <div class="mt-3 rounded-lg border border-base-200 bg-base-100">
                                    <div class="p-3 flex items-start gap-3">
                                        <div class="w-32">
                                            @if ($tempIsImage && method_exists($newFile, 'temporaryUrl'))
                                                <img src="{{ $newFile->temporaryUrl() }}"
                                                    alt="{{ $tempName }}"
                                                    class="w-full h-20 object-cover rounded-md">
                                            @else
                                                <div class="w-full h-20 rounded-md bg-base-200 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-neutral-500"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M7 3h6l5 5v11a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-medium break-all">{{ $tempName }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ $tempSize }} • {{ $tempMime }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-3 flex gap-2">
                                <button wire:click="uploadNewFile"
                                        wire:loading.attr="disabled"
                                        wire:target="uploadNewFile"
                                        class="btn btn-sm btn-primary">
                                    <span wire:loading.remove wire:target="uploadNewFile">Upload</span>
                                    <span wire:loading wire:target="uploadNewFile" class="loading loading-spinner loading-sm"></span>
                                </button>
                                <button wire:click="cancelUpload" class="btn btn-sm">Batal</button>
                            </div>
                        </div>                        
                    @endif

                </div>
            </div>
        </div>
        
    </div>
</div>