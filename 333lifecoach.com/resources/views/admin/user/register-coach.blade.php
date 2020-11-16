@extends('admin.master')
@section('title','Đăng ký làm trợ giảng')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('user.profile', Auth::user()->id) }}">Thông tin</a></li>
                            <li class="breadcrumb-item active">Đăng ký làm trợ giảng</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-success card-outline">
                            <div class="card-body box-profile">
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
                                                    <img id="file-ip-1-preview" style="width: 20rem;">
                                                </div>
                                                @error('image_1')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-6">
                                                <input type="file" value="{{ old('image_2') }}" class="@error('image_2') is-invalid @enderror" accept="image/png, image/jpeg, image/jpg" name="image_2" required onchange="showPreview2(event);"><br>
                                                <div class="preview" style="margin-top: 1rem;">
                                                    <img id="file-ip-2-preview" style="width: 20rem;">
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
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
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