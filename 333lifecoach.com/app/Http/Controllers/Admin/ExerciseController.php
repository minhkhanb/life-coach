<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Course;
use App\Model\CourseQuestion;
use App\Model\QuestionDetail;
use App\Model\Questions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use MongoDB\Driver\Query;
use Symfony\Component\Console\Question\Question;

class ExerciseController extends Controller
{

    public function index(Request $request)
    {
        $key_search = $request->search ? $request->search : "";
        $paramSearch = [
            'title' => $key_search,
            'course_id' => $request->get('course_id')
        ];
        $course = Course::all();
        $query = Questions::query();
        $questions = $query
            ->select([
                'questions.*',
                'course.name',
                'course.slug'
            ])
            ->leftJoin('course_question', 'questions.id', '=', 'course_question.question_id')
            ->leftJoin('course', 'course.id', '=', 'course_question.course_id')
            ->where('title', 'like', '%' . $request->search . '%')
            ->orderByDesc('created_at')
            ->when($request->get('course_id'), function ($query, $courseId) {
                $query->where('course.id', '=', $courseId);
            })
            ->get();
        return view('admin.question.index', compact('questions', 'paramSearch', 'course'));
    }

    public function createMultiChoice(Request $request)
    {
        $course = $this->getCourseList();
        $type = Questions::TYPE_MULTI_CHOICE;
        return view('admin.question.create', compact('course', 'type'));
    }

    public function createOneChoice(Request $request)
    {
        $course = $this->getCourseList();
        $type = Questions::TYPE_ONE_CHOICE;
        return view('admin.question.create', compact('course', 'type'));
    }

    public function getCourseList()
    {
        return Course::all();
    }

    public function validateRequest($request, $id = null)
    {
        $isMultiChoice = (int)$request->get('type') === Questions::TYPE_MULTI_CHOICE ?? false;
        $rules = [
            'course_id' => 'required',
            'title' => [
                'required'
            ],
            'answer_correct' => ['required']
        ];
        $messages = [
            'course_id.required' => 'Vui lòng chọn khóa học!',
            'title.required' => 'Vui lòng nhập tiêu đề câu hỏi!',
            'title.unique' => 'Tiêu đề câu hỏi đã tồn tại!',
            'answer_correct.required' => 'Vui lòng nhập nội dung!'
        ];
        $messageAnswer = [];
        if ($id !== null) {
            $rules['title'][] = Rule::unique('questions', 'title');
        }
        if (!$isMultiChoice) {
            $rules['answer_correct'] = ['required'];
            $messageAnswer["answer_correct"] = 'Vui lòng nhập nội dung!';
        }
        if ($isMultiChoice) {
            $answers = $request->get('answer');
            foreach ($answers as $index => $value) {
                if (empty($value)) {
                    $messageAnswer["answer[$index]"] = 'Vui lòng nhập nội dung câu hỏi!';
                };
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($isMultiChoice) {
                foreach ($messageAnswer as $key => $message) {
                    $errors->add($key, $message);
                }
            }
            return $errors;
        }
        return false;
    }

    public function store(Request $request, $id = null)
    {
        $validator = $this->validateRequest($request);
        if ($validator !== false) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();
        try {
            $question = Questions::updateOrCreate($request->all(), $id);
            $courseQuesId = null;
            if ($id !== null) {
                $courseQuesId = CourseQuestion::query()
                    ->where('id', $request->get('course_question_id'))
                    ->first()->id;
            }

            $paramCourseQuestion = [
                'course_id' => (int)$request->get('course_id'),
                'question_id' => $question->id,
            ];

            $model = CourseQuestion::updateOrCreate($paramCourseQuestion, $courseQuesId);
            if ($model === false) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Lỗi tạo câu hỏi');
            }
            $message = !empty($id) ? 'Chỉnh sửa thành công' : 'Tạo mới thành công';
            DB::commit();
            return redirect()->route('question.index')
                ->with('success', $message);
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        $course = $this->getCourseList();
        $detail = Questions::query()
            ->addSelect(['questions.*', 't2.name as course_name', 't1.course_id', 't1.id as course_question_id'])
            ->leftJoin('course_question as t1', 't1.question_id', '=', 'questions.id')
            ->leftJoin('course as t2', 't2.id', '=', 't1.course_id')
            ->where(['questions.id' => $id])
            ->first();

        if ($detail->type === Questions::TYPE_MULTI_CHOICE) {
            $detail->answers = array_values(json_decode($detail->answers, true));
        }
        $type = $detail->type;
        return view('admin.question.create', compact('course', 'detail', 'type'));
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $question = Questions::query()->where(['id' => $id])->first();
            CourseQuestion::query()->where(['question_id' => $id])->delete();
            $question->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Xóa thành công');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function deleteAll(Request $request)
    {
        $param = $request->all();
        if (isset($param['id'])) {
            Questions::query()->where('id', $param['id'])->delete();
            CourseQuestion::query()->where('question_id', $param['id'])->delete();
        } else {
            Questions::query()->delete();
            CourseQuestion::query()->delete();
        }
    }
}
