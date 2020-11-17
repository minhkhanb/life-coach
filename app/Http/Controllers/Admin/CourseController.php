<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Doc2Txt;
use App\Model\Course;
use App\Model\CourseQuestion;
use App\Model\CourseStudent;
use App\Model\Questions;
use App\Model\User;
use App\Notifications\UserNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use PhpOffice\PhpWord\IOFactory;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $key_search = $request->search ? $request->search : "";
        $course = Course::query()->with([
            'courseQuestion',
            'courseStudent',
            'studentComplete'
        ])
            ->orderByDesc('id')
            ->where('name', 'like', '%' . $request->search . '%')
            ->paginate(10);
        $courseAll = Course::all();
        $students = $this->getStudents();
        return view('admin.course.index', compact('course', 'courseAll', 'students', 'key_search'));
    }

    public function getStudents()
    {
        $isCoach = Auth::user()->isCoach();
        return User::query()
            ->where([
                ['type', '=', User::TYPE_STUDENT],
                ['active', '=', User::ACTIVE_COMMON]
            ])
            ->when($isCoach, function($query){
                $query->where('user_owner', '=', Auth::user()->id);
            })
            ->get();
    }

    /**
     * gửi khóa học cho all học viên
     * @param Request $request
     */
    public function shareLesson(Request $request)
    {
        $students = $request->students;

        $course = Course::query()
            ->where('name', '=', $request->get('link_lesson'))
            ->first();

        if (empty($students)) {
            return redirect()->back()->with('error', 'Chưa có học viên!');
        }

        if ($course->courseQuestion->count() === 0) {
            return redirect()->back()->with('error', 'Bài giảng chưa có câu hỏi!');
        }

        foreach ($students as $student_id) {
            $modelCourseStudent = new CourseStudent();
            $modelCourseStudent->course_id = $course->id;
            $modelCourseStudent->student_id = $student_id;
            $modelCourseStudent->date_join = Carbon::now();
            $modelCourseStudent->status = CourseStudent::STATUS_INPROGRESS;
            $modelCourseStudent->send_by_id = Auth::user()->id;

            if (!$modelCourseStudent->save()) {
                return redirect()->back()->with('error', 'Gửi bài không thành công!');
            }
            $studentModel = User::find($student_id);
            $link = $request->getHttpHost() . '/learning/' . $modelCourseStudent->id . '/lesson/' . $course->slug;
            sleep(0.5);
            Notification::send($studentModel, new UserNotification([
                'link_lesson' => $link,
                'type' => User::NOTI_SHARE_LESSON
            ]));
        }
        return redirect()->back()->with('success', 'Gửi bài thành công!');
    }

    /**
     * show form create
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.course.create');
    }

    /**
     * show form edit
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('admin.course.edit', compact('course'));
    }

    /**
     * validate common
     * @param $request
     * @param null $id
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validateRequest($request, $id = null)
    {
        $rules = [
            'name' => ['required'],
            'price' => 'min:0',
            'open_at' => ['required', 'after_or_equal:to'],
            'expected_end_date' => ['required', 'after:open_at'],
            'file' => ['file', 'mimes: jpg,jpeg,png,svg']
        ];
        if ($id === '') {
            $rules['name'][] = 'unique:course';
        }
        $message = [
            'name.required' => 'Tiêu đề bài giảng không được trống!',
            'name.unique' => 'Tiêu đề đã tồn tại!',
            'file.mimes' => 'Hình ảnh phải định dạng (jpg,jpeg,png,svg)!',
            'price.min' => 'Đơn giá chỉ nhận giá trị dương!',
            'open_at.required' => 'Thời gian mở không được trống!',
            'open_at.after_or_equal' => 'Thời gian mở phải bắt đầu từ ngày hiện tại!',
            'expected_end_date.required' => 'Vui lòng nhập ngày dự kiến kết thúc!',
            'expected_end_date.after' => 'Ngày dự kiến phải sau ngày mở!'
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        return $validator;
    }

    /**
     * Tạo bài giảng mới
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = $this->validateRequest($request);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            if (!Course::createOrUpdate($request->all())) {
                return redirect()->back()->withErrors(['error' => 'Xảy ra lỗi tạo bài giảng!']);
            }
            return redirect()->route('lesson.index')->with('success', 'Tạo bài giảng thành công!');
        } catch (\Exception $exception) {
            return redirect()->route('lesson.create')->with('error', $exception->getMessage());
        }
    }

    /**
     * Xóa bài giảng
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $course = Course::findOrFail($id);
            if (!$course->delete()) {
                return redirect()->route('lesson.index')->with('error', 'Lỗi xóa bài giảng');
            }
            CourseQuestion::query()
                ->where('course_id', $id)->delete();
            DB::commit();
            return redirect()->route('lesson.index')->with('success', 'Xóa thành công');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->route('lesson.index')->with('error', $exception->getMessage());
        }
    }

    /**
     * Update bài giảng
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validator = $this->validateRequest($request);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            if (!Course::createOrUpdate($request->all(), $id)) {
                return redirect()->back()->withErrors(['error' => 'Xảy ra lỗi sửa bài giảng!']);
            }
            return redirect()->route('lesson.index')->with('success', 'Sửa bài giảng thành công!');
        } catch (\Exception $exception) {
            return redirect()->route('lesson.edit', $id)->with('error', $exception->getMessage());
        }
    }

    /**
     * show chi tiết
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail($slug)
    {
        $detail = Course::query()->where('slug', '=', $slug)->first();
        return view('admin.course.detail', compact('detail'));
    }

    public function downloadTemplate()
    {
        $path_file = public_path('/theme_admin/fileUpload.xlsx');
        if (file_exists($path_file)) {
            return response()->download($path_file, 'templateImport.xlsx');
        }
        return 'File mẫu không tồn tại!';
    }

    public function downloadTemplateWord()
    {
        $path_file = public_path('/theme_admin/fileword.docx');
        if (file_exists($path_file)) {
            return response()->download($path_file, 'template_tu_luan.docx');
        }
        return 'File mẫu không tồn tại!';
    }

    public function downloadTemplateWordTwo()
    {
        $path_file = public_path('/theme_admin/template_tracnghiem.docx');
        if (file_exists($path_file)) {
            return response()->download($path_file, 'template_trac_nghiem.docx');
        }
        return 'File mẫu không tồn tại!';
    }

    public function import(Request $request)
    {
        $courseId = $request->get('course');
        $extension = null;
        if (isset($request->file)) {
            $extension = explode('.', $request->file->getClientOriginalName());
        }
        $ext = is_array($extension) ? $extension[1] : '';
        if (in_array($ext, ['docx', 'doc'])) {
            $readFile = $this->readFileWord($request->file, $courseId);
            if ($readFile['error'] === true) {
                return redirect()->route('lesson.index')->with('error_import', $readFile['message']);
            }
            return redirect()->route('lesson.index')->with('success', $readFile['message']);
        }
        $validator = \Illuminate\Support\Facades\Validator::make([
            'file' => !empty($request->file('file')) ? $request->file : '',
            'extension' => $ext,
            'course' => $courseId
        ], [
            'file' => 'required',
            'extension' => 'in:xlsx,xls',
            'course' => 'required'
        ], [
            'file.required' => 'Vui lòng chọn file tải lên!',
            'course.required' => 'Vui lòng chọn bài giảng!',
            'extension.in' => 'File tải lên không đúng định dạng excel!',
        ]);
        if ($validator->fails()) {
            return redirect()->route('lesson.index')->withErrors($validator->errors(), 'import_question')->withInput();
        }

        if ($ext == 'csv') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } elseif ($ext == 'xlsx') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        }

        $path = $request->file->getRealPath();
        $spreadsheet = $reader->load($path);
        $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        if ($spreadsheet->getSheetCount() > 1) {
            return redirect()->route('lesson.index')->withErrors(['error_import', 'File excel chỉ giới hạn 1 Sheet']);
        }
        $data = array_values($data);
        if (count($data) > 0) {
            $headerExcel = array_values($data[0]);
            if (
                $headerExcel[0] !== "title" ||
                $headerExcel[1] !== "type" ||
                $headerExcel[2] !== "answer_correct" ||
                ($headerExcel[3]) !== "A" ||
                ($headerExcel[4]) !== "B" ||
                ($headerExcel[5]) !== "C" ||
                ($headerExcel[6]) !== "D"

            ) {
                return redirect()->route('lesson.index')->with('error_import', "Form excel không đúng đinh dạng. Vui lòng xem lại tiêu đề các cột!");
            }

            $rules = [
                'title' => [
                    'required',
                    Rule::unique('questions', 'title')
                ],
                'type' => [
                    'required'
                ]
            ];

            $messages = [
                'title.required' => ':attribute không được để trống!',
                'title.unique' => ':attribute câu hỏi đã tồn tại trong hệ thống!',
                'type.required' => ':attribute không được để trống!',
            ];

            $dataSheet = array_slice($data, 1);
            $dataNew = [];
            foreach ($dataSheet as $item) {
                $dataNew[] = [
                    $headerExcel[0] => $item['A'],
                    $headerExcel[1] => $item['B'],
                    $headerExcel[2] => $item['C'],
                    $headerExcel[3] => $item['D'],
                    $headerExcel[4] => $item['E'],
                    $headerExcel[5] => $item['F'],
                    $headerExcel[6] => $item['G']
                ];
            }

            $dataInsert = [];
            foreach ($dataNew as $key => $value) {
                $validateData = [
                    'title' => trim($value['title']),
                    'type' => trim($value['type'])
                ];
                $attributes["title"] = sprintf('Cột `title` dòng %s', $key + 2);
                $attributes["type"] = sprintf('Cột `type` dòng %s', $key + 2);

                $validator = Validator::make($validateData, $rules, $messages, $attributes);
                if ($validator->fails()) {
                    $errors = $validator->errors();
                    return redirect()->route('lesson.index')->withErrors($errors, 'import_question')->withInput();
                }
                $dataInsert[] = $value;
            }

            DB::beginTransaction();
            try {
                foreach ($dataInsert as $value) {
                    $question = new Questions();
                    $question->title = $value['title'];
                    $question->slug = Str::slug($value['title']);
                    $question->type = strtolower($value['type']) === 'trắc nghiệm' ?
                        Questions::TYPE_MULTI_CHOICE :
                        Questions::TYPE_ONE_CHOICE;
                    $question->answer_correct = $value['answer_correct'];
                    $question->answers = json_encode([
                        'A' => $value['A'],
                        'B' => $value['B'],
                        'C' => $value['C'],
                        'D' => $value['D']
                    ]);
                    $question->created_at = Carbon::now();
                    if (!$question->save()) {
                        DB::rollBack();
                        return redirect()->route('lesson.index')->withErrors(['error_import' => 'Lỗi lưu dữ liệu!']);
                    }
                    $courseQuestionModel = new CourseQuestion();
                    $courseQuestionModel->course_id = $courseId;
                    $courseQuestionModel->question_id = $question->id;
                    if (!$courseQuestionModel->save()) {
                        DB::rollBack();
                        return redirect()->route('lesson.index')->withErrors(['error_import' => 'Lỗi lưu dữ liệu!']);
                    }
                }
//                $file = $request->file('file');
//                Questions::uploadFileQuestion($file);
                DB::commit();
                return redirect()->route('lesson.index')->with('success', 'Upload file dữ liệu thành công');
            } catch (\Exception $exception) {
                DB::rollBack();
                return redirect()->route('lesson.index')->with('error_import', 'Lỗi import dữ liệu:' . $exception->getMessage());
            }
        } else {
            return redirect()->route('lesson.index')->with('error_import', 'File excel không có dữ liệu!');
        }
    }

    public function readFileWord($file, $courseId)
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        if (!file_exists('storage/questions')) {
            mkdir("storage/questions", 0777, true);
        }
        Storage::putFileAs('/storage/questions/', $file, $fileName);
        $path = public_path() . '/storage/questions/' . $fileName;
        $doc2Txt = new Doc2Txt($path);
        $docText = $doc2Txt->convertToText();
        if (isset($docText['error'])) {
            return $docText;
        }
        $outText = explode('?', trim($docText));
        $arrType = explode('Câu 1', $docText);
        $arrType = explode(':', $arrType[0]);
        if (count($arrType) === 0 || $arrType[1] === "") {
            return ['error' => true, 'message' => 'Không phân biệt được loại câu hỏi!'];
        }
        $textType = strtolower(trim($arrType[1]));
        $findIndex = strpos($outText[0], 'Câu');
        $outText[0] = substr($outText[0], $findIndex, strlen($outText[0]));
        $order = array("\r\n", "\n", "\r");
        $data = [];
        if ($textType === 'trắc nghiệm') {
            $outText = explode('Câu', trim($docText));
            $outText = array_slice($outText, 1);
            foreach ($outText as $index => $text) {
                $text = str_replace($order, "", $text);
                $arrText = explode('?', $text);
                $arrAnswer = explode('Đáp án', $arrText[1]);
                $answerCorrect = explode(':', $arrAnswer[1]);
                $dsDapAn = array_slice($arrAnswer, 2);
                $dataDsDapAn = [];
                foreach ($dsDapAn as $vl) {
                    $arrEx = explode(':', $vl);
                    $dataDsDapAn[$arrEx[0]] = $arrEx[1];
                }

                $data[] = [
                    'title' => $arrText[0],
                    'answer_correct' => isset($answerCorrect[1]) ? $answerCorrect[1] : null,
                    'answers' => json_encode($dataDsDapAn),
                    'type' => Questions::TYPE_MULTI_CHOICE,
                ];
            }
        } else {
            foreach ($outText as $text) {
                $text = str_replace($order, "", $text);
                $strIndex = strpos($text, '.');
                $title = substr($text, $strIndex + 1, strlen($text));
                $data[] = [
                    'title' => $title,
                    'answer_correct' => null,
                    'type' => Questions::TYPE_ONE_CHOICE,
                    'answers' => null
                ];
            }
        }
        DB::beginTransaction();
        try {
            if (count($data) > 0) {
                foreach ($data as $item) {
                    if ($item['title'] !== "" && $item['title'] !== false) {
                        $queryCheck = Questions::query()
                            ->leftJoin('course_question', 'course_question.question_id', '=', 'questions.id')
                            ->where('questions.title', '=', $item['title'])
                            ->where('course_question.course_id', '=', $courseId)
                            ->where('questions.type', '=', $item['type']);
                        if ($queryCheck->exists()) {
                            $questionModel = Questions::query()->where('title', '=', $item['title'])->first();
                        } else {
                            $questionModel = new Questions();
                        }
                        $questionModel->title = $item['title'];
                        $questionModel->slug = Str::slug($item['title']);
                        $questionModel->type = $item['type'];
                        $questionModel->created_at = Carbon::now();
                        $questionModel->answers = $item['answers'];
                        if (!$questionModel->save()) {
                            DB::rollBack();
                            return ['error' => true, 'message' => 'Xảy ra lỗi lưu dữ liệu!'];
                        }

                        $courseQuestionModel = new CourseQuestion();
                        $courseQuestionModel->course_id = $courseId;
                        $courseQuestionModel->question_id = $questionModel->id;
                        if (!$courseQuestionModel->save()) {
                            DB::rollBack();
                            return ['error' => true, 'message' => 'Xảy ra lỗi lưu dữ liệu!'];
                        }
                    }
                }
            } else {
                return ['error' => true, 'message' => 'File không đúng định dạng hoặc không đúng dữ liệu'];
            }

            DB::commit();
            return ['error' => false, 'message' => 'Thành công!'];
        } catch (\Exception $exception) {
            DB::rollBack();
            return ['error' => true, 'message' => $exception->getMessage()];
        }
    }
}
