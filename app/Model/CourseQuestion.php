<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CourseQuestion extends Model
{
    protected $table = 'course_question';

    public function course()
    {
        return $this->belongsTo('App\Model\Course', 'course_id', 'id');
    }

    public static function updateOrCreate($params, $id = null)
    {
        if ($id !== null) {
            $courseQuestion = CourseQuestion::query()->where('id', $id)->first();
        } else {
            $courseQuestion = new CourseQuestion();
        }
        $courseQuestion->course_id = $params['course_id'];
        $courseQuestion->question_id = $params['question_id'];
        if(!$courseQuestion->save()) {
            return false;
        }
        return $courseQuestion;
    }
}
