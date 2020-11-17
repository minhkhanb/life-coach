@extends('admin.master')
@section('title','Chi tiết học viên')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('student.index') }}">Học viên</a></li>
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
                                <h5 class="m-0">Chi tiết học viên</h5>
                            </div>
                            <form method="post" action="{{ route('student.updateCoachStudent', $user->id) }}">
                                @csrf
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
                                            <td>Trạng thái</td>
                                            <td>{!! \App\Model\User::renderTextActive($user->active) !!}</td>
                                        </tr>
                                        @if(Auth::user()->type == \App\Model\User::TYPE_ADMIN)
                                        <tr>
                                            <td>Học viên của</td>
                                            <td>
                                                <select name="coach" class="form-control">
                                                    <option value="0">--Mời chọn trợ giảng--</option>
                                                    @foreach($list_coach as $coach)
                                                        <option value="{{ $coach->id }}" <?php if($user->user_owner == $coach->id) { echo "selected";}?>>{{ $coach->name .' - ' .$coach->email }}</option>
                                                    @endforeach                 
                                                </select>
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td>
                                                Chi tiết quá trình học
                                            </td>
                                            <td>
                                                @if(!$timeline->isEmpty())
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <!-- The time line -->
                                                            <div class="timeline">
                                                                <!-- timeline time label -->
                                                                <div class="time-label">
                                                                    <span class="bg-secondary">
                                                                        {{ \Carbon\Carbon::now()->format('d-m-Y') }}
                                                                    </span>
                                                                </div>
                                                                @foreach($timeline as $item)
                                                                    <div>
                                                                        <span class="badge badge-info">
                                                                            {{ formatDateTime($item->date_join) }}
                                                                        </span>
                                                                        <div class="timeline-item">

                                                                            <h3 class="timeline-header">
                                                                                {{ $item->course->name }}
                                                                            </h3>
                                                                            <div class="timeline-body">
                                                                                <p>Tình
                                                                                    trạng: {!! $item->renderStatusToText($item->status) !!}</p>
                                                                                <p>Điểm đánh giá (%):
                                                                                    @if($item->status === \App\Model\CourseStudent::STATUS_CANCEL)
                                                                                        <span
                                                                                            class="badge badge-success">{{ $item->rate }}</span>
                                                                                    @else
                                                                                        <span class="badge badge-danger">
                                                                                            Chưa đánh giá!
                                                                                        </span>
                                                                                    @endif
                                                                                </p>
                                                                            </div>
                                                                            @if(Auth::user()->isCoach() || Auth::user()->isAdmin())
                                                                            <div class="text-right pb-2 pr-2">
                                                                            	<button type="button" class="btn btn-xs btn-danger delete"
				                                                                    data-link="{{route('student.delete.lesson',$item->id)}}"
				                                                                    title="Xóa">
					                                                                <i class="fas fa-trash"></i>
					                                                            </button>
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                                <div>
                                                                    <i class="fas fa-clock bg-gray"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('student.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-arrow-left"></i> Trở về
                                    </a>
                                    @if(Auth::user()->type == \App\Model\User::TYPE_ADMIN)
                                    <button type="submit" class="btn btn-success float-right">
                                        <i class="fas fa-save"></i> Cập nhật
                                    </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div>
    </div>
@endsection
