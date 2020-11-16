<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Đăng nhập</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link href="/public/theme_admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/theme_admin/css/adminlte.min.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <style>
        .login-logo b {
            font-size: 32px;
            text-transform: uppercase;
            font-weight: 600;
            color: #656565;
        }
        .login-logo {
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
<body class="hold-transition login-page">
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card">
        <div class="login-logo">
            <b>Đăng nhập</b>
        </div>
        <div class="card-body login-card-body">
            @if($errors->has('error'))
                <div class="text-danger" style="margin-bottom: 1rem;">{{$errors->first('error')}}</div>
            @endif
            @if (!empty($success))
            <div class="text-success">{{$success}}</div>
            @endif
            <form action="{{ route('login.store') }}" method="post" id="form_login">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                           placeholder="Tên đăng nhập hoặc Số điện thoại"
                           value="{{ old('username') }}" name="username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                    @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           name="password"
                           placeholder="Mật khẩu">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block" style="border-radius: 2rem;" id="btn_login">Đăng nhập</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-center" style="margin-top: 0.3rem;">
                        <a href="{{ route('forgotPassword') }}">Quên mật khẩu</a>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12">
                        <p class="text-center">Nếu bạn chưa có tài khoản, hãy <a href="register" style="font-weight: bold;">Đăng ký</a></p>
                    </div>
                </div>
            </form>

            {{-- <div class="social-auth-links text-center mb-3"> --}}
                {{-- <p>- OR -</p>
                <a href="#" class="btn btn-block btn-primary">
                    <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
                </a>
                <a href="#" class="btn btn-block btn-danger">
                    <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                </a> --}}
            {{-- </div> --}}
            <!-- /.social-auth-links -->

           {{--  <p class="mb-1">
                <a href="forgot-password.html">I forgot my password</a>
            </p> --}}
           {{--  <p class="mb-0">
                <a href="register" class="text-center">Đăng ký thành viên</a>
            </p> --}}
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

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
        $('#form_login').validate({
            rules: {
                username: {
                    required: true,
                    minlength: 4
                },
                password: {
                    required: true
                },
            },
            messages: {
                username: {
                    required: "Tên đăng nhập không được bỏ trống",
                    minlength: "Tên đăng nhập phải lớn hơn 4 ký tự"
                },
                password: {
                    required: "Mật khẩu không được bỏ trống"
                }
            },
        });
    });
</script>
</html>