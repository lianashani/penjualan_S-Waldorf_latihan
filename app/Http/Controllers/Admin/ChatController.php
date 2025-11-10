<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberChat;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {
        // Get all members who have sent messages, with their last message
        $chats = MemberChat::with(['member', 'user'])
            ->select('id_member', DB::raw('MAX(created_at) as last_message_time'))
            ->groupBy('id_member')
            ->orderByDesc('last_message_time')
            ->get();

        // Get full chat details for each member
        $chatList = [];
        foreach ($chats as $chat) {
            $lastMessage = MemberChat::where('id_member', $chat->id_member)
                ->orderByDesc('created_at')
                ->first();

            $unreadCount = MemberChat::where('id_member', $chat->id_member)
                ->where('sender_type', 'member')
                ->where('is_read', 0)
                ->count();

            $chatList[] = [
                'member' => $lastMessage->member,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount
            ];
        }

        return view('kasir.chat.index', compact('chatList'));
    }

    public function show($memberId)
    {
        $member = Member::findOrFail($memberId);
        $messages = MemberChat::with(['member', 'user'])
            ->where('id_member', $memberId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark all member messages as read
        MemberChat::where('id_member', $memberId)
            ->where('sender_type', 'member')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return view('kasir.chat.show', compact('member', 'messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'id_member' => 'required|exists:members,id_member',
            'message' => 'required|string|max:1000'
        ]);

        $user = Auth::user();

        $chat = MemberChat::create([
            'id_member' => $request->id_member,
            'id_user' => $user->id,
            'message' => $request->message,
            'sender_type' => 'staff',
            'is_read' => 0
        ]);

        return response()->json([
            'success' => true,
            'chat' => $chat->load(['member', 'user'])
        ]);
    }

    public function getMessages($memberId)
    {
        $messages = MemberChat::with(['member', 'user'])
            ->where('id_member', $memberId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark member messages as read
        MemberChat::where('id_member', $memberId)
            ->where('sender_type', 'member')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }
}
