<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Course extends Model
{
    const IS_DELETE = 1;
    protected $dateFormat = "Y-m-d";
    const LINK_UPLOAD = '/storage/course/';
    protected $table = 'course';

    protected $casts = [
        'open_at' => 'datetime',
        'expected_at' => 'datetime',
        'expected_end_date' => 'datetime'
    ];

    public function getRenderImageAttribute()
    {
        $url = !empty($this->attributes['link_file']) ? $this->attributes['link_file'] : '/theme_admin/img/avatar.jpg';
        return "$url";
    }

    public function courseStudent()
    {
        return $this->hasMany('App\Model\CourseStudent', 'course_id', 'id');
    }

    public function courseQuestion()
    {
        return $this->hasMany('App\Model\CourseQuestion', 'course_id', 'id');
    }

    public function studentComplete()
    {
        return $this->hasMany('App\Model\CourseStudent', 'course_id', 'id')
            ->where('status', '<>', CourseStudent::STATUS_INPROGRESS);
    }

    public static function createOrUpdate($attributes = [], $id = null)
    {
        if (!empty($id)) {
            $course = Course::query()->where('id', '=', $id)->first();
        } else {
            $course = new Course();
        }
        $course->name = $attributes['name'];
        $course->slug = Str::slug($attributes['name']);
        $course->open_at = Carbon::parse($attributes['open_at'])->toDateTimeString();
        $course->expected_end_date = Carbon::parse($attributes['expected_end_date'])->toDateTimeString();
        $course->created_at = Carbon::now();
        $course->create_by = Auth::user()->id;
        if (isset($attributes['file'])) {
            // $attrFile = self::uploadFilte($attributes['file']);
            // $course->images = $attrFile['name'];
            // $course->type_file = $attrFile['extension'];
            // $course->link_file = $attrFile['link'];


            $file = $attributes['file'];
            $destinationPath = 'uploads/course';
            $file->move($destinationPath, $file->getClientOriginalName());
            $course->images = $file->getClientOriginalName();

        }
        return $course->save();
    }

    // public static function uploadFilte($file)
    // {
    //     $name = time() . $file->getClientOriginalName();
    //     $save = Storage::putFileAs(self::LINK_UPLOAD, $file, $name);
    //     if (!$save) {
    //         return false;
    //     }
    //     return [
    //         'name' => $name,
    //         'extension' => pathinfo($name)['extension'],
    //         'link' => config('app.url') . self::LINK_UPLOAD . $name
    //     ];
    // }
}
