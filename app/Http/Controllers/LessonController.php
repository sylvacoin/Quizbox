<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Classroom;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use mysql_xdevapi\Exception;

class LessonController extends Controller
{
    public function getLessons( $classroomId )
    {
        $lessons = Lesson::where('classroom_id', $classroomId)->orderBy('status', 'desc')->paginate(10);
        $classroom = Classroom::find($classroomId);
        //get all the classroom
        return view('teacher.lessons', compact(['classroom', 'lessons']));
    }

    public function getLesson( $lessonId )
    {
        try{
            $lesson=Lesson::find($lessonId);
            if (!$lesson)
                throw new \Exception('Lesson was not found');
            return view('teacher.lesson-detail', compact('lesson'));
        }catch (\Exception $ex)
        {
            return back()->with('error', $ex->getMessage());
        }
    }

    public function deleteLesson( $lessonId )
    {
        try{
            $owner = Auth::user();

            $lesson = Lesson::find($lessonId);

            if (! $lesson)
                return back()->with('error', 'Classroom was not found');

            if ($lesson->classroom->owner_id != $owner->id)
                return back()->with('error', 'You dont have right to modify this classroom');

            $lesson->delete();

            session()->flash('flash.banner', 'Classroom was deleted successfully');
            session()->flash('flash.bannerStyle', 'success');

            return redirect(route('classrooms.lessons', $lesson->classroom->id));
        }catch(\Exception $ex)
        {
            Log::error($ex);
            return back()->with('error', 'An error occurred please contact administrator');
        }
    }

    public function createLesson( $classroomId )
    {
        $classroom = Classroom::find($classroomId);
        return view('teacher.create-lesson', compact('classroom'));
    }

    public function uploadLesson( $classroomId, Request $request )
    {

    }

    public function editLesson( $lessonId )
    {
        $lesson = Lesson::find($lessonId);

        if (!$lesson)
            throw new \Exception('Lesson was not found');

        return view('teacher.create-lesson', compact('lesson'));
    }

    public function updateLesson( $lessonId, Request $request )
    {
        try {
            $lesson = Lesson::find($lessonId);

            if (!$lesson)
                throw new \Exception('Lesson was not found');

            $lesson->title = $request->title;
            $lesson->description = $request->description;
            $lesson->note = $request->note;
            $lesson->save();

            session()->flash('banner','Lesson was update successfully');
            session()->flash('bannerStyle','success');

        }catch(\Exception $ex)
        {
            Log::error($ex);
            back()->with('error', $ex->getMessage());
        }
    }

    public function storeLesson($classroomId, Request $request)
    {
        $classroom = Classroom::find($classroomId);

        if (!$classroom)
            return back()->with('error', 'Classroom does not exist');

        try{
            $lesson = $classroom->lessons()->create([
                'title' => $request->title,
                'description' => $request->description,
                'note' => $request->note,
                'status' => $request->status
            ]);

            if ($request->file('attachments'))
            {
                foreach($request->file('attachments') as $file)
                {

                    $fileName = time() . $file->getClientOriginalName();
                    $path = 'downloads/'.$lesson->id.'/' . $fileName;
                    Storage::disk('s3')->put($path, file_get_contents($file), 'public');

                    $filePath = Storage::url( $path );
                    $fileType = $file->getMimeType();

                    $lesson->attachments()->create([
                        'file_path' => $filePath,
                        'file_name' => $fileName,
                        'file_type' => $fileType
                    ]);
                }
            }

            if ($lesson)
            {
                session()->flash('flash.banner', 'Lesson was added successfully');
                session()->flash('flash.bannerStyle', 'success');

                return redirect(route('classrooms.lessons', $lesson->classroom_id));
            }

            return back()->with('error', 'Lesson was not created. Please try again');
        }catch (\Exception $ex)
        {
            Log::error($ex);
            return back()->with('error', $ex->getMessage());
        }
    }

    public function downloadFile( $id )
    {
        try{
            $file = Attachment::find($id);

            if (!$file)
                throw new Exception('File was not found');

            $path = $file->file_path;
            if (!Storage::disk('s3')->exists($path)) {
               throw new \Exception('File was not found');
            }

            return Storage::download($path);
        }
        catch( \Exception $ex)
        {
            Log::error($ex);
            return back()->with('error', $ex->getMessage());
        }
    }
}
