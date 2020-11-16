@extends('admin.master')
@section('title','Thông tin cá nhân')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#">Người dùng</a></li>
                            <li class="breadcrumb-item active">Thông tin</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <!-- Profile Image -->
                        <div class="card card-success card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    @if(Auth::user()->image)
                                        <img class="profile-user-img img-fluid img-circle"
                                             src="{{ asset('uploads/avatar/' . Auth::user()->image) }}"
                                             alt="User profile picture" style="height: 100px">
                                    @else
                                        <img class="profile-user-img img-fluid img-circle"
                                             src="/theme_admin/img/user2-160x160.jpg" alt="User profile picture">
                                    @endif
                                </div>

                                <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>

                                <p class="text-muted text-center">{{ Auth::user()->email }}</p>

                                <form action="{{ route('user.updatePassword',$user->id) }}" method="post"
                                      id="formChangePassword"
                                      class="form-horizontal" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <label>Mật khẩu mới<span class="text-danger">*</span></label>
                                            <input type="password" name="new_password"
                                                   id="new_password"
                                                   class="form-control @error('new_password') is-invalid @enderror"
                                                   value="{{ old('new_password') }}"
                                                   placeholder="Mật khẩu mới"
                                            >
                                            @error('new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <label>Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                            <input type="password" name="confirm_new_password"
                                                   class="form-control @error('confirm_new_password') is-invalid @enderror"
                                                   value="{{ old('confirm_new_password') }}"
                                                   placeholder="Xác nhận mật khẩu mới"
                                                   required
                                                   minlength="6"
                                            >
                                            @error('confirm_new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-success w-100">
                                                <i class="fas fa-save"></i>
                                                Thay đổi mật khẩu
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div><!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-8">
                        <div class="card card-success card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin của bạn</h3>
                            </div>
                            <form action="{{ route('user.update.profile',$user->id) }}" method="post"
                                  id="formProfileUser"
                                  class="form-horizontal" enctype="multipart/form-data">
                                <div class="card-body">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">
                                            Tên đăng nhập
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="text" name="phone"
                                                   readonly
                                                   class="form-control"
                                                   value="{{ $user->username }}"
                                                   placeholder="Tên đăng nhập"
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Số CMND</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="identity_card"
                                                   class="form-control @error('identity_card') is-invalid @enderror"
                                                   value="{{ $user->identity_card }}"
                                                   placeholder="Số chứng minh nhân dân"
                                                   id="identify_number" onkeyup="return validateIdentify(this.value)"
                                            />
                                            @error('identity_card')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">Họ tên</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="name"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   value="{{ old('name') ?? $user->name }}"
                                                   placeholder="Họ tên"
                                            >
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">Giới tính</label>
                                        <div class="col-sm-10">
                                            <select name="gender"
                                                    class="form-control @error('gender') is-invalid @enderror"
                                                    placeholder="Giới tính"
                                            >
                                                <option
                                                    value="0" {{ $user->gender == '0' ? 'selected="selected"' : '' }}>
                                                    Nữ
                                                </option>
                                                <option
                                                    value="1" {{ $user->gender == '1' ? 'selected="selected"' : '' }}>
                                                    Nam
                                                </option>
                                            </select>
                                            @error('full_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   value="{{ old('email') ?? $user->email }}"
                                                   placeholder="Email"
                                            >
                                            @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Địa chỉ</label>
                                        <div class="col-sm-10">
                                            <input type="text"
                                                   name="address"
                                                   placeholder="Địa chỉ"
                                                   value="{{ old('address',$user->address) }}"
                                                   class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputExperience" class="col-sm-2 col-form-label">Link
                                            facebook</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="nick_fb"
                                                   class="form-control @error('nick_fb') is-invalid @enderror"
                                                   value="{{ $user->nick_fb }}"
                                                   placeholder="Link facebook"
                                            />
                                            @error('nick_fb')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputSkills" class="col-sm-2 col-form-label">Email
                                            facebook</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="email_fb"
                                                   class="form-control @error('email_fb') is-invalid @enderror"
                                                   value="{{ $user->email_fb }}"
                                                   placeholder="Email facebook"
                                            />
                                            @error('email_fb')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">Ảnh đại
                                            diện </label>
                                        <div class="col-sm-10">
                                            <input type="file" name="image"
                                                   class="form-control @error('image') is-invalid @enderror"
                                                   accept="image/png, image/jpeg, image/jpg"
                                                   placeholder="Hình ảnh học viên"
                                            >
                                            @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    @if(Auth::user()->type == \App\Model\User::TYPE_STUDENT)
                                    <a href="{{ route('user.registerCoach',$user->id) }}" class="btn btn-primary float-left" style="color: white;">
                                        <i class="fas fa-save"></i> Đăng ký làm coach
                                    </a>
                                    @endif
                                    <button type="submit" class="btn btn-success float-right">
                                        <i class="fas fa-save"></i> Cập nhật
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('js')
    @include('admin.user.validate')
@endsection
<script type="text/javascript">
    function validateIdentify(number){
        number = number.replace(/[^0-9]/g, '');
        $("#identify_number").val(number);
    }
</script>