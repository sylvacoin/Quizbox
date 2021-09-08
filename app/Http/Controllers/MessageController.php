<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function inbox()
    {
        if (!Auth::check())
        {
            throw new \Exception('User needs to be logged in');
        }

        $userId = Auth::user()->id;
        //gets message for a particular user;
        $messages = Message::with('receiver')->where(function($q) use ($userId) {
            $q->where('sender_id', $userId)->orWhere('receiver_id', $userId);
        })->where('message_id', null)->get();
        return view('general.inbox', compact('messages'));
    }

    public function singleMessage( $messageId )
    {
        //gets message for a particular user;
        $messages = Message::with(['receiver', 'sender'])
            ->where('message_id', $messageId)
            ->orWhere('id', $messageId)
            ->orderBy('created_at')
            ->get();
        return view('general.single-inbox', compact(['messages','messageId']));
    }

    public function createMessage()
    {
        return view('general.create-message');
    }

    public function sendStudentMessage($studentId)
    {
        try{
            $student = User::find($studentId);
            if (!$student)
                throw new \Exception('Student was not found');

            return view('general.create-message', compact('student'));
        }catch (\Exception $ex)
        {
            Log::error($ex);
            return back()->with('errors', $ex->getMessage());
        }
    }

    public function replyMessage( $messageId )
    {
        try {
            $message = Message::find($messageId);
            if (!$message)
                throw new \Exception('Message not found');

            $sender = Auth::user()->id == $message->receiver_id ? $message->sender->email: "";

            return view('general.create-message', compact(['message', 'sender']));

        }catch(\Exception $ex)
        {
            Log::error($ex);
            return back()->with('error', $ex->getMessage());
        }
    }

    public function sendMessage(MessageRequest $request)
    {
        $request->validated();

        try {
            $sentCount = 0;
            $totalCount = 0;
            $errors = [];
            $recipients = explode(';', $request->receiver_id );
            foreach ($recipients as $receiver)
            {
                $recipient = User::where('email', $receiver)->first();

                if (!$recipient )
                {
                    $errors[] = 'Message to '.$recipient->name.' was not sent';
                    continue;
                }
                $sender_id = Auth::user()->id;
                $totalCount += 1;
                $messageSent = Message::create([
                    'message' => $request->message,
                    'subject' => $request->subject,
                    'message_id' => $request->message_id,
                    'receiver_id' => $recipient->id,
                    'sender_id' => $sender_id,
                    'status' => false
                ]);
                if ($messageSent){
                    $sentCount += 1;
                }else{
                    $errors[] = 'Message to '.$recipient->name.' was not sent';
                }
            }

            session()->flash('flash.banner', $sentCount.'/'.$totalCount.' messages was sent.');
            session()->flash('flash.bannerStyle', 'success');

            return response()->json(['success' => true, 'errors' => $errors, 'delivered' => $sentCount, 'sent' => $totalCount]);

        }catch(\Exception $ex)
        {
            Log::error($ex);
            return response()->json(['success' => false, 'message' => $ex->getMessage()]);
        }
    }

    public function deleteMessage( $messageId )
    {
        try{
            $message = Message::find($messageId);
            if (!$message)
                throw new Exception('Message was not found');

            $message->delete();
            session()->flash('flash.banner', 'Message was deleted successfully');
            session()->flash('flash.bannerStyle', 'success');

            return redirect(route('inbox'));
        }catch(\Exception $ex)
        {
            Log::error($ex);
            return back()->with('error', $ex->getMessage());
        }
    }

    public function markMessageAsRead( $messageId )
    {

    }

    public function markMessageAsUnread( $messageId )
    {

    }
}
