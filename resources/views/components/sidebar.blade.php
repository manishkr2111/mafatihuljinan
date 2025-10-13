<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <div class="logo-icon-">
                <img src="{{asset('storage/website/mafa-logo.jpg')}}" width="30" height="30">
            </div>
            <span>Mafatihuljinan</span>
        </div>
        <div class="close-sidebar" id="closeSidebar">
            <i class="fas fa-times"></i>
        </div>
    </div>

    <div class="sidebar-content">
        <div class="sidebar-section">
            <div class="sidebar-section-title">Main</div>
            <ul class="sidebar-nav">
                <li class="sidebar-nav-item">
                    <a href="#">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-nav-item {{ Route::is('admin.menus.index') ? 'active' : '' }}">
                    <a href="{{route('admin.menus.index')}}">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                        <span>Menu Item</span>
                    </a>
                </li>
                <li class="sidebar-nav-item {{ Route::is('admin.hijri.date.event') ? 'active' : '' }}">
                    <a href="{{route('admin.hijri.date.event')}}">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        <span>Hijri Date/Event</span>
                    </a>
                </li>
                <li class="sidebar-nav-item {{ Route::is('admin.marquee.index') ? 'active' : '' }}"">
                    <a href=" {{route('admin.marquee.index')}}">
                    <i class="fa fa-file-text" aria-hidden="true"></i>
                    <span>Marquee Text</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">Management</div>
            <ul class="sidebar-nav">
                <li class="sidebar-nav-item">
                    <a href="#">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <span>Search Posts</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="#">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <li class="sidebar-nav-item {{ Route::is('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ Route('admin.users')}}">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="#">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">Support</div>
            <ul class="sidebar-nav">
                <li class="sidebar-nav-item">
                    <a href="#">
                        <i class="fas fa-question-circle"></i>
                        <span>Help Center</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="#">
                        <i class="fas fa-headset"></i>
                        <span>Contact Support</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="#">
                        <i class="fas fa-book"></i>
                        <span>Documentation</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="sidebar-footer">
        <div class="user-profile">
            <div class="user-avatar">
                <span>{{ Str::substr(Auth::user()->name, 0, 1) }}</span>
            </div>
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name}}</div>
                <div class="user-role">Admin</div>
            </div>
            <div class="user-menu">
                <i class="fas fa-ellipsis-v"></i>
            </div>
        </div>
        <!-- Always visible logout -->
        <div class="user-logout" style="margin-top: 10px;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>
</aside>