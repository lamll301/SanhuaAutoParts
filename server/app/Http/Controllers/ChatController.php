<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageEvent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function getConversations(Request $request)
    {
        $user = $request->user();
        $conversations = Conversation::where(function($query) use ($user) {
            $query->where('customer_id', $user->id)
                  ->orWhere('staff_id', $user->id);
        })
        ->with(['messages' => function($query) {
            $query->latest()->limit(1);
        }])
        ->latest('last_message_at')
        ->get();

        return response()->json($conversations);
    }

    public function getMessages(Request $request, Conversation $conversation)
    {
        $messages = $conversation->messages()
            ->with('sender')
            ->latest()
            ->paginate(50);

        return response()->json($messages);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $request->validate([
            'content' => 'required|string',
            'image_url' => 'nullable|string'
        ]);

        $user = $request->user();
        $senderType = $user->hasRole('staff') ? 'staff' : 'customer';

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'sender_type' => $senderType,
            'content' => $request->content,
            'image_url' => $request->image_url
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'is_read_by_customer' => $senderType === 'customer',
            'is_read_by_staff' => $senderType === 'staff'
        ]);

        broadcast(new ChatMessageEvent($message))->toOthers();

        return response()->json($message->load('sender'));
    }
} 