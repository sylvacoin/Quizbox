<?php

namespace App\Http\Controllers;

use App\Events\SendMessageEvent;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'sender_id' => 'required',
            'message' => 'required',
            'lesson_id' => 'required',
            'room_url' => 'required'
        ]);
        try{

            $chat = Chat::create([
                'sender_id' => $request->sender_id,
                'message' => $request->message,
                'message_type' => 'chat',
                'lesson_id' => $request->lesson_id,
                'room_url' => $request->room_url
            ]);

            broadcast( new SendMessageEvent( $chat ) )->toOthers();

            return response()->json(['success' => 'true', 'message' => 'Sent']);

        }catch(\Exception $ex)
        {
            Log::error($ex);
            return response()->json(['success' => 'false', 'message' => $ex->getMessage()]);
        }
    }

    public function askQuestion()
    {

    }

    public function answerQuestion()
    {

    }

    public function getLessonChats($roomId)
    {
        $chats = Chat::where('room_url', $roomId)->take(5)->get();
        return response()->json(['data' => $chats]);
    }

}
