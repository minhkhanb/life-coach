@extends('admin.master')
@section('title','Link affiliate')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Trang chủ</a></li>
                            <li class="breadcrumb-item active">Coach</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-success card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Link affiliate</h5>
                            </div>
                            <form action="{{ route('coach.createAffiliate') }}" method="post">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-10">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ Auth::user()->name }}" placeholder="Tên coach">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-ellipsis-v"></span>
                                                    </div>
                                                </div>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <button type="submit" class="btn btn-success" style="width: 100%;">
                                                <i class="fas fa-save"></i> Cập nhật
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p><b>Link Affiliate của bạn là :</b></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-7">
                                            <input
                                                class="form-control"
                                                type="text"
                                                value="{{ $link_affiliate }}"
                                                id="link_affiliate">
                                        </div>
                                        <div class="col-5">
                                            <a class="btn btn-sm btn-primary d-inline" title="Sao chép" onclick="funCopy()">
                                                <i class="fas fa-copy" style="color: white; margin-top: 0.7rem;"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        function funCopy() {
            var copyText = document.getElementById("link_affiliate");
            console.log('copyText', copyText)
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            toastr.success('Copied: ' + copyText.value);
        }
    </script>
@endsection
