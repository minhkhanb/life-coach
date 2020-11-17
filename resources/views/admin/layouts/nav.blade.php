<aside class="main-sidebar sidebar-dark-info elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="/public/theme_admin/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">
            <b>SYSTEM COACHING</b>
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image" href="{{ route('user.profile', Auth::user()->id) }}">
                @if(Auth::user()->image)
                    <img src="{{ asset('uploads/avatar/' . Auth::user()->image) }}" style="height: 2.1rem"
                         class="img-circle elevation-2" alt="User Image">
                @else
                    <img src="/theme_admin/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                @endif
            </div>
            <div class="info">
                <a href="{{ route('user.profile',Auth::user()->id) }}" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ Route::is('dashboard')? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Trang chủ
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.profile',Auth::user()->id) }}"
                       class="nav-link {{ Route::is('user.profile')? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Tài khoản của tôi
                        </p>
                    </a>
                </li>
                @if(Auth::user()->isAdmin() || Auth::user()->isCoach())
                    <li class="nav-header">ADMIN - COACH</li>
                    <li class="nav-item">
                        <a href="{{ route('lesson.index') }}"
                           class="nav-link {{ Route::is('lesson.*')? 'active' : '' }}">
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>
                                Quản lý bài giảng
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('question.index') }}"
                           class="nav-link {{ Route::is('question.*')? 'active' : '' }}">
                            <i class="nav-icon fas fa-question-circle"></i>
                            <p>
                                Quản lý câu hỏi
                            </p>
                        </a>
                    </li>
                @endif

                @if(Auth::user()->isAdmin())
                    <li class="nav-item">
                        <a href="{{ route('coach.index') }}"
                           class="nav-link  {{ Route::is('coach.*')? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Quản lý trợ giảng
                            </p>
                        </a>
                    </li>
                @endif

                @if(Auth::user()->isAdmin() || Auth::user()->isCoach())
                    <li class="nav-item">
                        <a href="{{ route('student.index') }}"
                           class="nav-link {{ Route::is('student.*')? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Quản lý học viên
                            </p>
                        </a>
                    </li>
                @endif

                @if(Auth::user()->isCoach() || Auth::user()->isAdmin())
                    <li class="nav-item">
                        <a href="{{ route('learning.needReview') }}"
                           class="nav-link {{ Route::is('learning.needReview') || Route::is('learning.detailReview')? 'active' : '' }}">
                            <i class="nav-icon fas fa-check"></i>
                            <p>
                                Đánh giá bài giảng
                                <span class="badge badge-danger">{{ $countLessonNotReview }}</span>
                            </p>
                        </a>
                    </li>
                @endif

                @if(Auth::user()->isStudent())
                    <li class="nav-header">Học viên</li>
                    <li class="nav-item">
                        <a href="{{ route('learning.student.inProgress')  }}"
                           class="nav-link {{ Route::is('learning.student.*')? 'active' : '' }}">
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>
                                Bài giảng của bạn
                                <span class="right badge badge-danger" title="Bài tập chưa làm">{{ $countLessonOfStudent }}</span>
                            </p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
