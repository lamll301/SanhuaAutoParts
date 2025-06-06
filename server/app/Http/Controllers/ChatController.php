<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function getConversationByCustomer(Request $request)
    {
        $userId = $request->user_id;
        $conversation = Conversation::with([
            'staff:id,name,avatar_id', 
            'staff.avatar:id,path',
            'customer:id,name,avatar_id',
            'customer.avatar:id,path'
        ])->firstOrCreate(['customer_id' => $userId]);
        
        return response()->json($conversation);
    }

    public function getMessages(Conversation $conversation)
    {
        $messages = $conversation->messages()
            ->select('id', 'content', 'sender_type', 'image_url', 'is_read', 'sender_id', 'created_at')
            ->with('image')
            ->latest()
            ->paginate(50);
        return response()->json($messages);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $userId = $request->user_id;
        $roleId = $request->role_id;
        $senderType = $roleId ? 'staff' : 'customer';
        $message = $conversation->messages()->create([
            'sender_id' => $userId,
            'sender_type' => $senderType,
            'content' => $request->content,
        ]);
        $image_url = null;
        if ($request->hasFile('image')) {
            $storagePath = "Chat/{$conversation->id}";
            $file = $request->file('image');
            $path = $file->store($storagePath, 'public');
            if ($path) {
                $image = $message->image()->create([
                    'path' => Storage::url($path),
                    'filename' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
                $image_url = $image->id;
            }
        }
        if ($image_url) {
            $message->update([
                'image_url' => $image_url
            ]);
        }
        broadcast(new MessageSent($message))->toOthers();
        $conversation->update([
            'last_message_at' => now(),
            'is_read_by_customer' => $senderType === 'customer',
            'is_read_by_staff' => $senderType === 'staff'
        ]);
        return response()->json($message);
    }

    public function markAsRead(Request $request, Conversation $conversation)
    {
        $roleId = $request->role_id;
        $senderType = $roleId ? 'staff' : 'customer';

        if ($senderType === 'customer') {
            $conversation->messages()
                ->where('sender_type', 'staff')
                ->where('is_read', false)
                ->update(['is_read' => true]);
                
            $conversation->update(['is_read_by_customer' => true]);
        } else {
            $conversation->messages()
                ->where('sender_type', 'customer')
                ->where('is_read', false)
                ->update(['is_read' => true]);
                
            $conversation->update(['is_read_by_staff' => true]);
        }

        return response()->json(['message' => 'success']);
    }

    public function index(Request $request)
    {
        $query = Conversation::with([
            'customer:id,name,username,email,phone,role_id',
        ])
        ->whereHas('customer', function ($query) {
            $query->whereNull('role_id');
        })
        ->orderBy('updated_at', 'desc');
        
        return $this->getListResponse($query, $request, [
            'customer.name', 'customer.username', 'customer.email'
        ], []);
    }

    public function show(string $id)
    {
        $conversation = Conversation::with([
            'customer:id,name,avatar_id',
            'customer.avatar:id,path',
            'staff:id,name,avatar_id',
            'staff.avatar:id,path'
        ])->findOrFail($id);
        return response()->json($conversation);
    }

    public function connect(Conversation $conversation, Request $request)
    {
        $userId = $request->user_id;
        $conversation->update(['staff_id' => $userId]);
        return response()->json(['message' => 'success']);
    }
} 