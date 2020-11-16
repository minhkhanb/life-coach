@extends('admin.master')
@section('title','Danh sách học viên')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item active">học viên</li>
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
                                <h5 class="m-0">Danh sách học viên</h5>
                            </div>
                            <div class="row" style="margin-top: 0.5rem; padding: 0 1.3rem;">
                                <div class="col-9">
                                    <form action="{{ route('student.index') }}" method="get"
                                          class="form-inline mr-auto">
                                        <input class="form-control" value="{{ $key_search }}" name="search" type="text"
                                               placeholder="Tìm kiếm"
                                               style="width: 50% !important;">
                                        <button type="submit" class="btn btn-info ml-1">
                                            <i class="fas fa-search"></i> Tìm kiếm
                                        </button>
                                    </form>
                                </div>
                                <div class="col-3">
                                    @if(Auth::user()->type == \App\Model\User::TYPE_ADMIN)
                                    <div class="float-right mt-1">
                                        <a href="{{ route('student.downloadExcel') }}"
                                           target="_blank"
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-file-excel"></i> Xuất danh sách email
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            @if(count($student_wait_browsing_coach) > 0 && Auth::user()->type == \App\Model\User::TYPE_ADMIN)
                                <div class="alert alert-danger"
                                     style="width: 96%; margin: 0px auto; padding: 1rem; margin-top: 1rem;">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                        &times;
                                    </button>
                                    <h4>Các tài khoản học viên chờ phê duyệt làm trợ giảng!</h4>
                                    @foreach($student_wait_browsing_coach as $key => $item)
                                        <strong>{{ $key + 1 }}. </strong><a
                                            href="{{ route('user.verifyCoach', $item->id) }}">{{ $item->name }}</a><br>
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
                                            @if(Auth::user()->type == \App\Model\User::TYPE_ADMIN)
                                            <th>Email</th>
                                            @endif
                                            <th>Số điện thoại</th>
                                            <th>Số chứng minh nhân dân</th>
                                            <th>Người coach</th>
                                            <th>Số khóa học đã hoàn thành</th>
                                            <th width="10%">Ngày tham gia</th>
                                            <th>Trạng thái</th>
                                            <th>Chi tiết</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!$users->isEmpty())
                                            @foreach($users as $key => $user)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    @if(Auth::user()->type == \App\Model\User::TYPE_ADMIN)
                                                    <td>{{ $user->email }}</td>
                                                    @endif
                                                    <td>{{ $user->phone }}</td>
                                                    <td>{{ $user->identity_card }}</td>
                                                    <td>{!! \App\Model\User::getNameCoachOwner($user->user_owner) !!}</td>
                                                    <td>{{ $user->courseComplete->count() }}</td>
                                                    <td>{{ formatDateTime($user->created_at) }}</td>
                                                    <td>
                                                        {!! \App\Model\User::renderTextActive($user->active) !!}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('student.detailStudent',$user->id) }}"
                                                           class="btn btn-primary btn-xs" title="chi tiết">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if(Auth::user()->type == '1')
                                                            <button type="button" class="btn btn-xs btn-danger delete"
                                                                    data-link="{{ route('student.deleteStudent',$user->id) }}"
                                                                    title="Xóa">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
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
