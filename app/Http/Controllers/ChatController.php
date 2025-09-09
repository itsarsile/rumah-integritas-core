<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function downloadFile($id)
    {
        try {
            $file = ChatMessage::findOrFail($id);
            
            // Check if file exists in default disk (local)
            if (Storage::exists($file->file_path)) {
                return $this->returnFile(Storage::class, $file);
            }
            
            // Check if file exists in public disk
            if (Storage::disk('public')->exists($file->file_path)) {
                return $this->returnFile(Storage::disk('public'), $file);
            }
            
            // Log missing file for debugging
            \Log::warning('Missing chat file', [
                'id' => $id,
                'file_path' => $file->file_path,
                'file_name' => $file->file_name,
                'user_id' => auth()->id(),
                'created_at' => $file->created_at
            ]);
            
            return response()->json([
                'error' => 'File no longer available'
            ], 404);
            
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'File record not found'], 404);
        } catch (\Exception $e) {
            \Log::error('Chat file download error', [
                'error' => $e->getMessage(),
                'file_id' => $id,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Unable to download file'], 500);
        }
    }
    
    private function returnFile($storage, $file)
    {
        // Get proper filename and file info
        $fileName = $file->file_name ?? basename($file->file_path);
        
        try {
            $mimeType = $storage->mimeType($file->file_path);
            $fileSize = $storage->size($file->file_path);
        } catch (\Exception $e) {
            // Fallback if we can't get file info
            $mimeType = 'application/octet-stream';
            $fileSize = null;
        }
        
        $headers = [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'no-cache, must-revalidate',
            'Content-Disposition' => 'attachment; filename="' . addslashes($fileName) . '"'
        ];
        
        if ($fileSize) {
            $headers['Content-Length'] = $fileSize;
        }
        
        return $storage->response($file->file_path, $fileName, $headers);
    }
}
