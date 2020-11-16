@extends('admin.master')
@section('title','Tạo bài giảng')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('lesson.index') }}">bài giảng</a></li>
                            <li class="breadcrumb-item active">Tạo mới</li>
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
                                <h5 class="m-0">Thông tin bài giảng</h5>
                            </div>
                            <form class="form-horizontal" enctype="multipart/form-data" method="post"
                                  id="courseForm"
                                  action="{{ route('lesson.store') }}">
                                <div class="card-body">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label for="name" class="col-sm-2 col-form-label">Tên bài giảng
                                                <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="name"
                                                       class="form-control @error('name') is-invalid @enderror"
                                                       value="{{ old('name') }}"
                                                       placeholder="Tiêu đề bài giảng"
                                                       required
                                                       minlength="6">
                                                @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="open_at" class="col-sm-2 col-form-label">Ngày dự kiến mở
                                                <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="date"
                                                       class="form-control @error('open_at') is-invalid @enderror"
                                                       id="open_at"
                                                       name="open_at"
                                                       value="{{ old('open_at') }}"
                                                       placeholder="Thời gian mở"
                                                       required >
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
                                                       class="form-control @error('expected_end_date') is-invalid @enderror"
                                                       name="expected_end_date"
                                                       id="expected_end_date"
                                                       value="{{ old('expected_end_date') }}"
                                                       placeholder="Thời gian mở"
                                                       required >
                                                @error('expected_end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Hình ảnh
                                            </label>
                                            <div class="col-sm-10">
                                                <input type="file"
                                                       class="form-control @error('file') is-invalid @enderror"
                                                       name="file"
                                                       placeholder="Hình ảnh bài giảng" >
                                                @error('file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success float-right">
                                        <i class="fas fa-save"></i> Lưu lại
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
    <script src="/theme_admin/js/autoNumeric.min.js"></script>
    <script !src="">
        $('input[name="price"]').autoNumeric();
    </script>
@endsection
