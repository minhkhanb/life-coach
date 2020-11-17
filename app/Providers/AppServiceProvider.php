<?php

namespace App\Providers;

use App\Model\CourseStudent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer('admin.layouts.nav', function ($view) {
            $countLessonOfStudent = CourseStudent::query()
                ->select('id')
                ->where([
                    ['student_id', '=', Auth::user()->id],
                    ['status', '=', CourseStudent::STATUS_INPROGRESS]
                ])->count();
            $countLessonNotReview = CourseStudent::countLessonNeedReview()->count();
            $view->with([
                'countLessonOfStudent' => $countLessonOfStudent,
                'countLessonNotReview' => $countLessonNotReview
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
//        Schema::defaultStringLength(191);
        if (env('APP_ENV') === 'development') {
            $url->forceScheme('https');
        }
        if (!file_exists("storage/")) {
            mkdir("storage/", 0777, true);
        }
    }
}
