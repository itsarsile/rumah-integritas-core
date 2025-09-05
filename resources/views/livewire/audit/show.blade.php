<div>
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-error mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="card bg-white">
        <div class="card-body">
            <h2 class="card-title">Sistem Audit & Pengawasan</h2>
            <div class="divider"></div>
            <table class="table">
                <tr>
                    <td class="w-1/3">Judul LHP</td>
                    <td class="w-1/2">{{ $audit->report_title }}</td>
                </tr>
                <tr>
                    <td class="w-1/2">Tanggal Laporan</td>
                    <td class="w-1/2">{{ $audit->lhp_number }}</td>
                </tr>
                <tr>
                    <td class="w-1/2">Pengaju</td>
                    <td class="w-1/2">{{ $audit->creator->name }}</td>
                </tr>
                <tr>
                    <td>OPD Penerima</td>
                    <td>{{ $audit->regionalGovernmentOrganization->name }}</td>
                </tr>
                <tr>
                    <td>Tanggal Pengajuan</td>
                    <td>{{ $audit->created_at->locale('id')->translatedFormat('j F Y') }}</td>
                </tr>
                <tr>
                    <td>Deskripsi</td>
                    <td class="max-w-xs">{{ $audit->report_description }}</td>
                </tr>
                <tr>
                    <td>File</td>
                    <td>
                        @if ($audit->hasLhpDocument())
                            <div class="flex items-center gap-3">
                                <div>
                                    <a href="{{ route('audit.files', $audit->id) }}" target="_blank" class="link link-primary">
                                        {{ $audit->lhp_document_name }}
                                    </a>
                                    <span class="text-sm text-gray-500 ml-2">
                                        ({{ number_format($audit->lhp_document_size / 1024, 1) }} KB)
                                    </span>
                                </div>
                                <div class="flex gap-2">
                                    <button wire:click="showUploadForm" class="btn btn-sm btn-outline btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        Ganti File
                                    </button>
                                    <button wire:click="deleteFile" 
                                            wire:confirm="Apakah Anda yakin ingin menghapus file ini?"
                                            class="btn btn-sm btn-outline btn-error">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-3">
                                <span class="text-gray-500">No file uploaded</span>
                                <button wire:click="showUploadForm" class="btn btn-sm btn-outline btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    Upload File
                                </button>
                            </div>
                        @endif

                        <!-- File Upload Form -->
                        @if ($showFileUpload)
                            <div class="mt-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                                <h3 class="text-lg font-medium mb-3">Upload File Baru</h3>
                                <div class="space-y-3">
                                    <div>
                                        <input type="file" 
                                               wire:model="newFile" 
                                               class="file-input file-input-bordered w-full"
                                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                                        @error('newFile')
                                            <div class="text-error text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div wire:loading wire:target="newFile" class="text-sm text-gray-500">
                                        Mengupload file...
                                    </div>

                                    @if ($newFile)
                                        <div class="text-sm text-gray-600">
                                            File terpilih: {{ $newFile->getClientOriginalName() }}
                                            ({{ number_format($newFile->getSize() / 1024, 1) }} KB)
                                        </div>
                                    @endif

                                    <div class="flex gap-2">
                                        <button wire:click="uploadNewFile" 
                                                wire:loading.attr="disabled"
                                                wire:target="uploadNewFile"
                                                class="btn btn-sm btn-primary">
                                            <span wire:loading.remove wire:target="uploadNewFile">Upload</span>
                                            <span wire:loading wire:target="uploadNewFile" class="loading loading-spinner loading-sm"></span>
                                        </button>
                                        <button wire:click="cancelUpload" class="btn btn-sm btn-ghost">
                                            Batal
                                        </button>
                                    </div>

                                    <div class="text-xs text-gray-500">
                                        Format yang didukung: PDF, DOC, DOCX, XLS, XLSX. Maksimal 10MB.
                                    </div>
                                </div>
                            </div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>