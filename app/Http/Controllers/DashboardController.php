<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::check())
        {
            $role = Auth::user()->getRole();

            if ($role == 'administrator')
                return $this->admin();
            elseif ($role == 'teacher')
                return $this->teacher();
            else
                return $this->student();
        }
    }

    public function admin()
    {
        $classroom_count = Classroom::count();
        $teacher_count = User::whereHas('roles', function($q){
            $q->where('name', 'teacher');
        })->count();
        $student_count = User::whereHas('roles', function($q){
            $q->where('name', 'student');
        })->count();
        $lesson_count = Lesson::count();
        return view('admin.dashboard', compact(['classroom_count', 'teacher_count', 'student_count', 'lesson_count']));
    }

    public function teacher()
    {
        $teacher = Auth::user();
        $classroom_count = Classroom::where('owner_id', $teacher->id)->count();

        $student_count = User::whereHas('classroom_students', function ($q) use ($teacher){
            $q->where('owner_id', $teacher->id);
        })->count();

        $lesson_count = Lesson::whereHas('classroom', function ($q) use ($teacher){
            $q -> where('owner_id', $teacher->id);
        })->count();
        return view('teacher.dashboard', compact(['classroom_count', 'student_count', 'lesson_count']));
    }

    public function student()
    {
        $studentId = Auth::user()->id;
        $classrooms = Classroom::whereDoesntHave('classroom_students', function($q) use ($studentId){
            $q->where('student_id', $studentId);
        })->get();

        $liveClassrooms = Classroom::whereHas('classroom_students', function($q) use ($studentId){
            $q->where('student_id', $studentId);
        })->where('status', 1)->get();
        return view('student.dashboard', compact(['classrooms', 'liveClassrooms']));
    }

    private function getClasses()
    {
        return Classroom::with('owner');
    }
}
