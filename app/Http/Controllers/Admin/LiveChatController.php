<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Settings;
use App\Traits\CreatesNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveChatController extends Controller
{
    use CreatesNotifications;

    public function index()
    {
        $conversations = ChatConversation::with(['user', 'latestMessage'])
            ->orderByDesc('last_message_at')
            ->orderByDesc('id')
            ->get();

        return view('admin.livechat.index', [
            'title' => 'Live Chat',
            'settings' => Settings::find(1),
            'conversations' => $conversations,
            'openCount' => $conversations->where('status', 'open')->count(),
            'unreadTotal' => $conversations->sum('admin_unread'),
        ]);
    }

    public function conversations()
    {
        $conversations = ChatConversation::with(['user', 'latestMessage'])
            ->orderByDesc('last_message_at')
            ->orderByDesc('id')
            ->get()
            ->map(function ($c) {
                $user = $c->user;
                $last = $c->latestMessage;

                return [
                    'id' => $c->id,
                    'status' => $c->status,
                    'admin_unread' => (int) $c->admin_unread,
                    'last_message_at' => optional($c->last_message_at)->diffForHumans(),
                    'user' => [
                        'id' => optional($user)->id,
                        'name' => optional($user)->name ?: 'User',
                        'email' => optional($user)->email,
                        'photo' => $user ? profile_photo_url($user) : '',
                    ],
                    'preview' => $last ? \Illuminate\Support\Str::limit($last->message, 80) : 'No messages yet',
                ];
            });

        return response()->json([
            'conversations' => $conversations,
            'unread_total' => ChatConversation::sum('admin_unread'),
        ]);
    }

    public function messages($id)
    {
        $conversation = ChatConversation::with('user')->findOrFail($id);

        ChatMessage::where('conversation_id', $conversation->id)
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $conversation->admin_unread = 0;
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
                    'is_mine' => $m->sender_type === 'admin',
                ];
            });

        return response()->json([
            'conversation' => [
                'id' => $conversation->id,
                'status' => $conversation->status,
                'user' => [
                    'id' => optional($conversation->user)->id,
                    'name' => optional($conversation->user)->name,
                    'email' => optional($conversation->user)->email,
                    'photo' => $conversation->user ? profile_photo_url($conversation->user) : '',
                ],
            ],
            'messages' => $messages,
        ]);
    }

    public function send(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $conversation = ChatConversation::findOrFail($id);
        $admin = Auth::guard('admin')->user();

        if ($conversation->status === 'closed') {
            $conversation->status = 'open';
        }

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'admin',
            'sender_id' => $admin->id,
            'message' => trim($request->message),
            'is_read' => false,
        ]);

        $conversation->admin_id = $admin->id;
        $conversation->user_unread = $conversation->user_unread + 1;
        $conversation->last_message_at = now();
        $conversation->save();

        try {
            $this->createNotification(
                $conversation->user_id,
                'support',
                'New support message',
                \Illuminate\Support\Str::limit($message->message, 120),
                ['conversation_id' => $conversation->id]
            );
        } catch (\Throwable $e) {
            // non-fatal
        }

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_type' => 'admin',
                'message' => $message->message,
                'created_at' => $message->created_at->format('M j, g:i A'),
                'is_mine' => true,
            ],
        ]);
    }

    public function close($id)
    {
        $conversation = ChatConversation::findOrFail($id);
        $conversation->status = 'closed';
        $conversation->save();

        return response()->json(['success' => true, 'status' => 'closed']);
    }

    public function reopen($id)
    {
        $conversation = ChatConversation::findOrFail($id);
        $conversation->status = 'open';
        $conversation->save();

        return response()->json(['success' => true, 'status' => 'open']);
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => (int) ChatConversation::sum('admin_unread'),
        ]);
    }
}
