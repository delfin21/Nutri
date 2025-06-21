<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Get all messages for a conversation
  public function index($conversationId)
{
    \Log::info('Loading messages for conversation ID: ' . $conversationId);

    $user = Auth::user();
    \Log::info('Authenticated user ID: ' . $user->id);

    $conversation = Conversation::where('id', $conversationId)
        ->where(function ($query) use ($user) {
            $query->where('buyer_id', $user->id)
                  ->orWhere('farmer_id', $user->id);
        })
        ->firstOrFail();

    \Log::info('Found conversation. Fetching messages...');

    $messages = Message::where('conversation_id', $conversation->id)
        ->orderBy('created_at', 'asc')
        ->get();

    \Log::info('Message count: ' . $messages->count());

    return response()->json($messages);
}
    // Send a new message in a conversation
    public function store(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $user = Auth::user();

        $conversation = Conversation::where('id', $conversationId)
            ->where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                      ->orWhere('farmer_id', $user->id);
            })->firstOrFail();

        $receiverId = ($user->id == $conversation->buyer_id) ? $conversation->farmer_id : $conversation->buyer_id;

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json($message, 201);
    }
}
