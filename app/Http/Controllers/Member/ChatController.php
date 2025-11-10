<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MemberChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $member = Auth::guard('member')->user();

        // Get all chats for this member, ordered by newest first
        $chats = MemberChat::with('user')
            ->where('id_member', $member->id_member)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark all staff messages as read
        MemberChat::where('id_member', $member->id_member)
            ->where('sender_type', 'staff')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('member.chat.index', compact('chats', 'member'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $member = Auth::guard('member')->user();

        $chat = MemberChat::create([
            'id_member' => $member->id_member,
            'message' => $request->message,
            'sender_type' => 'member',
            'is_read' => false
        ]);

        return response()->json([
            'success' => true,
            'chat' => $chat->load('member'),
            'message' => 'Pesan terkirim'
        ]);
    }

    public function getMessages()
    {
        $member = Auth::guard('member')->user();

        $chats = MemberChat::with('user')
            ->where('id_member', $member->id_member)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark staff messages as read
        MemberChat::where('id_member', $member->id_member)
            ->where('sender_type', 'staff')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'chats' => $chats
        ]);
    }
}
