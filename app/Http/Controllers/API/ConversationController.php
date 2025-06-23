<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    /**
     * List all conversations for the current user (buyer or farmer).
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        \Log::info('[ConversationController@index] Called by user', [
            'id' => $user->id,
            'role' => $user->role,
        ]);

        $conversations = Conversation::where('buyer_id', $user->id)
            ->orWhere('farmer_id', $user->id)
            ->with(['buyer', 'farmer', 'lastMessage'])
            ->orderBy('updated_at', 'desc')
            ->get();

        \Log::info('[ConversationController@index] Conversations found', [
            'count' => $conversations->count(),
            'ids' => $conversations->pluck('id'),
        ]);

        $response = $conversations->map(function ($conversation) use ($user) {
            $otherUser = $conversation->buyer_id === $user->id
                ? $conversation->farmer
                : $conversation->buyer;

            $lastMessage = $conversation->lastMessage;

            return [
                'conversationId'       => $conversation->id,
                'otherUserName'        => optional($otherUser)->name ?? 'Unknown',
                'receiverId'           => optional($otherUser)->id ?? null,
                'lastMessage'          => optional($lastMessage)->message ?? '',
                'lastMessageTimestamp' => optional($lastMessage)?->created_at?->format('H:i') ?? null,
            ];
        });

        return response()->json($response);
    }

    /**
     * Start or retrieve an existing conversation with a specific user.
     * 
     * Expects: { "other_user_id": 123 }
     */
    public function store(Request $request)
    {
        $request->validate([
            'other_user_id' => 'required|integer|exists:users,id',
        ]);

        $user = Auth::user();
        $otherUser = User::findOrFail($request->input('other_user_id'));

        // Determine valid buyer/farmer pairing
        if ($user->role === 'buyer') {
            if ($otherUser->role !== 'farmer') {
                return response()->json(['message' => 'Invalid other user (not a farmer)'], 400);
            }
            $buyerId = $user->id;
            $farmerId = $otherUser->id;
        } elseif ($user->role === 'farmer') {
            if ($otherUser->role !== 'buyer') {
                return response()->json(['message' => 'Invalid other user (not a buyer)'], 400);
            }
            $farmerId = $user->id;
            $buyerId = $otherUser->id;
        } else {
            return response()->json(['message' => 'Unknown user role'], 400);
        }

        // Check if conversation already exists
        $conversation = Conversation::where('buyer_id', $buyerId)
            ->where('farmer_id', $farmerId)
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'buyer_id' => $buyerId,
                'farmer_id' => $farmerId,
            ]);
        }

        return response()->json([
            'conversationId' => $conversation->id,
            'otherUserName'  => $otherUser->name,
            'receiverId'     => $otherUser->id,
        ]);
    }
}
