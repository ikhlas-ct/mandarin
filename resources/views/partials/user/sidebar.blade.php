<!-- Sidebar -->
@php
    $authUser = auth()->user();

    // Hanya route admin yang tersedia saat ini.
    $dashboardRoute = $authUser->isAdmin() ? route('admin.dashboard') : '#';
@endphp

<div class="sidebar-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
        <a href="{{ $dashboardRoute }}" class="logo d-flex align-items-center">
            {{-- Gunakan accessor logo_url agar path storage/ selalu benar --}}
            <img src="{{ $settings->logo_url }}"
                alt="navbar brand" class="navbar-brand" height="50" />
            <span class="ms-2 text-white">{{ $settings->nama ?? 'Nama Website' }}</span>
        </a>

        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
            </button>
            <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
            </button>
        </div>
        <button class="topbar-toggler more">
            <i class="gg-more-vertical-alt"></i>
        </button>
    </div>
    <!-- End Logo Header -->
</div>

<div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
        <ul class="nav nav-secondary">

            @if ($authUser->isAdmin())

                <!-- Dashboard -->
                <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Pengaturan Website -->
                <li class="nav-item {{ request()->routeIs('admin.pengaturan*') ? 'active' : '' }}">
                    <a href="{{ route('admin.pengaturan') }}">
                        <i class="fas fa-cogs"></i>
                        <p>Pengaturan Website</p>
                    </a>
                </li>

                <!-- Profil Saya -->
                <li class="nav-item {{ request()->routeIs('admin.profil*') ? 'active' : '' }}">
                    <a href="{{ route('admin.profil') }}">
                        <i class="fas fa-user-circle"></i>
                        <p>{{ $authUser->admin?->nama ?? 'Profil Saya' }}</p>
                    </a>
                </li>

            @endif

            <!-- Logout -->
            <li class="nav-item mt-4">
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <p>Logout</p>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>

        </ul>
    </div>
</div>
