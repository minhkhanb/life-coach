<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reset password</title>
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
<div class="register-box" style="width: 25rem;">

    <div class="card">
        <div class="register-logo">
            <b>Cập nhật mật khẩu mới</b>
        </div>
        <div class="card-body register-card-body">
            @if($errors->has('error'))
                <div class="text-danger">{{$errors->first('error')}}</div>
            @endif
            <form action="{{ route('resetPass') }}" method="post" id="form_register">
                @csrf
                
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

                <input type="text" name="uid" value="{{ $user->id }}" style="display: none;">
                <input type="text" name="code" value="{{ $code }}" style="display: none;">

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
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-danger btn-block" style="border-radius: 2rem;">Cập nhật</button>
                    </div>
                </div>
            </form>

        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
</div>

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
