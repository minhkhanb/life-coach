<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Đăng ký tài khoản</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" href="/public/theme_admin/vendor/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/public/theme_admin/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <style>
        .register-logo b {
            font-size: 32px;
            text-transform: uppercase;
            font-weight: 600;
            color: #656565;
            margin-top: 1rem;
        }
        .input-group {
            position: relative;
        }
        label.error {
            position: absolute;
            font-weight: 500 !important;
            top: 2.3rem;
            color: red;
            font-size: 0.75rem;
        }
        .mb-3, .my-3 {
            margin-bottom: 1.25rem!important;
        }
    </style>
</head>
<body class="hold-transition register-page">
<div class="register-box">

    <div class="card">
        <div class="register-logo">
            <b>Đăng ký tài khoản</b>
        </div>
        <div class="card-body register-card-body">
            @if($errors->has('error'))
                <div class="text-danger">{{$errors->first('error')}}</div>
            @endif
            <form action="{{ route('register.store') }}" method="post" id="form_register">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Họ tên"
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <input type="text" name="uid" value="{{ $uid }}" style="display: none;">

                <div class="input-group mb-3">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                            name="email" 
                            value="{{ old('email') }}"
                            placeholder="Email"
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input  type="text" name="phone"
                            minlength="8" maxlength="15"
                            class="form-control @error('phone') is-invalid @enderror" 
                            value="{{ old('phone') }}"
                            placeholder="Số điện thoại"
                            required
                            id="phone_number" 
                            onkeyup="return validatePhone(this.value)" 

                    />
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-phone"></span>
                        </div>
                    </div>
                    @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="text" class="form-control @error('identity_card') is-invalid @enderror" name="identity_card" value="{{ old('identity_card') }}" placeholder="Chứng minh nhân dân" id="identify_number" onkeyup="return validateIdentify(this.value)" >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-address-card"></span>
                        </div>
                    </div>
                    @error('identity_card')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" 
                            placeholder="Mật khẩu"
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="password" class="form-control @error('confirm_password') is-invalid @enderror"
                            name="confirm_password" 
                            placeholder="Xác nhận mật khẩu"
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('confirm_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!-- <div class="input-group mb-3">
                    <input type="password" class="form-control"
                            name="confirm_password" 
                            placeholder="Retype password"
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-danger btn-block" style="border-radius: 2rem;">Đăng ký</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <br>
                        <p class="text-center">Nếu bạn đã có tài khoản, hãy <a href="login" style="font-weight: bold;">Đăng nhập</a></p>
                    </div>
                </div>
            </form>

            {{-- <div class="social-auth-links text-center"> --}}
                {{-- <p>- OR -</p>
                <a href="#" class="btn btn-block btn-primary">
                    <i class="fab fa-facebook mr-2"></i>
                    Sign up using Facebook
                </a>
                <a href="#" class="btn btn-block btn-danger">
                    <i class="fab fa-google-plus mr-2"></i>
                    Sign up using Google+
                </a> --}}
            {{-- </div>
            <a href="login" class="text-center">Đã có tài khoản thành viên</a> --}}
        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
</div>
<!-- /.register-box -->
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

<!-- jQuery -->
<script src="/public/theme_admin/vendor/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/public/theme_admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/public/theme_admin/js/adminlte.min.js"></script>
<script src="/public/theme_admin/js/jquery.validate.min.js"></script>
</body>
<script type="text/javascript">
    $(document).ready(function(){
        $('#form_register').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 6
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true,
                    minlength: 8,
                    maxlength: 15
                },
                identity_card: {
                    required: true,
                    minlength: 8,
                    maxlength: 15
                },
                password: {
                    required: true,
                    minlength: 6
                },
                confirm_password: {
                    required: true,
                    minlength: 6
                }
            },
            messages: {
                name: {
                    required: "Họ tên không được bỏ trống",
                    minlength: "Họ tên phải lớn hơn 6 ký tự"
                },
                email: {
                    required: "Email không được bỏ trống",
                    email: "Phải nhập đúng định dạng email"
                },
                phone: {
                    required: "Số điện thoại không được bỏ trống",
                    minlength: "Số điện thoại phải lớn hơn 8 ký tự",
                    maxlength: "Số điện thoại phải ít hơn 15 ký tự"
                },
                identity_card: {
                    required: "Số chứng minh nhân dân không được bỏ trống",
                    minlength: "Số chứng minh nhân dân phải lớn hơn 8 ký tự",
                    maxlength: "Số chứng minh nhân dâ phải ít hơn 15 ký tự"
                },
                password: {
                    required: "Mật khẩu không được bỏ trống",
                    minlength: "Mật khẩu lớn hơn 6 ký tự",
                },
                confirm_password: {
                    required: "Mật khẩu không được bỏ trống",
                    minlength: "Mật khẩu lớn hơn 6 ký tự",
                }
            },
        });
    });
</script>
</html>
