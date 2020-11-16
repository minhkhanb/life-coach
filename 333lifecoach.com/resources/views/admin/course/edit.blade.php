@extends('admin.master')
@section('title','Quản lý bài giảng')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('lesson.index') }}">bài giảng</a></li>
                            <li class="breadcrumb-item active">{{ $course->name }}</li>
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
                                <h5 class="m-0">Chỉnh sửa thông tin bài giảng</h5>
                            </div>
                            <form class="form-horizontal" enctype="multipart/form-data" method="post"
                                  id="courseForm"
                                  action="{{ route('lesson.update',$course->id) }}">
                                <div class="card-body">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-2 col-form-label">Tên bài giảng
                                                <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="name"
                                                       class="form-control @error('name') is-invalid @enderror"
                                                       value="{{ old('name') ?? $course->name }}"
                                                       placeholder="Tiêu đề bài giảng">
                                                @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="open_at" class="col-sm-2 col-form-label">Ngày mở dự kiến
                                                <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="date"
                                                       class="form-control @error('open_at') is-invalid @enderror"
                                                       id="open_at"
                                                       name="open_at"
                                                       value="{{ old('open_at') ?? \Carbon\Carbon::parse($course->open_at)->format('Y-m-d') }}"
                                                       placeholder="Thời gian mở">
                                                @error('open_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Ngày dự kiến kết thúc
                                                <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="date"
                                                       class="form-control @error('open_at') is-invalid @enderror"
                                                       name="expected_end_date"
                                                       value="{{ old('expected_end_date') ?? \Carbon\Carbon::parse($course->expected_end_date)->format('Y-m-d') }}"
                                                       placeholder="Thời gian mở">
                                                @error('expected_end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Hình ảnh</label>
                                            <div class="col-sm-10">
                                                <input type="file"
                                                       class="form-control @error('file') is-invalid @enderror"
                                                       name="file"
                                                       placeholder="Hình ảnh bài giảng">
                                                @error('file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <img class="img-fluid img-thumbnail" src="{{ asset('uploads/course/' . $course->images) }}" style="max-height: 200px">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success float-right">
                                        <i class="fas fa-save"></i> Cập nhật
                                    </button>
                                    <a href="{{ route('lesson.index') }}"
                                       class="btn btn-secondary">
                                        <i class="fas fa-arrow-alt-circle-left"></i>
                                        Trở về
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div>
    </div>
@endsection
@section('js')
    @include('admin.course.validate')
@endsection
