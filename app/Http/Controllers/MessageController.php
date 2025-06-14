<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessageNotification;

class MessageController extends Controller
{
    public function inbox()
    {
        $userId = Auth::id();

        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->get()
            ->groupBy(function ($msg) use ($userId) {
                return $msg->sender_id === $userId ? $msg->receiver_id : $msg->sender_id;
            });

        $role = Auth::user()->role;
        $view = $role === 'farmer' ? 'farmer.messages.index' : 'buyer.messages.index';

        return view($view, compact('conversations'));
    }

    public function show($userId)
    {
        $authId = Auth::id();

        $messages = Message::where(function ($q) use ($authId, $userId) {
            $q->where('sender_id', $authId)->where('receiver_id', $userId);
        })->orWhere(function ($q) use ($authId, $userId) {
            $q->where('sender_id', $userId)->where('receiver_id', $authId);
        })->get();

        $conversations = Message::where('sender_id', $authId)
            ->orWhere('receiver_id', $authId)
            ->get()
            ->groupBy(function ($msg) use ($authId) {
                return $msg->sender_id === $authId ? $msg->receiver_id : $msg->sender_id;
            });

        $role = Auth::user()->role;
        $view = $role === 'farmer' ? 'farmer.messages.show' : 'buyer.messages.show';

        return view($view, [
            'messages' => $messages,
            'conversations' => $conversations,
            'userId' => $userId // ✅ renamed from receiverId
        ]);
    }

    public function reply(Request $request, $userId)
{
    $request->validate([
        'message' => 'required|string|max:1000',
    ]);

    $auth = Auth::user();

    // Safely get correct IDs for roles
    $buyerId = $auth->role === 'buyer' ? $auth->id : $userId;
    $farmerId = $auth->role === 'farmer' ? $auth->id : $userId;

    // 🧠 Check if conversation exists first
    $conversation = \App\Models\Conversation::where('buyer_id', $buyerId)
        ->where('farmer_id', $farmerId)
        ->first();

    // 🔧 Create if not found
    if (!$conversation) {
        $conversation = \App\Models\Conversation::create([
            'buyer_id' => $buyerId,
            'farmer_id' => $farmerId,
        ]);
    }

    // ✅ Confirm we have a valid conversation ID
    if (!$conversation || !$conversation->id) {
        return back()->withErrors(['conversation' => 'Conversation creation failed.']);
    }

    // 📨 Save the message
    \App\Models\Message::create([
        'conversation_id' => $conversation->id,
        'sender_id' => $auth->id,
        'receiver_id' => $userId,
        'message' => $request->message,
        'is_read' => false,
    ]);

    // 🔔 Notify receiver
    $receiver = \App\Models\User::find($userId);
    $receiver->notify(new \App\Notifications\NewMessageNotification([
        'sender' => $auth->name,
        'sender_id' => $auth->id,
    ]));

    // 🔁 Redirect
    $route = $auth->role === 'farmer' ? 'farmer.messages.show' : 'buyer.messages.show';
    return redirect()->route($route, $userId)->with('success', 'Reply sent!');
}

    public function create()
    {
        $role = Auth::user()->role;
        $users = $role === 'farmer'
            ? User::where('role', 'buyer')->get()
            : User::where('role', 'farmer')->get();

        $view = $role === 'farmer' ? 'farmer.messages.create' : 'buyer.messages.create';
        return view($view, ['users' => $users]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        $receiver = User::find($request->receiver_id);
        $senderName = Auth::user()->name;

        $receiver->notify(new NewMessageNotification([
    'sender' => $senderName,
    'sender_id' => Auth::id(), // ✅ Fixes the error
]));

        $route = Auth::user()->role === 'farmer' ? 'farmer.messages.show' : 'buyer.messages.show';
        return redirect()->route($route, $request->receiver_id)->with('success', 'Message sent!');
    }
}
