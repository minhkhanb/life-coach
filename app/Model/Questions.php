<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Questions extends Model
{
    protected $table = 'questions';

    const PATH_UPLOAD = 'questions';
    const TYPE_MULTI_CHOICE = 1;
    const TYPE_ONE_CHOICE = 0;

    public static function uploadFileQuestion($file)
    {
        Storage::putFileAs(self::PATH_UPLOAD, $file, $file->getClientOriginalName());
    }

    public function getNameTypeAttribute()
    {
        $strType = $this->attributes['type'] === self::TYPE_MULTI_CHOICE ? 'Trắc nghiệm' : 'Tự luận';
        return "$strType";
    }

    public static function updateOrCreate($post, $id = null)
    {
        if ($id === null) {
            $question = new Questions();
        } else {
            $question = Questions::findOrFail($id);
            $question->updated_at = Carbon::now();
        }

        $question->title = $post['title'];
        $question->slug = Str::slug($post['title']);
        $question->type = $post['type'];
        $question->answer_correct = $post['answer_correct'];
        $question->answers = (int)$post['type'] === self::TYPE_ONE_CHOICE ? null : json_encode([
            'A' => $post['answer'][0],
            'B' => $post['answer'][1],
            'C' => $post['answer'][2],
            'D' => $post['answer'][3]
        ]);
        $question->save();
        return $question;
    }

}
