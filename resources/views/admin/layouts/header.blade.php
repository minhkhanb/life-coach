<nav class="main-header navbar navbar-expand navbar-dark navbar-info">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                @if(Auth::user()->image)
                    <img
                        src="{{ asset('uploads/avatar/' . Auth::user()->image) }}"
                        style="width: 35px; height: 35px" alt="User Avatar" class="mr-3 img-circle">
                @else
                    <img
                        src="/public/theme_admin/img/user2-160x160.jpg"
                        style="width: 35px;" alt="User Avatar" class="mr-3 img-circle">
                @endif
                {{ Auth::user()->name }}
                <i class="fa fa-angle-down" aria-hidden="true"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="text-center">
                        @if(Auth::user()->image)
                            <img src="{{ asset('uploads/avatar/' . Auth::user()->image) }}" alt="User Avatar"
                                 class="mr-3 img-circle" style="height: 2.1rem;">
                        @else
                            <img
                                src="/public/theme_admin/img/user2-160x160.jpg"
                                style="height: 2.1rem;" alt="User Avatar" class="mr-3 img-circle">
                        @endif
                        <p>{{ Auth::user()->email    }}</p>
                    </div>
                    <!-- Message End -->
                </a>

                <div class="dropdown-divider"></div>
                <div style="padding: .25rem 1rem;">
                    <a href="{{ route('user.profile', Auth::user()->id) }}" class="btn btn-primary btn-sm mt-1 mb-1"
                       style="width: 100%;"> <i class="fas fa-sign-out-alt"></i> Cài đặt</a><br>
                    @if(Auth::user()->type == 2)
                        <a href="{{ route('coach.getAffiliate', Auth::user()->id) }}" class="btn btn-success btn-sm mt-1 mb-1"
                           style="width: 100%;"> <i class="fas fa-sign-out-alt"></i> Affiliate</a><br>
                    @endif
                    <a href="{{ route('logout') }}" class="btn btn-danger btn-sm mt-1 mb-1" style="width: 100%;"> <i
                            class="fas fa-sign-out-alt"></i> Đăng xuất</a><br>
                </div>
            </div>
        </li>
    </ul>
</nav>
