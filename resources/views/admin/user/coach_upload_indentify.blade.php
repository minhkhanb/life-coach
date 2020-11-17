<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Upload chứng minh thư</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/public/theme_admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/theme_admin/css/adminlte.min.css">
    <style type="text/css">
        .login-logo b {
            font-size: 32px;
            text-transform: uppercase;
            font-weight: 600;
            color: #f56c6c
        }
        img {
            width: 200px;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card" style="padding: 2rem; width: 40rem; margin-left: -8rem;">
        @if(Auth::user()->image_identify && Auth::user()->image_identify_2)
        <div class="card-body login-card-body">
            <a href="#" style="font-size: 1.25rem;"><b>Chứng minh thư của bạn đang trong trạng thái chờ admin phê duyệt, vui lòng quay lại sau.</b></a>
        </div>
        @else
        <h4><b>Upload chứng minh thư cá nhân 2 mặt:</b></h4>
        <div class="card-body login-card-body">
            <form action="{{ route('UploadIdentify') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <input type="file" value="{{ old('image_1') }}" accept="image/png, image/jpeg, image/jpg" class="@error('image_1') is-invalid @enderror" name="image_1" required onchange="showPreview1(event);"><br>
                        <div class="preview" style="margin-top: 1rem;">
                            <img id="file-ip-1-preview">
                        </div>
                        @error('image_1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <input type="file" value="{{ old('image_2') }}" class="@error('image_2') is-invalid @enderror" accept="image/png, image/jpeg, image/jpg" name="image_2" required onchange="showPreview2(event);"><br>
                        <div class="preview" style="margin-top: 1rem;">
                            <img id="file-ip-2-preview">
                        </div>
                        @error('image_2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <br>
                
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Tải lên</button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>
</body>
</html>
<script type="text/javascript">
    function showPreview1(event){
        if(event.target.files.length > 0){
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("file-ip-1-preview");
            preview.src = src;
            preview.style.display = "block";
        }
    }
    function showPreview2(event){
        if(event.target.files.length > 0){
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("file-ip-2-preview");
            preview.src = src;
            preview.style.display = "block";
        }
    }
</script>