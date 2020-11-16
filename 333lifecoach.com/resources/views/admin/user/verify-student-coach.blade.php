@extends('admin.master')
@section('title','Đăng ký làm trợ giảng')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('user.profile', Auth::user()->id) }}">Thông tin cá nhân</a></li>
                            <li class="breadcrumb-item active">Đăng ký làm trợ giảng</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-success card-outline">
                            <div class="card-body box-profile">
                                <div class="card-header">
                                <h5 class="m-0">Chi tiết học viên</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Họ tên</td>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Số điện thoại</td>
                                        <td>{{ $user->phone }}</td>
                                    </tr>
                                    <tr>
                                        <td>Link Facebook</td>
                                        <td>{{ $user->nick_fb }}</td>
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
                                <a href="{{ route('student.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-circle-left"></i> Trở về
                                </a>
                                @if($user->type == \App\Model\User::TYPE_STUDENT);
                                    <button type="button" class="btn btn-danger delete float-right"
                                            data-link="{{route('user.deleteVerifyCoach',$user->id)}}"
                                            title="Delete" style="margin-bottom: 1rem; margin-left: 1rem;">
                                        <i class="fas fa-check-circle"></i>
                                        Xóa yêu cầu
                                    </button>

                                    <button type="button" class="btn btn-primary update float-right"
                                            data-link="{{route('user.confirmVerifyCoach',$user->id)}}"
                                            title="Xác thực" style="margin-bottom: 1rem; margin-left: 1rem;">
                                        <i class="fas fa-check-circle"></i>
                                        Xác thực làm trợ giảng 
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
