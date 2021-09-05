<?php

namespace App\Http\Controllers;

use App\Imports\TeachersImport;
use App\Models\ClassroomStudent;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
/*
 * TEACHERS
 */
    public function getTeachers()
    {
        $users = User::whereHas("roles", function ($q){
            $q->where('name', 'teacher');
        });
        $teachers = $users->paginate(5);

        return view('admin.teachers', compact('teachers'));
    }

    public function uploadTeachers()
    {
        return view('admin.upload-teachers');
    }

    public function createTeacher()
    {
        return view('admin.create-teacher');
    }

    public function storeUploadedTeachers(Request $request)
    {
        $request->validate([
            'teacherList' => 'required'
        ]);

        try{
            $file = $request->file('teacherList')->store('import');
            $import =  (new TeachersImport);
            $import->import($file);

            session()->flash('flash.banner', 'Teachers were imported successfully');
            session()->flash('flash.bannerStyle', 'success');

            return redirect(route('teachers.index'));

        }catch(\Exception $ex)
        {
            Log::error($ex);
            return back()->withError($ex->getMessage());

        }
    }

    public function storeTeacher( Request $request)
    {
        try {
            Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'gender' => 'required',
                'password' => ['required', 'string', 'confirmed'],
            ])->validate();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole('teacher');
            $user->notify(new WelcomeNotification($user->name, $request->password));

            session()->flash('flash.banner', 'Teacher was added successfully');
            session()->flash('flash.bannerStyle', 'success');

            return redirect(route('teachers.index'));
        }catch(\Exception $ex)
        {
            Log::error($ex);
            return back()->with('error', 'An error occurred. Teacher account was not created');
        }

    }

/*
 * STUDENTS
 */

    public function getStudents()
    {
        $users = User::whereHas("roles", function ($q){
            $q->where('name', 'student');
        });

        $students = $users->paginate(5);

        return view('admin.students', compact('students'));
    }

    public function getStudentDetail( $id )
    {
        try{
            $student = User::find($id);

            if (!$student)
                throw new \Exception('Student was not found');

            return view('admin.student-detail', compact('student'));

        }catch(\Exception $ex)
        {
            session()->flash('flash.banner', $ex->getMessage());
            session()->flash('flash.bannerStyle', 'danger');
            return back();
        }
    }

    public function getTeacherStudents()
    {
        $teacherId = Auth::user()->id;
        $students = ClassroomStudent::whereHas('classroom', function ($q) use ($teacherId) {
            $q->where('owner_id', $teacherId);
        })->whereHas('student', function ($q){
            $q->where('name', 'student');
        })->paginate(10);

        return view('teacher.students', compact('students'));
    }
}
