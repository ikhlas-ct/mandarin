<div class="main-header-logo">
    <div class="logo-header" data-background-color="dark">
        @php
            $authUser = auth()->user();

            // Route dashboard & profil (hanya admin yang punya route saat ini)
            $dashboardRoute = $authUser->isAdmin() ? route('admin.dashboard') : '#';
            $profilRoute    = $authUser->isAdmin() ? route('admin.profil') : '#';

            // Data profil (nama, foto) sesuai role
            $profilData = match ($authUser->role) {
                'admin' => $authUser->admin,
                default => $authUser->pelajar,
            };

            $profilNama  = $profilData?->nama ?? $authUser->username;
            $profilEmail = $authUser->email ?? 'Tidak ada email';

            // Foto disimpan di storage/app/public (kolom `foto`)
            $profilFoto = !empty($profilData?->foto) && file_exists(storage_path('app/public/' . $profilData->foto))
                ? asset('storage/' . $profilData->foto)
                : asset('default-image/default-user.png');
        @endphp

        <a href="{{ $dashboardRoute }}" class="logo">
            {{-- Gunakan accessor logo_url agar path storage/ selalu benar --}}
            <img src="{{ $settings->logo_url }}" alt="Logo" height="20">
        </a>
        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
            <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
        </div>
        <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
    </div>
</div>

<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
    <div class="container-fluid">
        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
            <li class="nav-item topbar-user dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                        <img src="{{ $profilFoto }}" alt="Profile" class="avatar-img rounded-circle" />
                    </div>
                    <span class="profile-username">
                        <span class="op-7">Hi,</span>
                        <span class="fw-bold">{{ $profilNama }}</span>
                    </span>
                </a>

                <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                        <li>
                            <div class="user-box">
                                <div class="avatar-lg">
                                    <img src="{{ $profilFoto }}" alt="Profile" class="avatar-img rounded-circle" />
                                </div>
                                <div class="u-text">
                                    <h4>{{ $profilNama }}</h4>
                                    <p class="text-muted">{{ $profilEmail }}</p>
                                    @if ($authUser->isAdmin())
                                        <a href="{{ $profilRoute }}" class="btn btn-xs btn-secondary btn-sm">
                                            View Profile
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ $dashboardRoute }}">
                                Dashboard
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                                Logout
                            </a>
                            <form id="logout-form-header" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </div>
                </ul>
            </li>
        </ul>
    </div>
</nav>
