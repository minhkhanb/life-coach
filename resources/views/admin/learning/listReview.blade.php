@extends('admin.master')
@section('title','Quản lý khóa học')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="">Học viên</a></li>
                            <li class="breadcrumb-item">Tình trạng học</li>
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
                                <h5 class="m-0">Danh sách khóa học của bạn quản lý</h5>
                            </div>
                            <div class="card-body">
                                @include('admin.learning.search',['route' => route('learning.needReview')])
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Tên khóa học</th>
                                            <th>Học viên</th>
                                            <th class="text-center">Tình trạng hoàn thành(%)</th>
                                            <th>Chi tiết</th>
                                            <th>Hành động</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!$data->isEmpty())
                                            @foreach($data as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td class="text-truncate">
                                                        {{ $item->name }}
                                                    </td>
                                                    <td>{{ $item->name_user }}</td>
                                                    <td class="text-center">
                                                        @if($item->status === \App\Model\CourseStudent::STATUS_COMPLETE)
                                                            <span class="badge badge-danger">Chưa đánh giá</span>
                                                        @else
                                                            <span class="badge badge-success">{{ $item->rate }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($item->status === \App\Model\CourseStudent::STATUS_CANCEL)
                                                            <?php $detail_rate = json_decode($item->detail_rate, true); ?>
                                                            <p class="mb-0">Tự luận: {{ $detail_rate['tl'] }}</p>
                                                            <p>Trắc nghiệm: {{ $detail_rate['tn'] }}</p>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if($item->status === \App\Model\CourseStudent::STATUS_COMPLETE)
                                                            <a href="{{ route('learning.detailReview',['id' => $item->course_student_id,'slug'=>$item->slug]) }}"
                                                               class="btn btn-sm btn-info">
                                                                <i class="fas fa-angle-double-right"></i>
                                                                Đánh giá
                                                            </a>
                                                        @else
                                                            <span
                                                                class="badge badge-success">Đã hoàn thành đánh giá</span>
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
                                @if(!$data->isEmpty())
                                    {{ $data->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div>
    </div>
@endsection
@section('css')
@endsection
@section('js')
@endsection

