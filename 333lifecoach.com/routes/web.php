<?php

use Illuminate\Support\Facades\Route;

Route::get('login', 'Admin\LoginController@showLoginForm')->name('login')->middleware('RoleExpireSession');

// forgot pasword
Route::get('forgot-password', 'Admin\LoginController@forgotPassword')->name('forgotPassword')->middleware('RoleExpireSession');
Route::post('forgot-password', 'Admin\LoginController@sendMailPassword')->name('sendMailPassword')->middleware('RoleExpireSession');
Route::get('reset-password/{code}', 'Admin\LoginController@formResetPassword')->name('formResetPassword')->middleware('RoleExpireSession');
Route::post('resetPass', 'Admin\LoginController@resetPass')->name('resetPass')->middleware('RoleExpireSession');

// login
Route::post('login', 'Admin\LoginController@login')->name('login.store')->middleware('RoleExpireSession');
Route::get('register', 'Admin\RegisterController@showRegistrationForm')->name('register')->middleware('RoleExpireSession');
Route::post('register', 'Admin\RegisterController@register')->name('register.store')->middleware('RoleExpireSession');

Route::group(['middleware' => ['auth']], function () {
    // logout
    Route::get('/logout', 'Admin\LoginController@logout')->name('logout');

    Route::get('/upload-identify', 'Admin\DashboardController@showUploadIdentify')->name('showUploadIdentify');
    Route::post('/upload-identify', 'Admin\DashboardController@UploadIdentify')->name('UploadIdentify');
    /**
     * ['middleware' => ['CheckCoachActive']]
     */
    Route::group(['middleware' => 'CheckCoachActive'], function () {
        Route::get('/', 'Admin\HomeController@dashboard')->name('dashboard');

        // coach
        Route::group(['prefix' => 'coach', 'as' => 'coach.'], function () {
            Route::get('/register', 'Admin\DashboardController@showRegisterCoach')->name('register')->middleware('checkAdmin');
            Route::post('/register', 'Admin\DashboardController@registerCoach')->name('register')->middleware('checkAdmin');
            Route::get('', 'Admin\DashboardController@showListCoach')->name('index')->middleware('checkAdmin');
            Route::get('/get-coach/{userId}', 'Admin\DashboardController@getCoach')->name('getCoach')->middleware('checkAdmin');
            Route::post('/update/{userId}', 'Admin\DashboardController@updateCoach')->name('update')->middleware('checkAdmin');
            Route::post('/delete-request/{userId}', 'Admin\DashboardController@deleteRequestCoach')->name('deleteRequestCoach')->middleware('checkAdmin');
            Route::post('/delete/{userId}', 'Admin\DashboardController@deleteCoach')->name('deleteCoach')->middleware('checkAdmin');
            // Coach link affiliate
            Route::get('/coach-affiliate/{userId}', 'Admin\DashboardController@getAffiliate')->name('getAffiliate')->middleware('checkCoach');
            Route::post('/create-affiliate', 'Admin\DashboardController@createAffiliate')->name('createAffiliate')->middleware('checkCoach');

        });

        /**
         * quan ly user
         */
        Route::get('/user', 'Admin\UserController@index')->name('user.index');
        Route::get('/user/profile/{id}', 'Admin\UserController@profile')->name('user.profile');
        Route::post('/user/profile/{id}', 'Admin\UserController@updateProfile')->name('user.update.profile');
        Route::post('/user/changePassword/{id}', 'Admin\UserController@updatePassword')->name('user.updatePassword');
        Route::get('/user/register-coach/{id}', 'Admin\UserController@registerCoach')->name('user.registerCoach')->middleware('checkStudent');
        Route::get('/user/verify-coach/{id}', 'Admin\UserController@verifyCoach')->name('user.verifyCoach')->middleware('checkAdmin');
        Route::post('/user/verify-coach/{id}', 'Admin\UserController@confirmVerifyCoach')->name('user.confirmVerifyCoach')->middleware('checkAdmin');
        Route::post('/user/delete-verify-coach/{id}', 'Admin\UserController@deleteVerifyCoach')->name('user.deleteVerifyCoach')->middleware('checkAdmin');


        // Quan lý học viên
        Route::group(['prefix' => 'student', 'as' => 'student.'], function () {
            Route::get('/', 'Admin\StudentController@showListStudent')->name('index')->middleware('RoleAdminCoach');
            Route::get('/{id}', 'Admin\StudentController@detailStudent')->name('detailStudent')->middleware('RoleAdminCoach');
            Route::post('/{id}', 'Admin\StudentController@updateCoachStudent')->name('updateCoachStudent')->middleware('checkAdmin');
            Route::post('/delete/student/{id}', 'Admin\StudentController@deleteStudent')->name('deleteStudent')->middleware('checkAdmin');
            Route::get("download/email","Admin\StudentController@exportExcelEmail")->name("downloadExcel");
            Route::post('/delete-lesson/{id}','Admin\StudentController@deleteLesson')->name("delete.lesson");
        });

        /**
         * quản lý khóa học
         */
        Route::group(['prefix' => 'lesson', 'as' => 'lesson.'], function () {
            Route::get('', 'Admin\CourseController@index')->name('index')->middleware('RoleAdminCoach');
            Route::get('create', 'Admin\CourseController@create')->name('create')->middleware('checkAdmin');
            Route::post('create', 'Admin\CourseController@store')->name('store')->middleware('checkAdmin');
            Route::get('edit/{id}', 'Admin\CourseController@edit')->name('edit')->where('id', '[0-9]+')->middleware('checkAdmin');
            Route::post('edit/{id}', 'Admin\CourseController@update')->name('update')->where('id', '[0-9]+')->middleware('checkAdmin');
            Route::post('delete/{id}', 'Admin\CourseController@delete')->name('delete')->where('id', '[0-9]+')->middleware('checkAdmin');
            Route::post('import', 'Admin\CourseController@import')->name('import')->middleware('checkAdmin');
            Route::get('detail/{slug}', 'Admin\CourseController@detail')->name('detail')->middleware('checkAdmin');
            Route::get('download-template', 'Admin\CourseController@downloadTemplate')->name('downloadTemplate')->middleware('checkAdmin');
            Route::post('share', 'Admin\CourseController@shareLesson')->name('share');
            Route::get('download-template-word','Admin\CourseController@downloadTemplateWord')->name('downloadTemplateWord');
            Route::get('download-template-word-2','Admin\CourseController@downloadTemplateWordTwo')->name('downloadTemplateWordTwo');
        });

        /**
         * quản lý bài tập
         */
        Route::group(['prefix' => 'question', 'as' => 'question.'], function () {
            Route::get('delete-all','Admin\ExerciseController@deleteAll');
            Route::get('', 'Admin\ExerciseController@index')->name('index')->middleware('RoleAdminCoach');
            Route::get('create/multi-choice', 'Admin\ExerciseController@createMultiChoice')->name('createMultiChoice')->middleware('checkAdmin');
            Route::get('create/one-choice', 'Admin\ExerciseController@createOneChoice')->name('createOneChoice')->middleware('checkAdmin');
            Route::post('store/{id?}', 'Admin\ExerciseController@store')->name('store')->middleware('checkAdmin');
            Route::get('edit/{id}', 'Admin\ExerciseController@edit')->name('edit')->middleware('checkAdmin');
            Route::post('delete/{slug}', 'Admin\ExerciseController@delete')->name('delete')->middleware('checkAdmin');
        });
    });
    /**
     * Xem tình trạng học của học viên
     */

    Route::group(['middleware' => 'isStudent'], function () {
        Route::group(['prefix' => 'learning', 'as' => 'learning.'], function () {
            Route::get('lesson/complete', 'Admin\LearningController@complete')->name('student.complete');
            Route::get('lesson/in-progress', 'Admin\LearningController@inProgress')->name('student.inProgress');
            // id: table course_student
            Route::get('lesson/{id}/{slug}', 'Admin\LearningController@showQuestions')->name('student.showQuestions');
            Route::post('lesson/doing/{courseId}', 'Admin\LearningController@saveLearnCourse')->name('student.saveLearnCourse');
            Route::get('export/{id}/{slug}', 'Admin\LearningController@showQuestions')->name('student.exportLesson');
            Route::post('import', 'Admin\LearningController@importLesson')->name('student.import');

        });
    });

    Route::group(['middleware' => 'RoleAdminCoach'], function () {
        Route::get('delete-all','Admin\LearningController@deleteAll');
        Route::get('coach/review', 'Admin\LearningController@showNeedReview')->name('learning.needReview');
        Route::get('coach/review/{id}/{slug}', 'Admin\LearningController@showQuestions')->name('learning.detailReview');
        Route::post('coach/review/{course_student_id}', 'Admin\LearningController@storeCompleteReviewCourse')->name('learning.storeCompleteCourse');
    });
});

Route::get('register/verify/{code}', 'Admin\RegisterController@verify');
