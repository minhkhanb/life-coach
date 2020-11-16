@extends('admin.master')
@section('title','Tạo tài khoản trợ giảng')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('coach.index') }}">Danh sách trợ giảng</a>
                            </li>
                            <li class="breadcrumb-item active">Thêm mới</li>
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
                            <form action="{{ route('coach.register') }}" method="post" id="formRegisterCoach">
                                <div class="card-header">
                                    <h5 class="m-0">Thêm mới tài khoản trợ giảng</h5>
                                </div>
                                <div class="card-body">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="" class="col-sm-2 col-form-label">
                                            Họ tên
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                   name="name" value="{{ old('name') }}" placeholder="Họ tên">
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-sm-2 col-form-label">
                                            Số điện thoại
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="text"
                                                   value="{{ old('phone') }}"
                                                   class="form-control @error('phone') is-invalid @enderror"
                                                   name="phone"
                                                   placeholder="Số điện thoại(tài khoản đăng nhập)"
                                                   id="phone_number" 
                                                   onkeyup="return validatePhone(this.value)">
                                            @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-sm-2 col-form-label">
                                            Số CMND
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="text"
                                                   class="form-control @error('identity_card') is-invalid @enderror"
                                                   name="identity_card" value="{{ old('identity_card') }}"
                                                   placeholder="Chứng minh nhân dân"
                                                   id="identify_number" onkeyup="return validateIdentify(this.value)">
                                            @error('identity_card')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-sm-2 col-form-label">
                                            Email
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control @error('email') is-invalid @enderror"
                                                   name="email" value="{{ old('email') }}" placeholder="Email">
                                            @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success float-right btn-sm">
                                        <i class="fas fa-save"></i> Thêm mới
                                    </button>
                                    <a href="{{ route('coach.index') }}"
                                       class="btn btn-secondary btn-sm">
                                        <i class="fas fa-arrow-circle-left"></i>
                                        Trở về
                                    </a>
                                </div>
                            </form>
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
@section('js')
    @include('admin.dashboard.validate')
@endsection
<script type="text/javascript">
    function validatePhone(number){
        number = number.replace(/[^0-9]/g, '');
        $("#phone_number").val(number);
    }
    function validateIdentify(number){
        number = number.replace(/[^0-9]/g, '');
        $("#identify_number").val(number);
    }
</script>