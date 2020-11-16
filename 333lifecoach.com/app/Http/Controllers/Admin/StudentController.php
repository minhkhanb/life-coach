<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\CourseStudent;
use App\Model\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\DB;
use App\Model\CourseStudentDetail;

class StudentController extends Controller
{
    public function showListStudent(Request $request)
    {
        $key_search = $request->search ? $request->search : "";
        $student_wait_browsing_coach = User::query()->where('active', '=', User::ACTIVE_COMMON)->where('type', '=', User::TYPE_STUDENT)->whereNotNull('image_identify')->whereNotNull('image_identify_2')->get();
        $type = User::TYPE_STUDENT;
        $isCoach = Auth::user()->isCoach();

        $users = User::query()
            ->with(['courseComplete' => function ($query) {
                $query->where('status', CourseStudent::STATUS_CANCEL);
            }])
            ->where('type', '=', (int)$type)
            ->where('name', 'like', '%' . $request->search . '%')
            ->when($request->get('status'), function ($query, $status) {
                $query->where('active', '<>', $status);
            })
            ->when($isCoach, function ($query) {
                $query->where('user_owner', Auth::user()->id);
            })
            ->paginate(10);
        return view('student.list_student', compact('users', 'key_search', 'student_wait_browsing_coach'));
    }

    public function detailStudent($id)
    {
        $user = User::query()->where('id', '=', $id)->first();
        $list_coach = User::query()->where('type', User::TYPE_COACHING)->where('active', User::ACTIVE_COMMON)->get();
        $timeline = CourseStudent::query()
            ->where('student_id', $id)
            ->get();
        return view('student.detail_student', compact('user', 'timeline', 'list_coach'));
    }

    public function deleteLesson($courseStudentId)
    {
        DB::beginTransaction();
        try {
            CourseStudent::findOrFail($courseStudentId)->delete();
            $courseStDetailModel = CourseStudentDetail::query()
                ->where('course_student_id', '=', $courseStudentId)
                ->get();
            if (!empty($courseStDetailModel)) {
                CourseStudentDetail::query()
                ->where('course_student_id', '=', $courseStudentId)
                ->delete();
            }
            
            DB::commit();
            return redirect()->back()->with('success','Xóa thành công!');
        }catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error',$exception->getMessage());
        }
    }

    public function deleteStudent($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'Xóa thành công');
    }

    public function updateCoachStudent(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->coach) {
            $user->user_owner = $request->coach;
        } else {
            $user->user_owner = null;
        }
        $user->save();
        return redirect()->back()->with('success', 'Cập nhật thành công');
    }

    public function exportExcelEmail()
    {
        $fileName = 'Danh_sach_email_hoc_vien';
        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();
        $sheet->mergeCells("A1:D1")->setCellValue("A1", "Danh sách email học viên");

        if (Auth::user()->type == User::TYPE_ADMIN) {
            $dataExport = User::query()->where('type', User::TYPE_STUDENT)->select(['email', 'phone', 'name'])->get();
        } else {
            $dataExport = User::query()->where('type', User::TYPE_STUDENT)->where('user_owner', Auth::user()->id)->select(['email', 'phone', 'name'])->get();
        }

        if (!$dataExport->isEmpty()) {
            $start = 2;
            foreach ($dataExport as $index => $item) {
                $sheet->setCellValue("A$start", $index + 1);
                $sheet->setCellValue("B$start", $item['email']);
                $sheet->setCellValue("C$start", $item['name']);
                $sheet->setCellValue("D$start", $item['phone']);
                $start++;
            }
            $styleArray = array(
                'font' => array(
                    'bold' => true
                ));
            $sheet->getColumnDimension('B')->setWidth('30');
            $sheet->getColumnDimension('C')->setWidth('30');
            $sheet->getColumnDimension('D')->setWidth('30');
            $sheet->getStyle('A1')->applyFromArray($styleArray);
            $write = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadSheet);
            $write->save("storage/$fileName" . ".xlsx");
            return response()->download("storage/$fileName" . ".xlsx");
        }
    }
}
