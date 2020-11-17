@extends('admin.master')
@section('title','Danh sách bài giảng của bạn')
@section('content')
    @include('admin.learning.modal')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item">Bài giảng của bạn</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- /.col-md-6 -->
                    <div class="col-lg-12">
                        <div class="card card-success card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Danh sách bài giảng</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-6">
                                        @include('admin.learning.search',['route' => route('learning.student.inProgress')])
                                    </div>
                                    <div class="col-6">
                                        <div class="float-right">
                                            <button type="button"
                                                    data-toggle="modal" data-target="#modalImportLesson"
                                                    class="btn btn-info btn-sm">
                                                <i class="fas fa-file-import"></i>
                                                Tải lên bài làm
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Tiêu đề bài giảng</th>
                                            <th>Thời gian gửi</th>
                                            <th class="text-center">Tình trạng hoàn thành(%)</th>
                                            <th>Chi tiết đáp án</th>
                                            <th>Hành động</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!$data->isEmpty())
                                            @foreach($data as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td class="text-truncate">
                                                        {{ $item->name }}
                                                    </td>
                                                    <td>
                                                        {{ formatDateTime($item->ct_created_at, 'd-m-Y H:i') }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if($item->status === \App\Model\CourseStudent::STATUS_COMPLETE)
                                                            <span class="badge badge-secondary">Đã gửi bài</span>
                                                        @elseif($item->status === \App\Model\CourseStudent::STATUS_CANCEL)
                                                            <span
                                                                class="badge badge-success">{{ $item->rate }}</span>
                                                        @else
                                                            <span class="badge badge-warning">Chưa làm bài</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($item->status === \App\Model\CourseStudent::STATUS_CANCEL)
                                                            <?php $detail_rate = json_decode($item->detail_rate, true); ?>
                                                            <p class="mb-0">Tự luận: {{ $detail_rate['tl'] }}</p>
                                                            <p>Trắc nghiệm: {{ $detail_rate['tn'] }}</p>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if($item->status === \App\Model\CourseStudent::STATUS_INPROGRESS)
                                                            <a href="{{ route('learning.student.showQuestions',['id' => $item->course_student_id,'slug'=>$item->slug]) }}"
                                                               class="btn btn-sm btn-info mb-1">
                                                                <i class="fas fa-angle-double-right"></i>
                                                                Làm bài
                                                            </a>
                                                            <a href="{{ route('learning.student.exportLesson',['id' => $item->course_student_id,'slug'=>$item->slug]).'?is_export=1' }}"
                                                               target="_blank"
                                                               class="btn btn-primary btn-sm">
                                                                <i class="fas fa-file-export"></i>
                                                                Tải về
                                                            </a>
                                                        @else
                                                            <a href="{{ route('learning.student.showQuestions',['id' => $item->course_student_id,'slug'=>$item->slug]) }}"
                                                               class="btn btn-sm btn-secondary">
                                                                <i class="fas fas fa-recycle"></i> Xem lại
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="10" class="text-center text-danger">
                                                    Không có dữ liệu!
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                @if(!$data->isEmpty())
                                    {{ $data->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div>
    </div>
@endsection
@section('css')
@endsection
@section('js')
    @if($errors->import_learning->any() || session()->has('import_learning'))
        <script !src="">
            $('#modalImportLesson').modal('show');
        </script>
    @endif
@endsection

