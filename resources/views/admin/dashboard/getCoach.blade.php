@extends('admin.master')
@section('title','Chi tiết Coach')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('coach.index') }}">Trợ giảng</a></li>
                            <li class="breadcrumb-item active">{{ $user->name }}</li>
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
                                <h5 class="m-0">Chi tiết trợ giảng</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Họ tên</td>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td>Chứng minh nhân dân</td>
                                        <td>{{ $user->identity_card }}</td>
                                    </tr>
                                    <tr>
                                        <td>Trạng thái</td>
                                        @if($user->active == '2')
                                            <td>active</td>
                                        @else
                                            <td style="color: red;">inactive</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>Ảnh chứng minh thư</td>
                                        <td>
                                            @if(empty($user->image_identify) && empty($user->image_identify_2))
                                                <span class="badge badge-danger">Chưa upload ảnh cmnd!</span>
                                            @endif
                                            @if(!empty($user->image_identify))
                                                <img class="profile-user-img img-fluid"
                                                     src="{{ asset('uploads/identify/' . $user->image_identify) }}"
                                                     alt="User identify card" style="width: 150px;">
                                            @endif
                                            @if(!empty($user->image_identify_2))
                                                <img class="profile-user-img img-fluid"
                                                     src="{{ asset('uploads/identify/' . $user->image_identify_2) }}"
                                                     alt="User identify card" style="width: 150px; margin-left: 2rem;">
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('coach.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-circle-left"></i> Trở về
                                </a>
                                @if($user->active != '2')
                                    <button type="button" class="btn btn-danger delete float-right"
                                            data-link="{{route('coach.deleteRequestCoach',$user->id)}}"
                                            title="Delete" style="margin-bottom: 1rem; margin-left: 1rem;">
                                        <i class="fas fa-check-circle"></i>
                                        Xóa yêu cầu
                                    </button>

                                    <button type="button" class="btn btn-primary update float-right"
                                            data-link="{{route('coach.update',$user->id)}}"
                                            title="Update" style="margin-bottom: 1rem;">
                                        <i class="fas fa-check-circle"></i>
                                        Xác thực tài khoản
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->
                </div>

            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
@endsection
