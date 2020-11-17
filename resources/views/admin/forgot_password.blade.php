<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Quên mật khẩu</title>
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
            <b>Quên mật khẩu</b>
        </div>
        <div class="card-body login-card-body">
            @if($errors->has('error'))
                <div class="text-danger" style="margin-bottom: 1rem;">{{$errors->first('error')}}</div>
            @endif
            @if (!empty($success))
            <div class="text-success">{{$success}}</div>
            @endif
            <form action="{{ route('sendMailPassword') }}" method="post" id="form_login">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" class="form-control @error('email') is-invalid @enderror"
                           placeholder="Email"
                           value="{{ old('email') }}" name="email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block" style="border-radius: 2rem;" id="btn_login">Gửi email</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-center" style="margin-top: 0.3rem;">
                        <a href="{{ route('login') }}">Đăng nhập</a>
                    </div>
                </div>
            </form>

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
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                email: {
                    required: "Email không được bỏ trống",
                    email: "Phải nhập đúng định dạng email"
                }
            },
        });
    });
</script>
</html>
