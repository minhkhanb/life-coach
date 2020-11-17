@extends('admin.master')
@section('title','Danh sách trợ giảng')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Trợ giảng</li>
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
                                <h5 class="m-0">Danh sách trợ giảng</h5>
                            </div>
                            <div class="row" style="margin-top: 0.5rem; padding: 0 1.3rem;">
                                <div class="col-9">
                                    <form action="{{ route('coach.index') }}" method="get" class="form-inline mr-auto">
                                        <input class="form-control" name="search" type="text" placeholder="Tìm kiếm theo tên" value="{{ $key_search }}" style="width: 50% !important;">
                                        <button type="submit" class="btn btn-info ml-1">
                                            <i class="fas fa-search"></i> Tìm kiếm
                                        </button>
                                    </form>
                                </div>
                                <div class="col-3">
                                    <div class="float-right mt-1">
                                        <a href="{{ route('coach.register') }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-plus"></i> Thêm mới
                                        </a>
                                    </div>
                                </div>
                            </div>

                            @if(count($user_wait_browsing) > 0)
                                <div class="alert alert-danger" style="width: 96%; margin: 0px auto; padding: 1rem; margin-top: 1rem;">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4>Các tài khoản trợ giảng đã upload chứng minh thư và chờ phê duyệt!</h4>
                                    @foreach($user_wait_browsing as $key => $item)
                                        <strong>{{ $key + 1 }}. </strong><a href="{{ route('coach.getCoach', $item->id) }}">{{ $item->name }}</a><br>
                                    @endforeach
                                </div>
                            @endif
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">STT</th>
                                                <th>Họ tên</th>
                                                <th>Email</th>
                                                <th>Số điện thoại</th>
                                                <th>Số chứng minh nhân dân</th>
                                                <th>Trạng thái</th>
                                                <th>Số học viên</th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!$users->isEmpty())
                                                @foreach($users as $key => $user)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>{{ $user->phone }}</td>
                                                        <td>{{ $user->identity_card }}</td>
                                                        <td>
                                                            {!! \App\Model\User::renderTextActive($user->active) !!}
                                                        </td>
                                                        <td>{!! \App\Model\User::studentOfCoach($user->id) !!}</td>
                                                        <td>
                                                            <a href="{{ route('coach.getCoach',$user->id) }}"
                                                               class="btn btn-primary btn-xs" title="Sửa">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-xs btn-danger delete"
                                                                    data-link="{{route('coach.deleteCoach',$user->id)}}"
                                                                    title="Xóa">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
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
                                <br>
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
@endsection
