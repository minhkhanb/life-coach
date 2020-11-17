@extends('admin.master')
@section('title','Trang chủ')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Trang chủ</a></li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    @if(Auth::user()->isAdmin() || Auth::user()->isCoach())
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $countStudent }}</h3>
                                    <p>Học viên</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <a href="{{ route('student.index') }}" class="small-box-footer">Xem chi tiết <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        @if(Auth::user()->isAdmin())
                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ $countCoach }}</h3>
                                        <p>Trợ giảng</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <a href="{{ route('coach.index') }}" class="small-box-footer">Xem chi tiết <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        @endif

                    <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $countCourse }}</h3>
                                    <p>Bài giảng</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <a href="{{ route('lesson.index') }}" class="small-box-footer">Xem chi tiết <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $countStudentWaiting }}</h3>
                                    <p>Học viên chưa kích hoạt</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-wind"></i>
                                </div>
                                <a href="{{ route('student.index').'?status='.\App\Model\User::ACTIVE_COMMON }}"
                                   class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        @if(Auth::user()->isAdmin())
                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3>{{ $countCoachNonActive }}</h3>
                                        <p>Tài khoản trợ giảng chưa kích hoạt</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-wind"></i>
                                    </div>
                                    <a href="{{ route('coach.index') }}?status={{\App\Model\User::ACTIVE_COMMON}}"
                                       class="small-box-footer">Xem chi tiết <i
                                            class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        @endif
                    @endif

                    @if(Auth::user()->isStudent())
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $countLessonOfHv }}</h3>
                                    <p>Bài giảng của bạn</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-wind"></i>
                                </div>
                                <a href="{{ route('learning.student.inProgress') }}"
                                   class="small-box-footer">Xem chi tiết <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    @endif
                <!-- ./col -->
                </div>
            </div><!-- /.container-fluid -->
        </div>
    </div>
@endsection
@section('js')
    <script src="/theme_admin/js/highcharts.js"></script>
    <script !src="">
        Highcharts.chart('chart-register-student', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Biến động đăng ký học'
            },
            xAxis: {
                categories: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                crosshair: true,
                title: {
                    text: 'Tháng'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Số lượng đăng ký'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">Tháng: {point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                },
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: true
                }
            },
            series: @json($dataReportRegisterStudent)
        });
    </script>
@endsection
