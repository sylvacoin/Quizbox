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
        try{
            $lesson = Lesson::find($lessonId);
            $optionTypes = ['multichoice', 'subjective', 'boolean'];

            if (!$lesson)
                throw new \Exception('Lesson was not found');


            $quiz = $lesson->quizzes()->create([
                'question' => $request->question,
                'option_type' => $optionTypes[$request->option_type - 1],
                'answer' => $request->answer[$request->option_type]
            ]);

            if (!$quiz)
                throw new \Exception('An error occurred question was not added');

            if ($request->option_type == 1)
            {
                foreach($request->options as $key => $option)
                {
                    $quiz->quiz_options()->create([
                        'option_key' => $this->toAlpha($key),
                        'option_value' => $option,
                    ]);
                }
            }

            session()->flash('flash.banner','Question was added successfully');
            session()->flash('flash.bannerStyle', 'success');

            return redirect( route('quiz.index', $lessonId) );

        }catch(\Exception $ex)
        {
            Log::error($ex);
            return back()->with('error', $ex->getMessage());
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
