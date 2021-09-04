<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Quiz;
use App\Models\StudentQuizResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentQuizResultController extends Controller
{
    public  function getLeaderBoard( $classroomId )
    {
        $leaderboardResults = StudentQuizResult::where('classroom_id', $classroomId)
            ->with('user')
            ->selectRaw("SUM(points) as total_points, classroom_id, student_id")
            ->groupBy('student_id')
            ->groupBy('classroom_id')
            ->orderBy('total_points')
            ->get();

//        dd($leaderboardResults->first()->user);

        return view('student.leaderboard', compact('leaderboardResults'));
    }

    public function getTeacherClasses()
    {
        $ownerId = Auth::user()->id;
        $classrooms = Classroom::whereHas("owner", function ($user) use ($ownerId) {
            $user->where('owner_id', $ownerId);
        })->paginate(10);

        return view('teacher.leaderboard-classes', compact('classrooms'));
    }

    public function getStudentClasses()
    {
        $studentId = Auth::user()->id;

        $classrooms = Classroom::with('owner')->whereHas('classroom_students', function($q) use ($studentId){
            $q->where('student_id', $studentId);
        })->paginate(10);
        return view('student.leaderboard-classes', compact('classrooms'));
    }

    public function getAllClasses()
    {
        $classrooms = Classroom::paginate(10);
        //get all the classroom

        return view('admin.leaderboard-classes', compact('classrooms'));
    }
}
