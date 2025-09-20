<!-- Single Root Container -->
<div class="h-[calc(100vh-5rem)] bg-base-100 flex flex-col rounded-2xl shadow">
    <!-- Mobile Header with Menu Toggle (visible only on mobile) -->
    <div class="navbar bg-base-100 shadow-sm lg:hidden">
        <div class="navbar-start">
            <button class="btn btn-ghost btn-circle" onclick="audit_sidebar.showModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
        <div class="navbar-center">
            <span class="text-lg font-semibold">
                {{ $currentAuditReport ? Str::limit($currentAuditReport->report_title, 25) : 'Audit Chat' }}
            </span>
        </div>
        <div class="navbar-end">
            <div class="flex items-center text-sm text-success">
                <div class="w-2 h-2 bg-success rounded-full mr-2"></div>
                {{ count($onlineUsers) }}
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="flex flex-1 min-h-0">
        <!-- Desktop Sidebar - Always visible on large screens -->
        <div class="hidden lg:flex lg:w-1/3 lg:min-w-96 bg-base-100 border-r border-base-300 flex-col rounded-l-2xl">
            <!-- Desktop Header -->
            <div class="p-4 border-b border-base-300 bg-base-200">
                <h2 class="text-lg font-semibold">Audit Reports</h2>
                <p class="text-sm text-base-content/70">Select a report to view chat</p>
            </div>

            <!-- Audit Reports List -->
            <div class="flex-1 overflow-y-auto">
                <div class="divide-y divide-base-300">
                    @foreach ($auditReports as $report)
                        <div wire:click="selectAudit({{ $report['id'] }})" 
                             class="p-4 cursor-pointer transition-all duration-200 hover:bg-base-200
                                    {{ $currentAuditId == $report['id'] ? 'bg-primary/10 border-r-4 border-primary' : '' }}">
                            
                            <!-- Status Badge -->
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-medium text-base-content text-sm line-clamp-2 flex-1 pr-2">
                                    {{ $report['report_title'] }}
                                </h3>
                                @php
                                    $status = $report['status'] ?? 'pending';
                                    $badgeClass = match($status) {
                                        'accepted', 'approved' => 'badge-success',
                                        'rejected' => 'badge-error',
                                        default => 'badge-warning'
                                    };
                                    $statusText = match($status) {
                                        'accepted', 'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        default => 'Menunggu',
                                    };
                                @endphp
                                <div class="badge {{ $badgeClass }} badge-sm">{{ $statusText }}</div>
                            </div>

                            <!-- Date -->
                            <p class="text-xs text-base-content/60 mb-3">
                                {{ \Carbon\Carbon::parse($report['created_at'])->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }}
                            </p>

                            <!-- Participants Info -->
                            <div class="flex justify-between text-xs gap-4">
                                <div class="flex flex-col flex-1">
                                    <span class="text-base-content/60">Pengirim</span>
                                    <span class="font-medium text-base-content">{{ $report['creator']['name'] ?? 'Unknown' }}</span>
                                </div>
                                <div class="flex flex-col flex-1 text-right">
                                    <span class="text-base-content/60">Penerima</span>
                                    <span class="font-medium text-base-content">
                                        {{ Str::limit($report['regional_government_organization']['name'] ?? 'Unknown', 20) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Chat Status Indicator -->
                            @if(isset($report['chat_room']))
                                <div class="flex items-center mt-2 text-xs text-success">
                                    <div class="w-2 h-2 bg-success rounded-full mr-2"></div>
                                    Chat Active
                                </div>
                            @else
                                <div class="flex items-center mt-2 text-xs text-base-content/40">
                                    <div class="w-2 h-2 bg-base-content/20 rounded-full mr-2"></div>
                                    No Chat Yet
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Chat Interface -->
        <div class="flex-1 flex flex-col min-w-0 bg-base-100 rounded-r-2xl">
            @if($currentAuditReport)
                <!-- Desktop Chat Header -->
                <div class="hidden lg:block bg-base-100 border-b border-base-300 shadow-sm">
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <h2 class="text-lg font-semibold text-base-content truncate">{{ $currentAuditReport->report_title }}</h2>
                                <div class="flex items-center space-x-4 text-sm text-base-content/70 mt-1">
                                    <span class="truncate">{{ $currentAuditReport->creator->name }}</span>
                                    <span>→</span>
                                    <span class="truncate">{{ $currentAuditReport->regionalGovernmentOrganization->name }}</span>
                                </div>
                            </div>
                            
                            <!-- Online Users Indicator -->
                            <div class="flex items-center text-sm text-success ml-4">
                                <div class="w-2 h-2 bg-success rounded-full mr-2"></div>
                                Online: {{ count($onlineUsers) }}
                            </div>
                        </div>
                        
                        <!-- Online Users List -->
                        @if(count($onlineUsers) > 0)
                            <div class="mt-3 p-3 bg-base-200 rounded-lg">
                                <h4 class="text-sm font-medium text-base-content/80 mb-2">Online Users:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($onlineUsers as $user)
                                        <div class="badge badge-success badge-outline">
                                            {{ $user['name'] }} ({{ $user['role'] }})
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sticky Attachment Header for current audit -->
                @if($currentAuditReport)
                <div class="sticky top-0 z-20 bg-base-100 px-4 pt-4 shadow-sm border-b border-base-300">
                    <div class="rounded-xl bg-base-100 p-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-base font-semibold leading-tight">{{ $currentAuditReport->report_title }}</div>
                                <div class="text-xs text-base-content/60 mt-1">
                                    {{ optional($currentAuditReport->created_at)->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }}
                                </div>
                            </div>
                            @php
                                $status = $currentAuditReport->status ?? 'pending';
                                $badge = ($status === 'accepted' || $status === 'approved') ? 'badge-success' : ($status === 'rejected' ? 'badge-error' : 'badge-warning');
                                $text = ($status === 'accepted' || $status === 'approved') ? 'Disetujui' : ($status === 'rejected' ? 'Ditolak' : 'Menunggu');
                            @endphp
                            <div class="badge {{ $badge }} badge-sm">{{ $text }}</div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-3 text-sm">
                            <div>
                                <div class="text-base-content/60">Pengirim</div>
                                <div class="font-medium">{{ $currentAuditReport->creator->name ?? '-' }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-base-content/60">Penerima</div>
                                <div class="font-medium">{{ $currentAuditReport->regionalGovernmentOrganization->name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="mt-3">
                            @if($currentAuditReport->hasLhpDocument())
                                <a href="{{ $currentAuditReport->getFileUrl() }}" target="_blank" class="btn btn-sm w-full gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    Lihat File Lampiran
                                </a>
                            @else
                                <button class="btn btn-sm w-full" disabled>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    Lampiran tidak tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Messages Area -->
                <div id="messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-base-100 scroll-smooth">
                    @forelse($messages as $msg)
                        <div class="flex {{ $msg['user_id'] == auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[85%] sm:max-w-xs lg:max-w-md">
                                <!-- Message Bubble -->
                                <div class="chat {{ $msg['user_id'] == auth()->id() ? 'chat-end' : 'chat-start' }}">
                                    <div class="chat-bubble {{ $msg['user_id'] == auth()->id() ? 'chat-bubble-primary' : 'chat-bubble-neutral' }} rounded-2xl">
                                        <div class="flex flex-col gap-2">
                                            <!-- Text Content (if exists) -->
                                            @if($msg['content'])
                                                <p class="text-sm whitespace-pre-wrap break-words">{{ $msg['content'] }}</p>
                                            @endif
                                            
                                            @if(array_key_exists('file_path', $msg) && $msg['file_path'] && 
                                                array_key_exists('file_name', $msg) && $msg['file_name'] && 
                                                array_key_exists('file_size', $msg) && $msg['file_size'])
                                                <div class="flex flex-col gap-2 {{ $msg['content'] ? 'border-t border-base-content/20 pt-2' : '' }}">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        <span class="text-sm font-medium">{{ $msg['file_name'] }}</span>
                                                    </div>
                                                    <div class="text-xs text-primary-content/70">
                                                        {{ $this->formatFileSize($msg['file_size']) }}
                                                    </div>
                                                    <a href="{{ route('dashboard.audit.chat.download', ['id' => $msg['id']]) }}" 
                                                    class="btn btn-xs btn-outline mt-1">
                                                        Download
                                                    </a>
                                                </div>
                                            @endif
                                            
                                            <!-- Empty state (if neither content nor file) -->
                                            @if(!$msg['content'] && !$msg['file_path'])
                                                <p class="text-sm text-base-content/50 italic">Message content unavailable</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="chat-footer opacity-50 text-xs mt-1">
                                        {{ $msg['user']['name'] }} • {{ \Carbon\Carbon::parse($msg['created_at'])->timezone('Asia/Jakarta')->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center text-base-content/60">
                                <div class="text-6xl mb-4">💬</div>
                                <p class="text-lg font-medium mb-2">No messages yet</p>
                                <p class="text-sm">Start the conversation!</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Mobile Online Users (visible only on small screens) -->
                <div class="lg:hidden bg-base-100 border-t border-base-300 p-3">
                    @if(count($onlineUsers) > 0)
                        <div class="text-center">
                            <div class="flex flex-wrap gap-1 justify-center">
                                @foreach($onlineUsers as $user)
                                    <div class="badge badge-success badge-sm">
                                        {{ Str::limit($user['name'], 12) }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- File Upload Preview -->
                @if($uploadedFile)
                <div class="bg-base-100 border-t border-base-300 p-3">
                    <div class="flex items-center justify-between p-2 bg-info/10 rounded-lg">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium">{{ $uploadedFile->getClientOriginalName() }}</p>
                                <p class="text-xs text-base-content/70">{{ $this->formatFileSize($uploadedFile->getSize()) }}</p>
                            </div>
                        </div>
                        <button wire:click="removeUploadedFile" class="btn btn-xs btn-ghost">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                @endif

                <!-- Message Input -->
                <div class="bg-base-100 border-t border-base-300 p-4 safe-area-bottom">
                    <form wire:submit.prevent="sendMessage" class="flex gap-2">
                        <!-- File Upload Button -->
                        <label for="file-upload" class="btn btn-ghost btn-circle cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            <input id="file-upload" type="file" wire:model="fileUpload" class="hidden">
                        </label>
                        
                        <!-- Message Input -->
                        <div class="flex-1">
                            <input 
                                wire:model="newMessage" 
                                type="text" 
                                placeholder="Type your message..." 
                                class="input input-bordered w-full focus:input-primary"
                                autocomplete="off"
                                maxlength="1000"
                            >
                        </div>
                        
                        <!-- Send Button -->
                        <button 
                            type="submit" 
                            class="btn btn-primary"
                            wire:loading.attr="disabled"
                            wire:target="sendMessage">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <span class="hidden sm:inline" wire:loading.remove wire:target="sendMessage">Send</span>
                            <span class="hidden sm:inline" wire:loading wire:target="sendMessage">Sending...</span>
                            <span class="loading loading-spinner loading-sm sm:hidden" wire:loading wire:target="sendMessage"></span>
                        </button>
                    </form>
                    
                    <!-- Upload Progress Bar -->
                    @if($uploadProgress > 0 && $uploadProgress < 100)
                    <div class="mt-2">
                        <progress class="progress progress-primary w-full" value="{{ $uploadProgress }}" max="100"></progress>
                        <p class="text-xs text-center text-base-content/70 mt-1">Uploading... {{ $uploadProgress }}%</p>
                    </div>
                    @endif
                    
                    <!-- Error Message -->
                    @if($uploadError)
                    <div class="mt-2 p-2 bg-error/10 text-error text-sm rounded-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $uploadError }}
                    </div>
                    @endif
                </div>
            @else
                <!-- Empty State -->
                <div class="flex-1 flex items-center justify-center p-4">
                    <div class="text-center text-base-content/60 max-w-md mx-auto">
                        <div class="text-6xl lg:text-8xl mb-6">💬</div>
                        <h3 class="text-xl lg:text-2xl font-bold mb-3">Select an Audit Report</h3>
                        <p class="text-base lg:text-lg mb-6">
                            <span class="lg:hidden">Tap the menu button above to choose an audit report and start chatting</span>
                            <span class="hidden lg:block">Choose an audit report from the sidebar to start or continue the conversation</span>
                        </p>
                        <button class="btn btn-primary lg:hidden" onclick="audit_sidebar.showModal()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            Browse Reports
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Mobile Sidebar Modal -->
    <dialog id="audit_sidebar" class="modal lg:hidden">
        <div class="modal-box w-11/12 max-w-md h-5/6 p-0">
            <div class="flex justify-between items-center p-4 border-b border-base-300 bg-base-200">
                <h3 class="font-bold text-lg">Audit Reports</h3>
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost">✕</button>
                </form>
            </div>
            
            <div class="overflow-y-auto flex-1">
                <div class="divide-y divide-base-300">
                    @foreach ($auditReports as $report)
                        <div wire:click="selectAudit({{ $report['id'] }})" 
                             onclick="audit_sidebar.close()"
                             class="p-4 cursor-pointer transition-all duration-200 hover:bg-base-200 min-h-[80px]
                                    {{ $currentAuditId == $report['id'] ? 'bg-primary/10' : '' }}">
                            
                            <!-- Status Badge -->
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-medium text-base-content text-sm line-clamp-2 flex-1 pr-2">
                                    {{ $report['report_title'] }}
                                </h3>
                                @php
                                    $status = $report['status'] ?? 'pending';
                                    $badgeClass = match($status) {
                                        'accepted', 'approved' => 'badge-success',
                                        'rejected' => 'badge-error',
                                        default => 'badge-warning'
                                    };
                                    $statusText = match($status) {
                                        'accepted', 'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        default => 'Menunggu',
                                    };
                                @endphp
                                <div class="badge {{ $badgeClass }} badge-sm">{{ $statusText }}</div>
                            </div>

                            <!-- Date -->
                            <p class="text-xs text-base-content/60 mb-3">
                                {{ \Carbon\Carbon::parse($report['created_at'])->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }}
                            </p>

                            <!-- Participants Info -->
                            <div class="flex justify-between text-xs gap-2">
                                <div class="flex flex-col flex-1">
                                    <span class="text-base-content/60">Pengirim</span>
                                    <span class="font-medium text-base-content text-xs">{{ Str::limit($report['creator']['name'] ?? 'Unknown', 15) }}</span>
                                </div>
                                <div class="flex flex-col flex-1 text-right">
                                    <span class="text-base-content/60">Penerima</span>
                                    <span class="font-medium text-base-content text-xs">
                                        {{ Str::limit($report['regional_government_organization']['name'] ?? 'Unknown', 15) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Chat Status Indicator -->
                            @if(isset($report['chat_room']))
                                <div class="flex items-center mt-2 text-xs text-success">
                                    <div class="w-2 h-2 bg-success rounded-full mr-2"></div>
                                    Chat Active
                                </div>
                            @else
                                <div class="flex items-center mt-2 text-xs text-base-content/40">
                                    <div class="w-2 h-2 bg-base-content/20 rounded-full mr-2"></div>
                                    No Chat Yet
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</div>

<!-- Enhanced JavaScript for real-time messaging and file handling -->
@script
<script>
    document.addEventListener('livewire:initialized', () => {
        // Get messages container (keeping original ID for compatibility)
        const el = $wire.$el.querySelector('#messages');
        
        // Scroll to bottom function
        const scrollBottom = () => { 
            if (el) {
                el.scrollTo({
                    top: el.scrollHeight,
                    behavior: 'smooth'
                });
            }
        };
        
        // Initial scroll to bottom
        scrollBottom();
        
        // Listen for scroll events from Livewire
        $wire.on('scrollToBottom', () => scrollBottom());

        // Auto-scroll when new messages arrive (Laravel Echo integration)
        const observer = new MutationObserver(() => {
            scrollBottom();
        });

        if (el) {
            observer.observe(el, {
                childList: true,
                subtree: true
            });
        }

        // Handle mobile keyboard appearance
        if (window.visualViewport) {
            window.visualViewport.addEventListener('resize', () => {
                if (window.visualViewport.height < window.screen.height * 0.75) {
                    // Keyboard is likely open, scroll to bottom after delay
                    setTimeout(() => {
                        scrollBottom();
                    }, 300);
                }
            });
        }

        // Focus input when selecting audit on mobile
        document.addEventListener('click', (e) => {
            if (e.target.closest('[wire\\:click*="selectAudit"]') && window.innerWidth < 1024) {
                setTimeout(() => {
                    const input = document.querySelector('input[wire\\:model="newMessage"]');
                    if (input) {
                        input.focus();
                    }
                }, 500);
            }
        });

        // Cleanup observer on component destruction
        window.addEventListener('beforeunload', () => {
            if (observer) {
                observer.disconnect();
            }
        });
    });

    // Handle Enter key for sending messages
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.matches('input[wire\\:model="newMessage"]') && !e.shiftKey) {
            e.preventDefault();
            const form = e.target.closest('form');
            if (form && (e.target.value.trim() || $wire.uploadedFile)) {
                form.dispatchEvent(new Event('submit', { bubbles: true }));
            }
        }
    });

    // Prevent zoom on input focus (iOS)
    document.addEventListener('touchstart', function(e) {
        if (e.target.matches('input[type="text"]')) {
            e.target.style.fontSize = '16px';
        }
    });
</script>
@endscript

@assets
<style>
    /* Line clamp utility for older browsers */
    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        line-clamp: 2;
    }

    /* Safe area handling */
    .safe-area-bottom {
        padding-bottom: max(1rem, env(safe-area-inset-bottom));
    }

    /* Smooth scrolling for messages */
    .scroll-smooth {
        scroll-behavior: smooth;
    }

    /* Better mobile touch targets */
    @media (max-width: 1023px) {
        .min-h-[80px] {
            min-height: 80px;
        }
        
        /* Ensure input doesn't zoom on iOS */
        input[type="text"] {
            font-size: 16px !important;
        }
    }

    /* Mobile viewport handling */
    @supports (height: 100dvh) {
        .h-screen {
            height: 100dvh;
        }
    }

    /* Custom scrollbar for webkit browsers */
    .overflow-y-auto::-webkit-scrollbar {
        width: 4px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: transparent;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: oklch(var(--bc) / 0.2);
        border-radius: 2px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: oklch(var(--bc) / 0.3);
    }

    /* Hide scrollbar on mobile */
    @media (max-width: 1023px) {
        .overflow-y-auto {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        
        .overflow-y-auto::-webkit-scrollbar {
            display: none;
        }
    }
</style>
@endassets
