@php
    $type = isset($type) ? $type : $detail->type;
    $detail = $detail ?? false;
    $detail_answer = !empty($detail) ? $detail->answers : [null, null, null, null];
    $arrRange = range('A','D');
    $title_header = $detail ? 'Chỉnh sửa câu hỏi' : 'Tạo mới câu hỏi';
@endphp
@extends('admin.master')
@section('title',$title_header)
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('question.index') }}">Câu hỏi</a></li>
                            <li class="breadcrumb-item active">{{ $title_header }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-success card-outline">
                            <div class="card-header">
                                <h5 class="m-0">{{ $title_header }}</h5>
                            </div>
                            <form action="{{ route('question.store',$detail ? $detail->id : '') }}" id="formQuestion"
                                  method="post">
                                @csrf
                                <input type="hidden" name="course_question_id"
                                       value="{{ $detail ? $detail->course_question_id : '' }}">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">Áp dụng cho bài giảng
                                            <span class="text-danger">*</span></label>
                                        <div class="col-sm-10">
                                            <select name="course_id"
                                                    class="form-control @error('course_id') is-invalid @enderror" required>
                                                <option value="" selected="false" disabled>Chọn bài giảng</option>
                                                @if(!$course->isEmpty())
                                                    @php
                                                        showDropdown($course, old('course_id',$detail->course_id ?? ''))
                                                    @endphp
                                                @endif
                                            </select>
                                            @error('course_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">Tiêu đề câu hỏi
                                            <span class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <textarea
                                                placeholder="Nhập câu hỏi"
                                                class="form-control @error('title') is-invalid @enderror"
                                                name="title" required minlength="6">{{ old('title', $detail->title ?? '') }}</textarea>
                                            @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <input type="hidden" name="type"
                                           value="{{ $type  }}">
                                    @if($type === \App\Model\Questions::TYPE_MULTI_CHOICE)
                                        <div class="listAnswer mt-2">
                                            <table width="100%">
                                                <tbody>
                                                @foreach($arrRange as $key => $val)
                                                    <tr class="rowAnswer row" data-index="{{ $val }}">
                                                        <td class="col-md-2">
                                                            <label for="name" class="col-form-label">
                                                                {{$val}}
                                                                <span class="text-danger">*</span></label>
                                                        </td>
                                                        <td class="col-md-8 form-group mb-0">
                                                            <div>
                                                                <textarea
                                                                    data-index="{{$key}}"
                                                                    placeholder="Nhập đán án"
                                                                    id="answer_{{ $key }}"
                                                                    class="form-control answer @error("answer[$key]") is-invalid @enderror"
                                                                    name="answer[{{$key}}]">{{ old("answer",$detail_answer)[$key] }}</textarea>
                                                                @error("answer[$key]")
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td class="col-md-2 text-center"
                                                            style="vertical-align: middle;">
                                                            <input type="radio" name="answer_correct"
                                                                   @if($detail && ($detail->answer_correct === $val) || $key === 0)
                                                                   checked
                                                                   @endif
                                                                   class="mt-3"
                                                                   value="{{$val}}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                    @else
                                        {{-- CAU HOI TU LUAN--}}
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-2 col-form-label">
                                                Ghi chú
                                                <span class="text-danger">*</span></label>
                                            <div class="col-md-10">
                                                <textarea
                                                    class="form-control textarea"
                                                    name="answer_correct"
                                                    rows="30">{{ old('answer_correct', $detail->answer_correct ?? '')  }}</textarea>
                                                @error('answer_correct')
                                                <span id="title-error" class="error invalid-feedback" style="display: block;">Trường này không được trống!</span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success float-right">
                                        <i class="fas fa-save"></i> Lưu lại
                                    </button>
                                    <a href="{{ route('question.index') }}"
                                       class="btn btn-secondary">
                                        <i class="fas fa-arrow-circle-left"></i>
                                        Trở về
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
@endsection
@section('css')
@endsection

@section('js')
    @include('admin.question.validate')

    @error('answer_correct')
    <script>
        $().ready(function () {
            $('#cke_answer_correct').css('border', '1px solid #dc3545');
        })
    </script>
    @enderror

    <script !src="">
        $('#addAnswer').click(function () {
            let index = $('.listAnswer .rowAnswer').length;
            ++index
            let row = "<div class=\"form-group row rowAnswer\" data-index=" + index + ">\n" +
                "                                    <label for=\"name\" class=\"col-sm-2 col-form-label\">Đáp án " + index + "\n" +
                "                                        <span class=\"text-danger\">*</span></label>\n" +
                "                                    <div class=\"col-md-10\">\n" +
                "                                        <textarea\n" +
                "                                            placeholder=\"Nhập đáp án\"\n" +
                "                                            class=\"form-control textarea\"\n" +
                "                                            name=\"answer_correct[]\"></textarea>\n" +
                "                                    </div>\n" +
                "                                </div>";
            $('.listAnswer').append(row)
        });
    </script>
    @if($type === \App\Model\Questions::TYPE_ONE_CHOICE)
        <script src="/public/theme_admin/vendor/ckeditor/ckeditor.js"></script>
        <script type="text/javascript">
            $(function () {
                CKEDITOR.replace('answer_correct', {
                    height: 500
                });
                CKEDITOR.replace('title', {
                    height: 350
                });
            });
        </script>
    @endif
@endsection
