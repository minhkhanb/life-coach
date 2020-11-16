@extends('admin.master')
@section('title','Danh sách câu hỏi')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Danh sách câu hỏi</li>
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
                                <h5 class="m-0">Danh sách câu hỏi</h5>
                            </div>
                            <div class="row" style="margin-top: 0.5rem; padding: 0 1.3rem;">
                                <div class="form-group col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                    <form action="{{ route('question.index') }}" method="get"
                                          class="mr-auto">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input class="form-control" value="{{ $paramSearch['title'] }}" name="search" type="text"
                                                       placeholder="Tên bài giảng">
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" name="course_id">
                                                    <option value="">Danh mục bài tập/bài giảng</option>
                                                    @if(!$course->isEmpty())
                                                        @php
                                                            showDropdown($course, old('course_id',$paramSearch['course_id'] ?? ''))
                                                        @endphp
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-info ml-1">
                                                    <i class="fas fa-search"></i> Tìm kiếm
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                    <div class="float-right mt-1">
                                        @if(Auth::user()->type == '1')
                                            <a href="{{ route('question.createMultiChoice') }}"
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-plus"></i> Trắc nghiệm
                                            </a>
                                            <a href="{{ route('question.createOneChoice') }}"
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-plus"></i> Tự luận
                                            </a>
                                        @endif

                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="table-questions">
                                        <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th width="50%">Tiêu đề câu hỏi</th>
                                            <th width="10%">Loại câu hỏi</th>
                                            <th width="10%">Bài giảng</th>
                                            <th width="15%">Đáp án gợi ý</th>
                                            <th width="15%">Đáp án lựa chọn</th>
                                            @if(Auth::user()->type == '1')
                                                <th width="5%"></th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!$questions->isEmpty())
                                            @foreach($questions as $item)
                                                @php
                                                    $answers = json_decode($item['answers']);
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td title="{{ $item->title }}">
                                                        {!! $item->title !!}
                                                    </td>
                                                    <td>{{ $item->name_type }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td class="text-more text-truncate" style="max-width: 150px;">
                                                        {!! strlen($item->answer_correct) > 100 ? substr($item->answer_correct, 0, 100).'...' : $item->answer_correct !!}
                                                    </td>
                                                    <td style="max-width: 250px;">
                                                        @if($item->type === \App\Model\Questions::TYPE_MULTI_CHOICE)
                                                            {!! renderToAnswers($item['answers'], $item->answer_correct) !!}
                                                        @endif
                                                    </td>

                                                    @if(Auth::user()->type == '1')
                                                        <td class="text-center">
                                                            <a href="{{ route('question.edit',$item->id) }}"
                                                               class="btn btn-primary btn-xs" title="Sửa">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-xs btn-danger delete"
                                                                    data-link="{{ route('question.delete',$item->id) }}"
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
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div>
    </div>
@endsection
@section('css')
    <style>
        table .text-more > p, table li {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
  <link rel="stylesheet" href="/public/theme_admin/vendor/dataTable/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="/public/theme_admin/vendor/dataTable/responsive.bootstrap4.min.css">
@endsection
@section('js')
<script src="/public/theme_admin/vendor/dataTable/jquery.dataTables.min.js"></script>
<script src="/public/theme_admin/vendor/dataTable/dataTables.bootstrap4.min.js"></script>
<script src="/public/theme_admin/vendor/dataTable/dataTables.responsive.min.js"></script>
<script src="/public/theme_admin/vendor/dataTable/responsive.bootstrap4.min.js"></script>
<script>
  $(function () {
    $("#table-questions").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
  });
</script>
@endsection

