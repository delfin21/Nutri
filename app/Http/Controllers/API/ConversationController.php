<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    // Existing index() method remains unchanged
public function index(Request $request)
{
    $user = Auth::user();

    $conversations = Conversation::where('buyer_id', $user->id)
        ->orWhere('farmer_id', $user->id)
        ->with(['buyer', 'farmer', 'lastMessage'])
        ->orderBy('updated_at', 'desc')
        ->get();

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
     * Start a new conversation (if one doesn’t already exist).
     * Expects JSON: { "other_user_id": 123 }
     * - If Auth::user()->role == 'buyer', other_user_id must be a farmer
     * - If Auth::user()->role == 'farmer', other_user_id must be a buyer
     */
    public function store(Request $request)
    {
        $request->validate([
            'other_user_id' => 'required|integer|exists:users,id',
        ]);

        $user = Auth::user();
        $otherUser = User::findOrFail($request->input('other_user_id'));

        // Determine roles
        if ($user->role === 'buyer') {
            // Buyer can only start a conversation with a farmer
            if ($otherUser->role !== 'farmer') {
                return response()->json(['message' => 'Invalid other user (not a farmer)'], 400);
            }
            $buyerId = $user->id;
            $farmerId = $otherUser->id;
        } elseif ($user->role === 'farmer') {
            // Farmer can only start a conversation with a buyer
            if ($otherUser->role !== 'buyer') {
                return response()->json(['message' => 'Invalid other user (not a buyer)'], 400);
            }
            $farmerId = $user->id;
            $buyerId = $otherUser->id;
        } else {
            return response()->json(['message' => 'Unknown user role'], 400);
        }

        // Check if a conversation between these two already exists
        $conversation = Conversation::where('buyer_id', $buyerId)
            ->where('farmer_id', $farmerId)
            ->first();

        if (!$conversation) {
            // Create a new conversation
            $conversation = Conversation::create([
                'buyer_id' => $buyerId,
                'farmer_id' => $farmerId,
            ]);
        }

        // Return the conversation info with the “other user” details
        return response()->json([
            'conversationId' => $conversation->id,
            'otherUserName'  => $otherUser->name,
            'receiverId'     => $otherUser->id,
        ]);
    }
}
