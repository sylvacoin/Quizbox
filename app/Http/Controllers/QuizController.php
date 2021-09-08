<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    public function getQuizzes( $lessonId )
    {
        $quizzes = Quiz::where('lesson_id', $lessonId)->paginate(10);
        $lesson = Lesson::find($lessonId);
        return view('teacher.quizzes', compact(['quizzes','lesson']));
    }

    public function createQuiz( $lessonId )
    {
        $lesson = Lesson::find($lessonId);
        return view('teacher.create-quiz', compact('lesson'));
    }

    public function storeQuiz($lessonId, Request $request)
    {
        Log::info($request->options);

        try{
            $lesson = Lesson::find($lessonId);
            $optionTypes = ['multichoice', 'subjective', 'boolean'];
            $booleanOptions = ['True', 'False'];
            $answer = $request->answer[$request->option_type];
            $options = [];
            $booleanAnswer = '';

            if ($request->option_type == 3)
            {
                $answerKey = array_search(ucwords($answer), $booleanOptions);
                $booleanAnswer = $this->toAlpha( $answerKey );
            }

            if (!$lesson)
                throw new \Exception('Lesson was not found');


            $quiz = $lesson->quizzes()->create([
                'question' => $request->question,
                'option_type' => $optionTypes[$request->option_type - 1],
                'answer' => $request->option_type == 3 ? $booleanAnswer : $answer
            ]);

            if (!$quiz)
                throw new \Exception('An error occurred question was not added');

            if ($request->option_type == 1)
            {
                foreach($request->options as $key => $option)
                {
                    $options[] = [
                        'option_key' => $this->toAlpha($key),
                        'option_value' => $option,
                    ];
                }

                $quiz->quiz_options()->createMany($options);
            }else if ($request->option_type == 3){

                foreach($booleanOptions as $key => $option)
                {
                    $options[] = [
                        'option_key' => $this->toAlpha($key),
                        'option_value' => $option,
                    ];
                }

                $quiz->quiz_options()->createMany($options);

            }

            session()->flash('flash.banner','Question was added successfully');
            session()->flash('flash.bannerStyle', 'success');

            return response()->json(['success' => true, 'message' => 'Ok']);

        }catch(\Exception $ex)
        {
            Log::error($ex);
            return response()->json(['success' => false, 'message' => $ex->getMessage()]);
        }
    }

    function toAlpha($num)
    {
        $alphabet = range('A', 'Z');
        return $alphabet[$num];
    }

    public function deleteQuiz( $quizId )
    {
        try{
            $quiz = Quiz::find($quizId);
            if (!$quiz)
                throw new \Exception('Quiz not found');
            $quiz->delete();
            return back()->with('success', 'Quiz was deleted successfully');
        }catch(\Exception $ex)
        {
            Log::error($ex);
            return back()->with('error', $ex->getMessage());
        }
    }
}
