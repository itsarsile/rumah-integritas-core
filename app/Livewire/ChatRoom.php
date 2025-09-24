<?php
namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\AuditReports;
use App\Models\ChatMessage;
use App\Models\ChatRoom as ChatRoomModel;
use App\Models\ChatRoomParticipant;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class ChatRoom extends Component
{
    use WithFileUploads;
    public $chatRoomId;
    public $currentAuditId;
    public $auditReports = [];
    public $messages = [];
    public $newMessage = '';
    public $onlineUsers = [];
    public $currentAuditReport;

    public $fileUpload;
    public $uploadedFile = null;
    public $uploadProgress = 0;
    public $uploadError = null;


    public function mount($id = null)
    {
        // Load all audit reports for the sidebar
        $this->auditReports = AuditReports::with(['creator', 'regionalGovernmentOrganization', 'chatRoom'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();

        // Load the specific audit report
        $this->loadAuditChat($id);
    }

    public function loadAuditChat($id)
    {
        $auditReport = AuditReports::findOrFail($id);
        $this->currentAuditId = $id;
        $this->currentAuditReport = $auditReport;

        $chatRoom = $auditReport->chatRoom;

        if (!$chatRoom) {
            $chatRoom = ChatRoomModel::create([
                'audit_report_id' => $auditReport->id,
                'room_code' => 'ROOM-' . strtoupper(uniqid()),
                'title' => $auditReport->report_title,
                'description' => $auditReport->report_description,
                'status' => 'active',
                'created_by' => auth()->id(),
            ]);
        }

        if (auth()->check()) {
            $roleName = auth()->user()->hasRole('admin') ? 'admin' : 'member';
            ChatRoomParticipant::updateOrCreate(
                [
                    'chat_room_id' => $chatRoom->id,
                    'user_id' => auth()->id(),
                ],
                [
                    'role' => $roleName,
                    'joined_at' => now(),  // For existing records, this will update joined_at (harmless, or use a conditional below if you want to preserve original)
                ]
            );
        }

        $this->chatRoomId = $chatRoom->id;
        $this->messages = ChatMessage::where('chat_room_id', $this->chatRoomId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->take(50)
            ->get()
            ->toArray();


        ChatRoomParticipant::where('chat_room_id', $this->chatRoomId)
            ->where('user_id', auth()->id())
            ->update(['last_seen_at' => now()]);

        // Update the URL without page reload
        $this->dispatch('updateUrl', route('dashboard.audit.chat', ['id' => $id]));
    }

    public function updatedFileUpload()
    {
        $this->uploadError = null;
        $this->uploadProgress = 0;

        $this->validate([
            'fileUpload' => 'file|max:10240',
        ]);

        try {
            $this->uploadedFile = $this->fileUpload;
            $this->fileUpload = null;
        } catch (\Exception $e) {
            $this->uploadError = 'Failed to upload file: ' . $e->getMessage();
            $this->uploadedFile = null;
        }
    }

    public function removeUploadedFile()
    {
        $this->uploadedFile = null;
        $this->uploadError = null;
    }

    public function sendMessage()
    {
        if (empty($this->newMessage) && !$this->uploadedFile) {
            return;
        }

        // Handle file upload if present
        $fileData = null;
        if ($this->uploadedFile) {
            try {
                $this->uploadProgress = 30;

                // Store the file
                $filePath = $this->uploadedFile->store('chat_files/' . $this->chatRoomId, 'public');

                $this->uploadProgress = 70;

                $fileData = [
                    'file_name' => $this->uploadedFile->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_size' => $this->uploadedFile->getSize(),
                    'file_mime_type' => $this->uploadedFile->getMimeType(),
                ];

                $this->uploadProgress = 100;

            } catch (\Exception $e) {
                $this->uploadError = 'Failed to upload file: ' . $e->getMessage();
                $this->uploadedFile = null;
                $this->uploadProgress = 0;
                return;
            }
        }

        // Create the message
        $messageData = [
            'chat_room_id' => $this->chatRoomId,
            'user_id' => auth()->id(),
            'message_type' => $this->uploadedFile ? 'file' : 'text',
            'content' => $this->newMessage,
        ];

        if ($fileData) {
            $messageData = array_merge($messageData, $fileData);
        }

        $message = ChatMessage::create($messageData);
        broadcast(new MessageSent($message))->toOthers();

        // Load message with user relationship
        $messageWithUser = ChatMessage::with('user')->find($message->id);
        $this->messages[] = $messageWithUser->toArray();

        // Reset form
        $this->newMessage = '';
        $this->uploadedFile = null;
        $this->uploadProgress = 0;
        $this->uploadError = null;

        ChatRoomParticipant::where('chat_room_id', $this->chatRoomId)
            ->where('user_id', auth()->id())
            ->update(['last_read_message_id' => $message->id]);

        $this->dispatch('scrollToBottom');
    }

    public function formatFileSize($bytes)
    {
        if ($bytes == 0)
            return '0 B';

        $units = ['B', 'KB', 'MB', 'GB'];
        $base = log($bytes) / log(1024);
        $floor = floor($base);

        return round(pow(1024, $base - $floor), 2) . ' ' . $units[$floor];
    }

    public function selectAudit($auditId)
    {
        $this->loadAuditChat($auditId);
        $this->dispatch('scrollToBottom');
    }


    #[On('echo-presence:presence-chat.{chatRoomId},here')]
    public function updateOnlineUsersHere($users)
    {
        $this->onlineUsers = $users;
    }

    #[On('echo-presence:presence-chat.{chatRoomId},joining')]
    public function updateOnlineUsersJoining($user)
    {
        $this->onlineUsers[] = $user;
    }

    #[On('echo-presence:presence-chat.{chatRoomId},leaving')]
    public function updateOnlineUsersLeaving($user)
    {
        $this->onlineUsers = array_filter($this->onlineUsers, fn($u) => $u['id'] !== $user['id']);
    }

    #[On('echo-presence:presence-chat.{chatRoomId},.message.sent')]
    public function messageReceived($event)
    {
        $this->messages[] = $event;
        $this->dispatch('scrollToBottom');
    }

    #[Title("Percakapan")]
    public function render()
    {
        return view('livewire.chat-room');
    }
}
?>