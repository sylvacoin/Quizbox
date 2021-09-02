<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ClassroomController extends Controller
{
    public function getClassrooms()
    {
        $classrooms = Classroom::paginate(10);
        //get all the classroom
        return view('admin.classrooms', compact('classrooms'));
    }

    public function getTeacherClassrooms()
    {
        $ownerId = Auth::user()->id;
        $classrooms = Classroom::whereHas("owner", function ($user) use ($ownerId) {
            $user->where('owner_id', $ownerId);
        })->paginate(10);
        return view('teacher.classrooms', compact('classrooms'));
    }

    public function storeClassroom(Request $request)
    {
        $request->validate([
            'classroom' => 'required'
        ]);

        try{
            $owner = Auth::user();
            $classroom = $owner->classrooms()->create([
                'title' => $request->classroom,
                'slug' => Str::slug($request->classroom),
                'room_id' => Str::random(13),
            ]);

            if (!$classroom)
                throw new \Exception('classroom.personal');

            session()->flash('flash.banner', 'Classroom was added successfully');
            session()->flash('flash.bannerStyle', 'success');

            return back();
        }catch (\Exception $ex)
        {
            Log::error($ex);
            session()->flash('flash.banner', 'An error occurred. Please contact administrator');
            session()->flash('flash.bannerStyle', 'success');
            return back();
        }
    }

    public function subscribeToClassroom($classroomId)
    {
        try{
            $user = Auth::user();

            if ($user->getRole() != 'student')
                throw new \Exception('This account is not a student account');

            $classroomFound = ClassroomStudent::where('classroom_id', $classroomId)->where('student_id', $user->id)->count();
            if ($classroomFound > 0)
                throw new \Exception('You already subscribed to this classroom');

            $classroomStudent = ClassroomStudent::create([
                'classroom_id' => $classroomId,
                'student_id' => $user->id
            ]);

            if (!$classroomStudent)
                throw new \Exception('An error occurred. Please contact administrator');

            session()->flash('flash.banner', 'Your subscription was successful');
            session()->flash('flash.bannerStyle', 'success');

            return back();
        }catch (\Exception $ex)
        {
            Log::error($ex);
            return back()->with('error', $ex->getMessage());
        }
    }

    public function joinClassroom( $classroomURL )
    {
        try {
            $classroom = Classroom::with('lessons')->where('room_id', $classroomURL)->first();

            if (!$classroom)
                throw new \Exception('Invalid link or Classroom does not exist');

            if ($classroom->status == 0)
                throw new \Exception('Host has not yet started this class');

            $lesson = Lesson::with('attachments')->where('status', 1)->where('classroom_id', $classroom->id)->first();

            return view('student.classroom', ['notes' => $lesson, 'attachments' => $lesson->attachments, 'classroom' => $classroom]);

        }catch (\Exception $ex)
        {
            Log::error($ex);
            return back()->with('error', $ex->getMessage());
        }
    }

    public function unsubscribeFromClassroom($classroomId)
    {
        try{
            $user = Auth::user();

            if ($user->getRole() != 'student')
                throw new \Exception('This account is not a student account');

            $classroomFound = ClassroomStudent::where('classroom_id', $classroomId)->where('student_id', $user->id)->first();
            if (!$classroomFound)
                throw new \Exception('You have not subscribed to this classroom');

            $classroomFound->delete();

            session()->flash('flash.banner','Your successfully unsubscribed to '.$classroomFound->classroom->title.' class');
            session()->flash('flash.bannerStyle', 'success');

            return back();

        }catch (\Exception $ex)
        {
            Log::error($ex);
            return back()->with('error', $ex->getMessage());
        }
    }

    public function startClass($lessonId, $classroomURL)
    {
        try {
            //get current user
            $currentUser = Auth::user();
            //get the classroom
            $classroom = Classroom::where('room_id',$classroomURL)->first();


            if ($classroom->owner_id != $currentUser->id)
                throw new \Exception('You dont have access to start this class');

//            if ($classroom->status == 1 )
//                throw new \Exception('Classroom is already active');


            //get the lesson
            $lesson = Lesson::find($lessonId);

            if ($lesson->classroom->owner_id != $currentUser->id)
                throw new \Exception('You dont have access to start this class');

//            if ($lesson->status == 1 )
//                throw new \Exception('Classroom is already active');

            $lesson->status = 1;
            $classroom->status = 1;

            $lesson->save();
            $classroom->save();

//            broadcast(new StartClassEvent($classroom->room_id))->toOthers();

            return back()->with('success', 'Classroom is live');
        }catch (\Exception $ex)
        {
            Log::error($ex);
            return back()->with('error', $ex->getMessage());
        }
    }

    public function stopClass($lessonId, $classroomURL)
    {
        try {
            //get current user
            $currentUser = Auth::user();
            //get the classroom
            $classroom = Classroom::where('room_id', $classroomURL)->first();

            if ($classroom->owner_id != $currentUser->id)
                throw new \Exception('You dont have authorization to perform this action');

            //get the lesson
            $lesson = Lesson::find($lessonId);

            if ($lesson->classroom->owner_id != $currentUser->id)
                throw new \Exception('You dont have authorization to perform this action');

            $lesson->status = 0;
            $classroom->status = 0;
            $classroom->save();
            $lesson->save();

            return back()->with('success', 'Classroom is offline');

        }catch (\Exception $ex)
        {
            Log::error($ex);
            return back()->with('error', $ex->getMessage());
        }
    }

    public function findClassrooms()
    {
        $studentId = Auth::user()->id;
        $classrooms = Classroom::whereDoesntHave('classroom_students', function($q) use ($studentId){
            $q->where('student_id', $studentId);
        })->paginate(5);
        return view('student.classrooms', compact('classrooms'));
    }

    public function getStudentClassrooms()
    {
        $studentId = Auth::user()->id;

        $classrooms = Classroom::with('owner')->whereHas('classroom_students', function($q) use ($studentId){
            $q->where('student_id', $studentId);
        })->paginate(10);
        return view('student.my-classes', compact('classrooms'));
    }

}
