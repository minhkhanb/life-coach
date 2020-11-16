<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Course;
use App\Model\CourseQuestion;
use App\Model\CourseStudent;
use App\Model\CourseStudentDetail;
use App\Model\Questions;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use function Composer\Autoload\includeFile;

class LearningController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getDataCourseDoing($type)
    {
        $queryData = CourseStudent::query()
            ->leftJoin('course', 'course.id', '=', 'course_student.course_id')
            ->where([
                ['course_student.student_id', '=', Auth::user()->id]
            ])
            ->when($type, function ($query) use ($type) {
                $query->whereIn('course_student.status', $type);
            })
            ->select('course.*',
                'course_student.id as course_student_id',
                'course_student.created_at as ct_created_at',
                'course_student.detail_rate',
                'course_student.status', 'course_student.rate', 'course_student.student_id')
            ->paginate(10);
        return $queryData;
    }

    /**
     * kiểm tra xem
     * @return array
     */
    public function getDataCourseWorked()
    {
        $queryCourseDone = CourseStudentDetail::query()
            ->select('course_student_id')
            ->groupBy('course_student_id')
            ->get()
            ->toArray();
        $courseIdExists = array_map(function ($item) {
            return $item['course_student_id'];
        }, $queryCourseDone);
        return $courseIdExists;
    }

    public function complete(Request $request)
    {
        $data = $this->getDataCourseDoing([CourseStudent::STATUS_COMPLETE]);
        return view('admin.learning.index', compact('data'));
    }

    public function inProgress(Request $request)
    {
        $status = $request->get('status');
        $arrStatus = [];
        if (!empty($status)) {
            $arrStatus = [
                $status
            ];
        } else {
            $arrStatus = [
                CourseStudent::STATUS_INPROGRESS,
                CourseStudent::STATUS_COMPLETE,
                CourseStudent::STATUS_CANCEL
            ];
        }
        $data = $this->getDataCourseDoing($arrStatus);
        $search = ['status' => $status];
        return view('admin.learning.index', compact('data','search'));
    }

    /**
     * query show question of course
     * @param $courseId
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function queryQuestionOfCourse($courseId)
    {
        return Questions::query()
            ->leftJoin('course_question', 'course_question.question_id', '=', 'questions.id')
            ->where([
                ['course_question.course_id', '=', $courseId],
            ])
            ->orderByDesc('questions.created_at')
            ->select(['questions.*'])
            ->get();
    }

    public function getQuestionOfStudent($courseStudentId)
    {
        return CourseStudentDetail::query()
            ->leftJoin('course_student', 'course_student.course_id', '=', 'course_student_detail.course_student_id')
            ->leftJoin('questions', 'questions.id', '=', 'course_student_detail.question_id')
            ->where('course_student_detail.course_student_id', $courseStudentId)
            ->select(['questions.*', 'course_student.id as course_student_id'])
            ->orderBy('questions.type', 'DESC')
            ->get();
    }

    /**
     * show form lam bai tap
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showQuestions(Request $request, $courseStudentId, $slug)
    {
        $isCoach = Auth::user()->isCoach();
        $isAdmin = Auth::user()->isAdmin();
        $courseInfo = CourseStudent::query()
            ->select('course.*',
                'course_student.status',
                'course_student.student_id',
                'users.name as student_name',
                'course_student.review',
                'course_student.id as course_student_id')
            ->leftJoin('course', 'course_student.course_id', '=', 'course.id')
            ->leftJoin('users', 'users.id','=', 'course_student.student_id')
            ->where([
                ['course.slug', '=', $slug],
                ['course_student.id', '=', $courseStudentId]
            ])
            ->first();
        $details = CourseStudentDetail::query()
            ->where('course_student_id', $courseStudentId)
            ->get();
        $arrayQuestionOfStudent = [];
        foreach ($details as $item) {
            $arrayQuestionOfStudent[] = $item->question_id;
        }
        // TH coach đánh giá kết quả khóa học
        if ($isCoach || $isAdmin) {
            $questions = $this->getQuestionOfStudent($courseStudentId);
            return view('admin.learning.reviewAction', compact(
                'courseInfo',
                'questions',
                'details',
                'isCoach',
                'arrayQuestionOfStudent'));
        }
        $questions = $this->queryQuestionOfCourse($courseInfo->id);

        // Export
        if ($request->has('is_export')) {
            $path = $this->exportLessonToExcel($questions, $courseStudentId);
            return response()->download($path, 'Bài tập của bạn.xlsx');
        }

        return view('admin.learning.learning', compact(
            'courseInfo',
            'questions',
            'details',
            'arrayQuestionOfStudent',
            'isCoach'));
    }


    /**
     * show list danh sach khoa hoc can danh gia cua coach
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showNeedReview(Request $request)
    {
        $status = $request->get('status');
        $arrType = [CourseStudent::STATUS_CANCEL, CourseStudent::STATUS_COMPLETE];
        if (!empty($status)) {
            $arrType = [$status];
        }
        $isCoach = Auth::user()->isCoach();
        $data = CourseStudent::query()
            ->select([
                'course.*',
                'users.email',
                'users.name as name_user',
                'course_student.status',
                'course_student.rate',
                'course_student.student_id',
                'course_student.detail_rate',
                'course_student.id as course_student_id',
            ])
            ->leftJoin('course', 'course.id', '=', 'course_student.course_id')
            ->leftJoin('users', 'users.id', '=', 'course_student.student_id')
            ->whereIn('course_student.status', $arrType)
            ->when($isCoach, function($query){
                // $query->where('course_student.send_by_id', '=', Auth::user()->id);
                 $query->where('users.user_owner', '=', Auth::user()->id);
            })
            ->orderByDesc('course_student.updated_at')
            ->paginate(10);
        $search = [
            'status' => $status
        ];
        return view('admin.learning.listReview', compact('data', 'search'));
    }

    public function storeCompleteReviewCourse(Request $request, $course_student_id)
    {
        $totalTrue = 0;
        $total_tn = $total_tl = 0;
        if (!empty($request->get('right'))) {
            $totalTrue += count($request->get('right'));
            $total_tn = count($request->get('right'));
        }
        if (!empty($request->get('accept_answer'))) {
            $totalTrue += count($request->get('accept_answer'));
            $total_tl = count($request->get('accept_answer'));
        }
        $review_rate_detail = [
            'tl' => $total_tl . '/' . $request->get('total_tl'),
            'tn' => $total_tn . '/' . $request->get('total_tn')
        ];
        $totalQuestion = $this->getQuestionOfStudent($course_student_id)->count();
        $modelCourseStudent = CourseStudent::query()
            ->where('id', $course_student_id)
            ->first();
        DB::beginTransaction();
        try {
            $rate = 0;
            if ($totalQuestion > 0) {
                $rate = round(($totalTrue / $totalQuestion) * 100, 2);
            }
            $modelCourseStudent->rate = $rate;
            $modelCourseStudent->review = $request->get('review_note');
            $modelCourseStudent->status = CourseStudent::STATUS_CANCEL;
            $modelCourseStudent->detail_rate = json_encode($review_rate_detail);
            $modelCourseStudent->updated_at = Carbon::now();
            $modelCourseStudent->save();

            DB::commit();
            return redirect()->route('learning.needReview')->with('success', 'Hoàn thành đánh giá');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function saveLearnCourse(Request $request, $courseStudentId)
    {
        DB::beginTransaction();
        try {
            $rules = [];
            $messages = [];
            $input = $request->all();
            foreach($input['answer'] as $key => $val)
            {
                $rules['answer.'.$key] = 'required';
                $messages['answer.'. $key.'.required'] = 'Vui lòng nhập câu trả lời!';
            }

            $validator = Validator::make($input, $rules, $messages);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            // update status table course_student
            $modelCourseStudent = CourseStudent::findOrFail($courseStudentId);
            $modelCourseStudent->status = CourseStudent::STATUS_COMPLETE;
            $modelCourseStudent->updated_at = Carbon::now();
            $modelCourseStudent->save();
            foreach ($request->get('answer') as $questionId => $answer) {
                $model = new CourseStudentDetail();
                $model->course_student_id = $modelCourseStudent->id;
                $model->question_id = $questionId;
                $model->answer = is_array($answer) ? $answer[0] : $answer;
                $model->save();
            }

            DB::commit();
            return redirect()->route('learning.student.inProgress')->with('success', 'Bạn đã hoàn thành khóa học!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function exportLessonToExcel($questions, $courseStudentId)
    {
        $file_name = 'Danh sách câu hỏi';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Mã đề');
        $sheet->setCellValue('B1', $courseStudentId);
        $sheet->setCellValue('A2', 'Họ tên');
        $sheet->setCellValue('B2', Auth::user()->name);

        $start = 3;
        $sheet->setCellValue("A$start", 'STT');
        $sheet->setCellValue("B$start", 'Mã câu hỏi');
        $sheet->setCellValue("C$start", 'Loại câu hỏi');
        $sheet->setCellValue("D$start", 'Câu hỏi');
        $sheet->setCellValue("E$start", 'Đáp án lựa chọn');
        $sheet->setCellValue("F$start", 'A');
        $sheet->setCellValue("G$start", 'B');
        $sheet->setCellValue("H$start", 'C');
        $sheet->setCellValue("I$start", 'D');

        $start = 4;
        $rang_tmp = range('F', 'I');
        foreach ($questions as $index => $item) {
            $type = $item->type === Questions::TYPE_ONE_CHOICE ? 'Tự luận' : 'Trắc nghiệm';
            $sheet->setCellValue('A' . $start, ++$index);
            $sheet->setCellValue('B' . $start, $item->id);
            $sheet->setCellValue('C' . $start, $type);
            $sheet->setCellValue('D' . $start, htmlspecialchars_decode($item->title, ENT_HTML5));
            $sheet->setCellValue('E' . $start, '');
            if ($item->type === Questions::TYPE_MULTI_CHOICE) {
                $i = 0;
                foreach (json_decode($item->answers) as $key_ans => $val_ans) {
                    $sheet->setCellValue($rang_tmp[$i] . $start, $val_ans);
                    $i++;
                }
            }
            $start++;
        }
        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => array('argb' => '333333'),
                ),
            ),
        );
        $styleColor = [
            'fill' => array(
                'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => '333333')
            )
        ];

        $start = $start - 1;
        $sheet->getStyle('B1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e4e6e8');
        $sheet->getStyle('B2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e4e6e8');
        $sheet->getStyle('A3:I3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('1e7d33');

        $sheet->getStyle("A3:I$start")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(50);
        $sheet->getColumnDimension('E')->setWidth(50);
$sheet->getStyle('A4:Z999')->getAlignment()->setWrapText(true);
$sheet->getRowDimension(1)->setRowHeight(-1);

        $start = ($start + 2);
        $sheet->mergeCells("A$start:I$start")->setCellValue("A$start", 'Bạn chỉ nhập đáp án!');
        $sheet->getStyle("A$start")
            ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
        $writer = new Xlsx($spreadsheet);
        $id_user = Auth::user()->id;
        if (!file_exists("storage/lesson_$id_user")) {
            mkdir("storage/lesson_$id_user", 0777, true);
        }
        $writer->save("storage/lesson_$id_user/" . $file_name . "xlsx");
        return "storage/lesson_$id_user/" . $file_name . "xlsx";
    }

    public function importLesson(Request $request)
    {
        $extension = explode('.', $request->hasFile('file') ? $request->file('file')->getClientOriginalName() : null);

        $validator = \Illuminate\Support\Facades\Validator::make([
            'file' => !empty($request->file('file')) ? $request->file('file') : '',
            'extension' => strtolower(count($extension) > 1 ? $extension[1] : ''),
        ], [
            'file' => 'required',
            'extension' => 'in:xlsx,xls,csv',
        ], [
            'file.required' => 'Vui lòng chọn file tải lên!',
            'extension.in' => 'File tải lên không đúng định dạng excel(xlsx,xls,csv)!',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->route('learning.student.inProgress')
                ->withErrors($validator->errors(), 'import_learning')
                ->withInput();
        }
        $extension = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_EXTENSION);
        if ($extension == 'csv') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } elseif ($extension == 'xlsx') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        }

        $path = $request->file->getRealPath();
        $spreadsheet = $reader->load($path);
        $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        if ($spreadsheet->getSheetCount() > 1) {
            return redirect()->route('learning.student.inProgress')
                ->with('import_learning', 'File excel chỉ giới hạn 1 Sheet');
        }
        $data = array_values($data);
        if (count($data) > 0) {
            if (
                $data[0]['A'] !== 'Mã đề' ||
                $data[0]['B'] === '' ||
                $data[1]['A'] !== 'Họ tên' ||
                $data[1]['B'] === ''
            ) {
                return redirect()
                    ->route('learning.student.inProgress')
                    ->with('import_learning', "Không được thay đổi nội dung: Mã đề, Họ tên!");
            }
            $courseStudentId = $data[0]['B'];
            $headerExcel = array_values($data[2]);
            if (
                $headerExcel[0] !== "STT" ||
                $headerExcel[1] !== "Mã câu hỏi" ||
                $headerExcel[2] !== "Loại câu hỏi" ||
                $headerExcel[3] !== "Câu hỏi" ||
                $headerExcel[4] !== "Đáp án lựa chọn" ||
                ($headerExcel[5]) !== "A" ||
                ($headerExcel[6]) !== "B" ||
                ($headerExcel[7]) !== "C" ||
                ($headerExcel[8]) !== "D"

            ) {
                return redirect()
                    ->route('learning.student.inProgress')
                    ->with('import_learning', "Không được thay đổi tên các cột!");
            }

            $rules = [
                'E' => [
                    'required',
                ]
            ];
            $messages = [
                'E.required' => ':attribute không được để trống!',
            ];
            $dataSheet = array_slice($data, 3);
            $dataSheet = array_slice($dataSheet, 0, count($dataSheet) - 2);
            $dataNew = [];
            foreach ($dataSheet as $item) {
                $dataNew[] = [
                    'A' => $item['A'],
                    'B' => $item['B'],
                    'C' => $item['C'],
                    'D' => trim($item['D']),
                    'E' => $item['E'],
                ];
            }

            $dataInsert = [];
            foreach ($dataNew as $key => $value) {
                $validateData = [
                    'E' => trim($value['E']),
                ];
                $attributes["E"] = sprintf('Cột `Đáp án lựa chọn` dòng %s', $key + 4);

                $validator = Validator::make($validateData, $rules, $messages, $attributes);
                if ($validator->fails()) {
                    $errors = $validator->errors();
                    return redirect()
                        ->route('learning.student.inProgress')
                        ->withErrors($errors, 'import_learning')->withInput();
                }
                $dataInsert[] = $value;
            }
            $id_user = Auth::user()->id;
            $filename = time() . "_Đáp án_" . $request->file('file')->getClientOriginalName();
            $link_file = config('app.url') . "/storage/lesson_$id_user/$filename";
            DB::beginTransaction();
            try {
                $modelCourseStudent = CourseStudent::findOrFail($courseStudentId);
                $modelCourseStudent->status = CourseStudent::STATUS_COMPLETE;
                $modelCourseStudent->file = $link_file;
                $modelCourseStudent->updated_at = Carbon::now();
                if (!$modelCourseStudent->save()) {
                    DB::rollBack();
                    return redirect()
                        ->route('learning.student.inProgress')
                        ->with('import_learning', 'Lỗi nộp bài!');
                }

                CourseStudentDetail::query()
                    ->where('course_student_id', $courseStudentId)->delete();
                foreach ($dataInsert as $value) {
                    $model = new CourseStudentDetail();
                    $question = Questions::query()->where('title', $value['D'])->first();
                    if (empty($question)) {
                        DB::rollBack();
                        return redirect()
                            ->route('learning.student.inProgress')
                            ->with('import_learning', 'Không được sửa đổi nội dung câu hỏi!');
                    }
                    $model->course_student_id = $courseStudentId;
                    $model->question_id = $question->id;
                    $model->answer = $value['E'];
                    if (!$model->save()) {
                        DB::rollBack();
                        return redirect()
                            ->route('learning.student.inProgress')
                            ->with('import_learning', 'Lỗi nộp bài!');
                    }
                }
                DB::commit();
                $file = $request->file('file');
                Storage::putFileAs("/storage/lesson_$id_user/", $file, $filename);
                return redirect()->route('learning.student.inProgress')->with('success', 'Nộp bài thành công!');
            } catch (\Exception $exception) {
                DB::rollBack();
                return redirect()->route('learning.student.inProgress')->with('error_import', 'Lỗi import dữ liệu:' . $exception->getMessage());
            }
        } else {
            return redirect()->route('learning.student.inProgress')->with('error_import', 'File excel không có dữ liệu!');
        }
    }

    public function deleteAll()
    {
        CourseStudent::query()->delete();
        CourseStudentDetail::query()->delete();
    }
}
