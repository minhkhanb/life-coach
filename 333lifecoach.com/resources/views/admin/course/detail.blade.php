@extends('admin.master')
@section('title','Thông tin bài giảng')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('lesson.index') }}">Bài giảng</a></li>
                            <li class="breadcrumb-item active">{{ $detail->slug }}</li>
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
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <p>Thông tin chi tiết:</p>
                                        <table width="100%">
                                            <tr>
                                                <td>Tên bài giảng:</td>
                                                <td>{{ $detail->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Ngày mở:</td>
                                                <td>{{ formatDateTime($detail->open_at) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Ngày dự kiến kết thúc:</td>
                                                <td>{{ formatDateTime($detail->expected_end_date) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-6">
                                        <p>Hình ảnh:</p>
                                        <img src="{{ asset('uploads/course/' . $detail->images) }}" alt="" class="img-fluid img-thumbnail">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->
                </div>
            </div><!-- /.container-fluid -->
        </div>
    </div>
@endsection
