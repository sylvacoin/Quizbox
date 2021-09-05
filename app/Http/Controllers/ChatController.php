<?php

namespace App\Http\Controllers;

use App\Events\SendMessageEvent;
use App\Events\SendRankingEvent;
use App\Http\Resources\LeaderBoardResource;
use App\Models\Chat;
use App\Models\Classroom;
use App\Models\Quiz;
use App\Models\StudentQuizResult;
use App\Models\User;
use Carbon\Carbon;
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

    public function askQuestion( Request $request )
    {
        try{
            $quiz = Quiz::with('quiz_options')->find($request->question_id);

            if (!$quiz)
                throw new \Exception('question was not found');

            Log::info($quiz);

            $chat = Chat::create([
                'sender_id' => $request->sender_id,
                'message' => json_encode([
                    'id' => $quiz->id,
                    'question' => $quiz->question,
                    'option_type' => $quiz->option_type,
                    'options' => $quiz->quiz_options->toArray()
                ]),
                'message_type' => 'quiz',
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

    public function answerQuestion(Request $request)
    {
        $request->validate([
            'quid' => 'required',
            'answer' => 'required',
            'sender_id' => 'required',
            'room_url' => 'required'
        ]);

        try {

            $student = User::whereHas('roles', function($q){
                $q->where('name', 'student');
            })->find($request->sender_id);
            if (!$student )
                throw new \Exception('No student with such id exists');

            $classroom = Classroom::where('room_id',$request->room_url)->first();
            if (! $classroom)
                throw new \Exception('Classroom does not exist');

            $quiz = Quiz::find($request->quid);
            if (! $quiz)
                throw new \Exception('Quiz does not exist');

            $olderThan3months = Carbon::now()->subDays(90);
            $result = StudentQuizResult::where('quiz_id', $request->quid)
                ->where('student_id', $request->sender_id)->orderByDesc('id')->first();


            if ($result && !$result->created_at->lt($olderThan3months))
                throw new \Exception('You already answered this question');

            $isCorrectAnswer = false;
            $point = 0;

            if ( $quiz->answer == $request->answer)
            {
                $isCorrectAnswer = true;
                $point = 1;
            }


            $result = StudentQuizResult::create([
               'student_id' => $request->sender_id,
                'classroom_id' => $classroom->id,
                'quiz_id' => $request->quid,
                'answer' => $request->answer,
                'is_correct_answer' => $isCorrectAnswer,
                'points' => $point
            ]);

            $chat = Chat::create([
                'sender_id' => $request->sender_id,
                'message' => $student->name. ' answered the question '.($isCorrectAnswer ? 'correctly' : 'wrongly' ).' and so scored '.$point.' point',
                'message_type' => 'notification',
                'lesson_id' => $request->lesson_id,
                'room_url' => $request->room_url
            ]);

            $rankings = $this->getRanking($classroom->id);

            broadcast( new SendMessageEvent( $chat ) )->toOthers();
            broadcast(new SendRankingEvent( $classroom->id, $rankings));

            //TODO: get the leaderboard here and push result to broadcast;
            return response()->json(['success' => true, 'message' => 'ok']);
        }catch(\Exception $ex)
        {
            Log::error($ex);
            return response()->json([
                'success'=>false,
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function getRanking( $classroomId )
    {
        $leaderboardResults = StudentQuizResult::where('classroom_id', $classroomId)
            ->with('user')
            ->selectRaw("SUM(points) as total_points, classroom_id, student_id")
            ->groupBy('student_id')
            ->groupBy('classroom_id')
            ->orderByDesc('total_points')
            ->get();

//        dd($leaderboardResults->first()->user);

        return LeaderBoardResource::collection($leaderboardResults);
    }

    public function getLessonChats($roomId)
    {
        $chats = Chat::where('room_url', $roomId)->take(-10)->get();
        return response()->json(['data' => $chats]);
    }

}
