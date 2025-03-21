<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Chat;
use App\Models\Message;
use App\Models\ChatParticipant;

class ChatController extends Controller
{

    public function createChat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array|min:2',
            'user_ids.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $chat = Chat::create();

        foreach ($request->user_ids as $user_id) {
            ChatParticipant::create([
                'chat_id' => $chat->id,
                'user_id' => $user_id,
            ]);
        }

        return response()->json(['message' => 'Chat created!', 'chat_id' => $chat->id], 201);
    }

    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chat_id' => 'required|exists:chats,id',
            'sender_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $mediaPath = null;
        if ($request->hasFile('media')) {
            $mediaPath = $request->file('media')->store('chat_media', 'public');
        }

        $message = Message::create([
            'chat_id' => $request->chat_id,
            'sender_id' => $request->sender_id,
            'message' => $request->message,
            'media' => $mediaPath,
        ]);

        return response()->json(['message' => 'Message sent!', 'data' => $message], 201);
    }

    public function getChatMessages($chat_id)
    {
        $chat = Chat::with('messages.sender')->find($chat_id);

        if (!$chat) {
            return response()->json(['message' => 'Chat not found'], 404);
        }

        return response()->json(['chat' => $chat], 200);
    }


    public function getUserChats(Request $request)
    {
        $userId = $request->user()->id;

        $chats = Chat::with(['latestMessage', 'participants.user'])
            ->whereHas('participants', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        return response()->json([
            'status' => 'success',
            'chats' => $chats,
        ]);
    }


}
