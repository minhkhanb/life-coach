<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Course;
use App\Model\CourseStudent;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function dashboard()
    {
        $countCoachNonActive = 0;
        $countStudent = 0;
        $countStudentWaiting = 0;
        if (Auth::user()->isAdmin()) {
            $countStudent = User::query()->select('id')->where('type', User::TYPE_STUDENT)->count();
            $countStudentWaiting = $this->countStudentWaiting();
            $countCoachNonActive = User::query()->select('id')
                ->where([
                    ['type', '=', User::TYPE_COACHING],
                    ['active', '<>', User::ACTIVE_COMMON]
                ])
                ->count();


        }
        if (Auth::user()->isCoach()) {
            $countStudent = User::query()
                ->select('id')
                ->where([
                    ['type', '=', User::TYPE_STUDENT]
                ])
                ->when(Auth::user()->isCoach(), function ($query) {
                    $query->where('user_owner', '=', Auth::user()->id);
                })
                ->count();
            $countStudentWaiting = $this->countStudentWaiting(Auth::user()->id);
        }
        $countCoach = User::query()->select('id')->where('type', User::TYPE_COACHING)->count();
        $countCourse = Course::query()->select('id')->count();

        $dataReportRegisterStudent[] = [];
        // of hv
        $countLessonOfHv = 0;
        if (Auth::user()->isStudent()) {
            $countLessonOfHv = CourseStudent::query()
                ->select('id')
                ->where([
                    ['student_id', '=', Auth::user()->id]
                ])->count();
        }

        return view('admin.home.index',
            [
                'countStudent' => $countStudent,
                'countCoach' => $countCoach,
                'countCourse' => $countCourse,
                'countStudentWaiting' => $countStudentWaiting,
                'countCoachNonActive' => $countCoachNonActive,
                'dataReportRegisterStudent' => $dataReportRegisterStudent,
                'countLessonOfHv' => $countLessonOfHv
            ]);
    }

    public function getDataReportRegisterStudent()
    {
        $queryData = CourseStudent::query()
            ->select(DB::raw('MONTH(created_at) as month'))
            ->addSelect(DB::raw('COUNT(*) as total'))
            ->groupBy('month')
            ->get();
        $months = range(1, 12);
        $data['data'] = [];
        foreach ($months as $index => $month) {
            $data['data'][] = 0;
            if (count($queryData) > 0) {
                foreach ($queryData as $item) {
                    if ($month === $item['month']) {
                        $data['data'][$index] = $item['total'];
                    }
                }
            }
        }
        $data['name'] = 'Biến động học viên đăng ký học';
        return $data;
    }

    public function countStudentWaiting($idCoach = null)
    {
        return User::query()
            ->select('id')
            ->where('type', User::TYPE_STUDENT)
            ->where('active', User::UN_ACTIVE)
            ->when($idCoach, function ($query, $idCoach) {
                $query->where('user_owner', $idCoach);
            })
            ->count();
    }
}
