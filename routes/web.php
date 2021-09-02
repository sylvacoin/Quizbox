<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ChatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth:sanctum', 'verified'])->group(function (){
   Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::group(['prefix' => 'teachers'], function (){
        Route::get('', [AccountController::class, 'getTeachers'])->name('teachers.index');
        Route::get('upload-teachers', [AccountController::class, 'uploadTeachers'])->name('teachers.upload');
        Route::get('create-teachers', [AccountController::class, 'createTeacher'])->name('teachers.create');
        Route::post('upload-teachers', [AccountController::class, 'storeUploadedTeachers'])->name('teachers.save-upload');
        Route::post('create-teachers', [AccountController::class, 'storeTeacher'])->name('teachers.save');
        Route::post('{id}/block', [AccountController::class, 'blockTeacher'])->name('teachers.block');
    });

    Route::group(['prefix' => 'students'], function (){
        Route::get('', [AccountController::class, 'getStudents'])->name('students.index');
        Route::get('my-students', [AccountController::class, 'getTeacherStudents'])->name('teacher.students');
        Route::get('{id}', [AccountController::class, 'getStudentDetail'])->name('students.detail');
        Route::post('{id}/block', [AccountController::class, 'blockStudent'])->name('students.block');
    });

    Route::group(['prefix' => 'classrooms'], function (){
        Route::get('find', [ClassroomController::class, 'findClassrooms'])->name('classrooms.find');
        Route::get('my-classes', [ClassroomController::class, 'getStudentClassrooms'])->name('classrooms.my-classes');

        Route::get('', [ClassroomController::class, 'getClassrooms'])->name('classrooms.index')->middleware('role_or_permission:administrator');
        Route::get('my-classrooms', [ClassroomController::class, 'getTeacherClassrooms'])->name('teacher.classrooms')->middleware('role_or_permission:teacher');
        Route::get('create-classroom', [ClassroomController::class, 'createClassroom'])->name('classrooms.create')->middleware('role_or_permission:teacher');
        Route::post('create-classroom', [ClassroomController::class, 'storeClassroom'])->name('classrooms.save')->middleware('role_or_permission:teacher');
        Route::get('{id}', [ClassroomController::class, 'getClassroom'])->name('classrooms.show')->middleware('role_or_permission:teacher');
        Route::get('{id}/lessons', [LessonController::class, 'getLessons'])->name('classrooms.lessons')->middleware('role_or_permission:teacher');


        Route::post('{classroomId}/{lessonId}/start', [ClassroomController::class, 'startClass'])->name('classrooms.start')->middleware('role_or_permission:teacher');
        Route::post('{classroomId}/{lessonId}/stop', [ClassroomController::class, 'stopClass'])->name('classrooms.stop')->middleware('role_or_permission:teacher');
        Route::post('{classroomId}/subscribe', [ClassroomController::class, 'subscribeToClassroom'])->name('classrooms.subscribe');
        Route::post('{classroomId}/unsubscribe', [ClassroomController::class, 'unsubscribeFromClassroom'])->name('classrooms.unsubscribe');
        Route::get('attend/{classroomURL}', [ClassroomController::class, 'joinClassroom'])->name('classrooms.join');
    });

    Route::group(['prefix' => 'lessons'], function (){

//        Route::get('{classroomId}/upload-lessons', [LessonController::class, 'uploadLesson'])->name('lessons.upload');
        Route::get('{classroomId}/create-lesson', [LessonController::class, 'createLesson'])->name('lessons.create');
//        Route::post('{classroomId}/upload-lessons', [LessonController::class, 'storeUploadedLessons'])->name('lessons.save-upload');
        Route::post('{classroomId}/create-lesson', [LessonController::class, 'storeLesson'])->name('lessons.save');

        Route::get('{id}/detail', [LessonController::class, 'getLesson'])->name('lessons.show');
        Route::post('{id}/delete', [LessonController::class, 'deleteLesson'])->name('lessons.delete');
//        Route::get('{id}/edit', [LessonController::class, 'editLesson'])->name('lessons.edit');
//        Route::post('{id}/edit', [LessonController::class, 'updateLesson'])->name('lessons.update');
        Route::get('{id}/quiz', [QuizController::class, 'getQuizzes'])->name('quiz.index');
    });



    Route::group(['prefix' => 'quiz'], function (){
//        Route::get('{lessonId}/upload-quiz', [QuizController::class, 'uploadQuiz'])->name('quiz.upload');
        Route::get('{lessonId}/create-quiz', [QuizController::class, 'createQuiz'])->name('quiz.create');
//        Route::post('{lessonId}/upload-quiz', [QuizController::class, 'storeUploadedQuiz'])->name('quiz.save-upload');
        Route::post('{lessonId}/create-quiz', [QuizController::class, 'storeQuiz'])->name('quiz.save');
        Route::post('{id}/delete', [QuizController::class, 'deleteQuiz'])->name('quiz.delete');
//        Route::get('{id}/edit', [QuizController::class, 'editQuiz'])->name('quiz.edit');
//        Route::post('{id}/edit', [QuizController::class, 'updateQuiz'])->name('quiz.update');
    });

    Route::group(['prefix' => 'chat'], function (){
        Route::post('send', [ChatController::class, 'sendMessage'])->name('chat.create');
        Route::get('read/{room_url}', [ChatController::class, 'getLessonChats'])->name('chat.read');
    });
});

