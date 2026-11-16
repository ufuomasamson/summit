<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveChatController extends Controller
{
    protected function getOrCreateConversation()
    {
        $user = Auth::user();

        $conversation = ChatConversation::where('user_id', $user->id)
            ->where('status', 'open')
            ->latest('id')
            ->first();

        if (!$conversation) {
            $conversation = ChatConversation::where('user_id', $user->id)
                ->latest('id')
                ->first();
        }

        if ($conversation) {
            if ($conversation->status === 'closed') {
                $conversation->status = 'open';
                $conversation->save();
            }

            return $conversation;
        }

        return ChatConversation::create([
            'user_id' => $user->id,
            'status' => 'open',
            'user_unread' => 0,
            'admin_unread' => 0,
            'last_message_at' => now(),
        ]);
    }

    public function bootstrap()
    {
        $conversation = $this->getOrCreateConversation();

        ChatMessage::where('conversation_id', $conversation->id)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $conversation->user_unread = 0;
        $conversation->save();

        $messages = ChatMessage::where('conversation_id', $conversation->id)
            ->orderBy('id')
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'sender_type' => $m->sender_type,
                    'message' => $m->message,
                    'created_at' => $m->created_at->format('M j, g:i A'),
                    'is_mine' => $m->sender_type === 'user',
                ];
            });

        return response()->json([
            'conversation_id' => $conversation->id,
            'status' => $conversation->status,
            'unread' => 0,
            'messages' => $messages,
        ]);
    }

    public function poll(Request $request)
    {
        $user = Auth::user();
        $afterId = (int) $request->query('after_id', 0);

        $conversation = ChatConversation::where('user_id', $user->id)
            ->latest('id')
            ->first();

        if (!$conversation) {
            return response()->json([
                'messages' => [],
                'unread' => 0,
                'status' => 'none',
            ]);
        }

        $query = ChatMessage::where('conversation_id', $conversation->id)->orderBy('id');
        if ($afterId > 0) {
            $query->where('id', '>', $afterId);
        }

        $messages = $query->get()->map(function ($m) {
            return [
                'id' => $m->id,
                'sender_type' => $m->sender_type,
                'message' => $m->message,
                'created_at' => $m->created_at->format('M j, g:i A'),
                'is_mine' => $m->sender_type === 'user',
            ];
        });

        // Mark newly fetched admin messages as read when widget is open
        if ($request->boolean('mark_read')) {
            ChatMessage::where('conversation_id', $conversation->id)
                ->where('sender_type', 'admin')
                ->where('is_read', false)
                ->update(['is_read' => true]);
            $conversation->user_unread = 0;
            $conversation->save();
        }

        return response()->json([
            'conversation_id' => $conversation->id,
            'status' => $conversation->status,
            'unread' => (int) $conversation->fresh()->user_unread,
            'messages' => $messages,
        ]);
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $user = Auth::user();
        $conversation = $this->getOrCreateConversation();

        if ($conversation->status === 'closed') {
            $conversation->status = 'open';
        }

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'user',
            'sender_id' => $user->id,
            'message' => trim($request->message),
            'is_read' => false,
        ]);

        $conversation->admin_unread = $conversation->admin_unread + 1;
        $conversation->last_message_at = now();
        $conversation->save();

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'message' => [
                'id' => $message->id,
                'sender_type' => 'user',
                'message' => $message->message,
                'created_at' => $message->created_at->format('M j, g:i A'),
                'is_mine' => true,
            ],
        ]);
    }

    public function unreadCount()
    {
        $count = (int) ChatConversation::where('user_id', Auth::id())->sum('user_unread');

        return response()->json(['count' => $count]);
    }
}
