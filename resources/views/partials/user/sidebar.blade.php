<!-- Sidebar -->
@php
    $authUser = auth()->user();

    $dashboardRoute = $authUser->isAdmin()
        ? route('admin.dashboard')
        : ($authUser->isPelajar() ? route('pelajar.dashboard') : '#');
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

                <!-- Kategori -->
                <li class="nav-item {{ request()->routeIs('admin.kategori*') ? 'active' : '' }}">
                    <a href="{{ route('admin.kategori.index') }}">
                        <i class="fas fa-tags"></i>
                        <p>Kategori</p>
                    </a>
                </li>

                <!-- Tingkat HSK -->
                <li class="nav-item {{ request()->routeIs('admin.level-hsk*') ? 'active' : '' }}">
                    <a href="{{ route('admin.level-hsk.index') }}">
                        <i class="fas fa-layer-group"></i>
                        <p>Tingkat HSK</p>
                    </a>
                </li>

                <!-- Kosakata -->
                <li class="nav-item {{ request()->routeIs('admin.kosakata*') ? 'active' : '' }}">
                    <a href="{{ route('admin.kosakata.index') }}">
                        <i class="fas fa-language"></i>
                        <p>Kosakata</p>
                    </a>
                </li>

                <!-- Grup Kosakata & Generator Soal -->
                <li class="nav-item {{ request()->routeIs('admin.grup-kosakata*') ? 'active' : '' }}">
                    <a href="{{ route('admin.grup-kosakata.index') }}">
                        <i class="fas fa-object-group"></i>
                        <p>Grup &amp; Soal Latihan</p>
                    </a>
                </li>

                <!-- Paragraf -->
                <li class="nav-item {{ request()->routeIs('admin.paragraf*') ? 'active' : '' }}">
                    <a href="{{ route('admin.paragraf.index') }}">
                        <i class="fas fa-paragraph"></i>
                        <p>Paragraf</p>
                    </a>
                </li>

                <!-- Pelajar -->
                <li class="nav-item {{ request()->routeIs('admin.pelajar*') ? 'active' : '' }}">
                    <a href="{{ route('admin.pelajar.index') }}">
                        <i class="fas fa-user-graduate"></i>
                        <p>Pelajar</p>
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

            @elseif ($authUser->isPelajar())

                <!-- Dashboard -->
                <li class="nav-item {{ request()->routeIs('pelajar.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('pelajar.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Kosakata -->
                <li class="nav-item {{ request()->routeIs('pelajar.kosakata*') ? 'active' : '' }}">
                    <a href="{{ route('pelajar.kosakata.index') }}">
                        <i class="fas fa-language"></i>
                        <p>Kosakata</p>
                    </a>
                </li>

                <!-- Flashcard -->
                <li class="nav-item {{ request()->routeIs('pelajar.flashcard*') ? 'active' : '' }}">
                    <a href="{{ route('pelajar.flashcard.index') }}">
                        <i class="fas fa-clone"></i>
                        <p>Flashcard</p>
                    </a>
                </li>

                <!-- Latihan & Ujian -->
                <li class="nav-item {{ request()->routeIs('pelajar.latihan*', 'pelajar.pengerjaan*') ? 'active' : '' }}">
                    <a href="{{ route('pelajar.latihan.index') }}">
                        <i class="fas fa-tasks"></i>
                        <p>Latihan &amp; Ujian</p>
                    </a>
                </li>

                <!-- Hafalan Saya -->
                <li class="nav-item {{ request()->routeIs('pelajar.hafalan*') ? 'active' : '' }}">
                    <a href="{{ route('pelajar.hafalan.index') }}">
                        <i class="fas fa-chart-pie"></i>
                        <p>Hafalan Saya</p>
                    </a>
                </li>

                <!-- Profil Saya (muncul otomatis setelah route pelajar.profil dibuat) -->
                @if (\Illuminate\Support\Facades\Route::has('pelajar.profil'))
                    <li class="nav-item {{ request()->routeIs('pelajar.profil*') ? 'active' : '' }}">
                        <a href="{{ route('pelajar.profil') }}">
                            <i class="fas fa-user-circle"></i>
                            <p>{{ $authUser->pelajar?->nama ?? 'Profil Saya' }}</p>
                        </a>
                    </li>
                @endif

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
    