@extends('admin.master')
@section('title','Danh sách bài giảng')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Danh sách bài giảng</li>
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
                            <div class="row mt-1 ml-2 mr-2">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <form action="{{ route('lesson.index') }}" method="get" class="form-inline mr-auto">
                                        <input class="form-control" name="search" value="{{ $key_search }}" type="text"
                                               placeholder="Tìm kiếm"
                                               style="width: 50% !important;">
                                        <button type="submit" class="btn btn-primary ml-1">
                                            <i class="fas fa-search"></i> Tìm kiếm
                                        </button>
                                    </form>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="float-right mt-1">
                                        @if(Auth::user()->type === \App\Model\User::TYPE_ADMIN)
                                            <a href="{{ route('lesson.create') }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-plus"></i> Tạo bài giảng
                                            </a>
                                            <button data-toggle="modal" data-target="#importExercise"
                                                    class="btn btn-info btn-sm">
                                                <i class="fas fa-file-excel"></i> Import câu hỏi
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Hình ảnh</th>
                                            <th width="15%">Tên bài giảng</th>
                                            <th>Thời gian mở</th>
                                            <th>Số lượng câu hỏi</th>
                                            <th width="10%">Số học viên đăng ký học</th>
                                            <th width="10%">Số học viên hoàn thành</th>
                                            <th width="10px">Copy and share</th>
                                            @if(Auth::user()->type === \App\Model\User::TYPE_ADMIN)
                                                <th></th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!$course->isEmpty())
                                            @foreach($course as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        @if(!empty($item->images))
                                                            <img class="img-responsive"
                                                                 src="{{ asset('uploads/course/' . $item->images) }}"
                                                                 width="70px" height="65px">
                                                        @else
                                                            <img class="img-responsive"
                                                                 src="/public/theme_admin/img/avatar.jpg"
                                                                 width="70px" height="65px">
                                                        @endif
                                                    </td>
                                                    <td class="text-truncate" style="max-width: 150px;">
                                                        <a href="{{ route('lesson.detail', $item->slug) }}"
                                                           title="{{ $item->name }}">
                                                            {{ $item->name }}
                                                        </a>
                                                    </td>
                                                    <td>{{ formatDateTime($item->open_at) }}</td>
                                                    <td>
                                                        @if( $item->courseQuestion->count() > 0)
                                                            {{ $item->courseQuestion->count() }}
                                                        @else
                                                            <span class="badge badge-danger">Chưa có câu hỏi</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->courseStudent->count() }}</td>
                                                    <td>{{ $item->studentComplete->count() }}</td>
                                                    <td class="text-center">
                                                        <input
                                                            type="text"
                                                            value="{{ $item->name }}"
                                                            id="link_course_{{$item->id}}"
                                                            style="opacity: -1; z-index: -1; position: absolute;">
                                                        <button onclick="funCopy({{$item->id}})"
                                                                class="btn btn-sm btn-primary d-inline">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                    </td>
                                                    @if(Auth::user()->type === \App\Model\User::TYPE_ADMIN)
                                                        <td class="text-center" style="z-index: 1;">
                                                            <a href="{{ route('lesson.edit',$item->id) }}"
                                                               class="btn btn-primary btn-xs" title="Sửa">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-xs btn-danger delete"
                                                                    data-link="{{route('lesson.delete',$item->id)}}"
                                                                    title="Xóa">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    @endif
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
                                @if(!empty($course))
                                    {{ $course->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->
                </div>
            </div><!-- /.container-fluid -->
        </div>
    </div>
    @include('admin.course.modal')
@endsection
@section('js')
    <script>
        function funCopy(courseId) {
            var idInput = "link_course_" + courseId;
            var copyText = document.getElementById(idInput);
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            toastr.success('Copied: ' + copyText.value);
            $('#link_lesson').val(copyText.value)
            $('#modalSendToLesson').modal('show');
        }

        $("#checkAll").click(function(){
            $("input:checkbox").not(this).prop("checked", this.checked);
        })
    </script>

    @if(session()->has('error_import') || $errors->import_question->any())
        <script !src="">
            $('#importExercise').modal('show');
        </script>
    @endif
@endsection
