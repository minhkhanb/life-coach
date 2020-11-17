<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Model\User;

class CourseStudent extends Model
{
    const STATUS_INPROGRESS = 0;
    const STATUS_COMPLETE = 1; // TH: hv đã nộp bài -> chưa đánh giá
    const STATUS_CANCEL = 2; // đã đánh giá
    protected $table = 'course_student';

    public function course()
    {
        return $this->belongsTo('App\Model\Course', 'course_id', 'id');
    }

    public static function countLessonNeedReview()
    {
        $isCoach = Auth::user()->isCoach();
        $dataStudent = null;
        
        $query = CourseStudent::query()
            ->select('course_student.*')
            ->leftJoin('users', 'users.id', '=', 'course_student.student_id')
            ->where([
                ['course_student.status', '=', CourseStudent::STATUS_COMPLETE]
            ])
            ->when($isCoach, function($query){
                // $query->where('course_student.send_by_id', '=', Auth::user()->id);
                $query->where('users.user_owner', '=', Auth::user()->id);
            });
        
        return $query;
    }

    public static function queryLessonStudent()
    {
        return CourseStudent::query()
            ->where([
                ['course_student.student_id', '=', Auth::user()->id],
            ]);
    }

    public static function renderStatusToText($status)
    {
        if ($status === self::STATUS_COMPLETE) {
            return '<span class="badge badge-info">Đã nộp bài</span>';
        }
        if ($status === self::STATUS_CANCEL) {
            return '<span class="badge badge-success">Đã hoàn thành đánh giá</span>';
        }
        return '<span class="badge badge-danger">Chưa làm bài</span>';
    }
}
