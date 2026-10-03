@extends('layouts.user.user')

@section('title', 'Data Anak Asuh')

@section('styles')
    <style>
        /* ===== IMPORT FONT ===== */
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body,
        .card,
        .table,
        .btn,
        h4,
        h5 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            border: none;
            border-radius: 16px;
            padding: 20px;
            position: relative;
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .07);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            right: -18px;
            top: -18px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            opacity: .12;
        }

        .stat-card.blue {
            background: linear-gradient(135deg, #e8f0fe 0%, #dbeafe 100%);
        }

        .stat-card.blue::after {
            background: #1a73e8;
        }

        .stat-card.green {
            background: linear-gradient(135deg, #e6f9f0 0%, #d1fae5 100%);
        }

        .stat-card.green::after {
            background: #16a34a;
        }

        .stat-card.orange {
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
        }

        .stat-card.orange::after {
            background: #ea580c;
        }

        .stat-card.purple {
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
        }

        .stat-card.purple::after {
            background: #7c3aed;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .stat-icon.blue {
            background: #1a73e8;
            color: #fff;
        }

        .stat-icon.green {
            background: #16a34a;
            color: #fff;
        }

        .stat-icon.orange {
            background: #ea580c;
            color: #fff;
        }

        .stat-icon.purple {
            background: #7c3aed;
            color: #fff;
        }

        .stat-value {
            font-size: 1.85rem;
            font-weight: 800;
            line-height: 1;
            color: #1e293b;
        }

        .stat-label {
            font-size: .78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #64748b;
            margin-top: 3px;
        }

        /* ===== PAGE HEADER ===== */
        .page-header-title {
            font-size: 1.45rem;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -.3px;
        }

        .breadcrumb-item a {
            color: #1a73e8;
            text-decoration: none;
            font-size: .82rem;
        }

        .breadcrumb-item.active {
            color: #64748b;
            font-size: .82rem;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            color: #94a3b8;
        }

        /* ===== FILTER & TABLE CARD ===== */
        .filter-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .07);
            overflow: hidden;
        }

        .filter-card .card-header {
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            padding: 18px 24px;
        }

        .filter-card .card-header h5 {
            font-size: .95rem;
            font-weight: 700;
            color: #1e293b;
        }

        .filter-section {
            background: #fafbfc;
            border-bottom: 1px solid #f1f5f9;
            padding: 16px 24px;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            font-size: .83rem;
            padding: 7px 12px;
            color: #334155;
            background-color: #f8fafc;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #1a73e8;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26, 115, 232, .12);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .input-group .input-group-text {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-right: none;
            border-radius: 10px 0 0 10px;
            color: #94a3b8;
            font-size: .8rem;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        /* ===== TABLE ===== */
        .table thead th {
            background: #f8fafc;
            color: #64748b;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            padding: 12px 16px;
            border-bottom: 2px solid #e2e8f0;
            border-top: none;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 13px 16px;
            vertical-align: middle;
            font-size: .85rem;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table-hover tbody tr:hover td {
            background: #f8fafc;
        }

        /* ===== AVATAR ===== */
        .avatar-sm {
            width: 38px;
            height: 38px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e2e8f0;
        }

        .avatar-placeholder {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .name-text {
            font-weight: 600;
            font-size: .85rem;
            color: #1e293b;
        }

        .nik-text {
            font-size: .72rem;
            color: #94a3b8;
        }

        /* ===== BADGES ===== */
        .badge {
            font-size: .7rem;
            font-weight: 600;
            padding: 4px 9px;
            border-radius: 6px;
            letter-spacing: .2px;
        }

        .badge-aktif {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-nonaktif {
            background: #f1f5f9;
            color: #64748b;
        }

        .badge-keluar {
            background: #fee2e2;
            color: #dc2626;
        }

        .badge-laki {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-perempuan {
            background: #fce7f3;
            color: #be185d;
        }

        .badge-dalam {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-luar {
            background: #f1f5f9;
            color: #64748b;
        }

        .badge-yatim {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-yatim-piatu {
            background: #fee2e2;
            color: #dc2626;
        }

        .badge-dhuafa {
            background: #f1f5f9;
            color: #64748b;
        }

        /* ===== ACTION BUTTONS ===== */
        .btn-action {
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: .75rem;
            padding: 0;
            border: none;
            transition: all .15s ease;
        }

        .btn-detail {
            background: #e0f2fe;
            color: #0369a1;
        }

        .btn-detail:hover {
            background: #0369a1;
            color: #fff;
        }

        .btn-edit {
            background: #fef9c3;
            color: #a16207;
        }

        .btn-edit:hover {
            background: #ca8a04;
            color: #fff;
        }

        .btn-hapus {
            background: #fee2e2;
            color: #dc2626;
        }

        .btn-hapus:hover {
            background: #dc2626;
            color: #fff;
        }

        /* ===== BTN PRIMARY ===== */
        .btn-primary {
            background: linear-gradient(135deg, #1a73e8, #1558b0);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: .83rem;
            padding: 8px 18px;
            box-shadow: 0 2px 8px rgba(26, 115, 232, .35);
            transition: all .2s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1558b0, #0f3e82);
            box-shadow: 0 4px 14px rgba(26, 115, 232, .45);
            transform: translateY(-1px);
        }

        .btn-outline-secondary {
            border-radius: 10px;
            font-size: .83rem;
            border-color: #e2e8f0;
            color: #64748b;
            padding: 7px 12px;
        }

        .btn-outline-secondary:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #334155;
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            padding: 60px 20px;
        }

        .empty-state-icon {
            width: 72px;
            height: 72px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .empty-state-icon i {
            font-size: 1.8rem;
            color: #94a3b8;
        }

        /* ===== FOOTER ===== */
        .card-footer {
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            padding: 12px 24px;
        }

        /* ===== ALERT ===== */
        .alert-success {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #15803d;
            border-radius: 12px;
            font-size: .85rem;
        }

        /* ===== PAGE HEADER CARD ===== */
        .ph-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 14px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 6px rgba(0,0,0,.05);
        }
        .ph-card::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 4px;
            border-radius: 14px 0 0 14px;
        }
        .ph-card.index-page::before { background: #1a73e8; }

        .ph-left { display: flex; align-items: center; gap: 12px; }

        .ph-icon {
            width: 42px; height: 42px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; flex-shrink: 0;
        }
        .ph-icon.index { background: #e8f0fe; color: #1a73e8; }

        .ph-title {
            font-size: 1.05rem; font-weight: 700;
            color: #1e293b; letter-spacing: -.2px; line-height: 1.2;
            margin: 0;
        }
        .ph-breadcrumb {
            display: flex; align-items: center; gap: 4px;
            flex-wrap: wrap; margin-top: 4px;
            list-style: none; padding: 0; margin-bottom: 0;
        }
        .ph-breadcrumb li { display: flex; align-items: center; }
        .ph-breadcrumb li + li::before {
            content: '›';
            color: #cbd5e1;
            font-size: .7rem;
            margin: 0 4px;
        }
        .ph-breadcrumb a          { font-size: .75rem; color: #1a73e8; text-decoration: none; }
        .ph-breadcrumb a:hover    { text-decoration: underline; }
        .ph-breadcrumb .bc-active  { font-size: .75rem; color: #94a3b8; }
    </style>
@endsection

@section('content')
    <div class="container">

     <div class="ph-card index-page">
    <div class="ph-left">
        <div class="ph-icon index"><i class="fas fa-users"></i></div>
        <div>
            <h5 class="ph-title">Daftar Anak Asuh</h5>
            <ol class="ph-breadcrumb" aria-label="breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><span class="bc-active">Anak Asuh</span></li>
            </ol>
        </div>
    </div>

</div>

        {{-- ===== PAGE-INNER – sama persis dengan kode asli ===== --}}
        <div class="page-inner">

            {{-- Alert --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- ===== STAT CARDS ===== --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card stat-card blue">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon blue"><i class="fas fa-users"></i></div>
                            <div>
                                <div class="stat-value">{{ $stats['total'] }}</div>
                                <div class="stat-label">Total Anak Asuh</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card green">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon green"><i class="fas fa-user-check"></i></div>
                            <div>
                                <div class="stat-value">{{ $stats['aktif'] }}</div>
                                <div class="stat-label">Aktif</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card orange">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon orange"><i class="fas fa-home"></i></div>
                            <div>
                                <div class="stat-value">{{ $stats['dalam'] }}</div>
                                <div class="stat-label">Tinggal Dalam</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card purple">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon purple"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <div class="stat-value">{{ $stats['luar'] }}</div>
                                <div class="stat-label">Tinggal Luar</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== FILTER & TABLE CARD ===== --}}
            <div class="card filter-card shadow-sm">

                {{-- Card Header --}}
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">
                        <i class="fas fa-list text-primary me-2 opacity-75"></i>Daftar Anak Asuh
                    </h5>
                     <a href="{{ route('anak-asuh.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Anak Asuh
            </a>
                </div>

                {{-- Filter --}}
                <div class="filter-section">
                    <form method="GET" action="{{ route('anak-asuh.index') }}">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari nama / NIK..." value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="col-6 col-sm-3 col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>
                                        Non-aktif</option>
                                    <option value="keluar" {{ request('status') === 'keluar' ? 'selected' : '' }}>Keluar
                                    </option>
                                </select>
                            </div>

                            <div class="col-6 col-sm-3 col-md-2">
                                <select name="jenis_tinggal" class="form-select">
                                    <option value="">Semua Tinggal</option>
                                    <option value="dalam" {{ request('jenis_tinggal') === 'dalam' ? 'selected' : '' }}>
                                        Dalam</option>
                                    <option value="luar" {{ request('jenis_tinggal') === 'luar' ? 'selected' : '' }}>
                                        Luar</option>
                                </select>
                            </div>

                            <div class="col-6 col-sm-3 col-md-2">
                                <select name="jenis_kelamin" class="form-select">
                                    <option value="">Semua JK</option>
                                    <option value="L" {{ request('jenis_kelamin') === 'L' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="P" {{ request('jenis_kelamin') === 'P' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                            </div>

                            @if (auth()->user()->isAdminDinsos())
                                <div class="col-6 col-sm-3 col-md-2">
                                    <select name="panti_asuhan_id" class="form-select">
                                        <option value="">Semua Panti</option>
                                        @foreach ($pantis as $panti)
                                            <option value="{{ $panti->id }}"
                                                {{ request('panti_asuhan_id') == $panti->id ? 'selected' : '' }}>
                                                {{ $panti->nama_panti }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                                <a href="{{ route('anak-asuh.index') }}" class="btn btn-outline-secondary btn-sm ms-1"
                                    title="Reset filter">
                                    <i class="fas fa-redo-alt"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Table --}}
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table-hover mb-0 table">
                            <thead>
                                <tr>
                                    <th width="40">#</th>
                                    <th>Nama</th>
                                    <th>JK</th>
                                    <th>Usia</th>
                                    <th>Status Yatim</th>
                                    <th>Tinggal</th>
                                    <th>Pendidikan</th>
                                    @if (auth()->user()->isAdminDinsos())
                                        <th>Panti</th>
                                    @endif
                                    <th>Status</th>
                                    <th width="110">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($anakAsuhs as $i => $anak)
                                    <tr>
                                        <td class="text-muted">{{ $anakAsuhs->firstItem() + $i }}</td>

                                        {{-- Nama --}}
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if ($anak->foto)
                                                    <img src="{{ asset('storage/' . $anak->foto) }}"
                                                        alt="{{ $anak->nama }}" class="avatar-sm">
                                                @else
                                                    <div
                                                        class="avatar-placeholder {{ $anak->jenis_kelamin === 'L'
                                                            ? 'bg-primary bg-opacity-10 text-primary'
                                                            : 'bg-danger bg-opacity-10 text-danger' }}">
                                                        {{ strtoupper(substr($anak->nama, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="name-text">{{ $anak->nama }}</div>
                                                    <div class="nik-text">{{ $anak->nik ?? '-' }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- JK --}}
                                        <td>
                                            <span
                                                class="badge {{ $anak->jenis_kelamin === 'L' ? 'badge-laki' : 'badge-perempuan' }}">
                                                {{ $anak->jenis_kelamin_label }}
                                            </span>
                                        </td>

                                        {{-- Usia --}}
                                        <td>
                                            <span class="fw-semibold">{{ $anak->usia ?? '-' }}</span>
                                            <span class="text-muted" style="font-size:.75rem;"> thn</span>
                                        </td>

                                        {{-- Status Yatim --}}
                                        <td>
                                            @php
                                                $yatimMap = [
                                                    'yatim' => ['label' => 'Yatim', 'cls' => 'badge-yatim'],
                                                    'piatu' => ['label' => 'Piatu', 'cls' => 'badge-yatim'],
                                                    'yatim_piatu' => [
                                                        'label' => 'Yatim Piatu',
                                                        'cls' => 'badge-yatim-piatu',
                                                    ],
                                                    'dhuafa' => ['label' => 'Dhuafa', 'cls' => 'badge-dhuafa'],
                                                ];
                                                $yt = $yatimMap[$anak->status_yatim] ?? [
                                                    'label' => $anak->status_yatim,
                                                    'cls' => 'badge-dhuafa',
                                                ];
                                            @endphp
                                            <span class="badge {{ $yt['cls'] }}">{{ $yt['label'] }}</span>
                                        </td>

                                        {{-- Tinggal --}}
                                        <td>
                                            <span
                                                class="badge {{ $anak->jenis_tinggal === 'dalam' ? 'badge-dalam' : 'badge-luar' }}">
                                                {{ ucfirst($anak->jenis_tinggal) }}
                                            </span>
                                        </td>

                                        {{-- Pendidikan --}}
                                        <td>{{ $anak->jenjang_pendidikan ?? '-' }}</td>

                                        {{-- Panti (Admin Dinsos) --}}
                                        @if (auth()->user()->isAdminDinsos())
                                            <td>
                                                <span class="text-muted" style="font-size:.8rem;">
                                                    {{ $anak->pantiAsuhan?->nama_panti ?? '-' }}
                                                </span>
                                            </td>
                                        @endif

                                        {{-- Status --}}
                                        <td>
                                            @php
                                                $statusMap = [
                                                    'aktif' => 'badge-aktif',
                                                    'nonaktif' => 'badge-nonaktif',
                                                    'keluar' => 'badge-keluar',
                                                ];
                                                $statusCls = $statusMap[$anak->status] ?? 'badge-nonaktif';
                                            @endphp
                                            <span class="badge {{ $statusCls }}">{{ ucfirst($anak->status) }}</span>
                                        </td>

                                        {{-- Aksi --}}
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('anak-asuh.show', $anak->id) }}"
                                                    class="btn btn-action btn-detail" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('anak-asuh.edit', $anak->id) }}"
                                                    class="btn btn-action btn-edit" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <button class="btn btn-action btn-hapus" data-id="{{ $anak->id }}"
                                                    data-nama="{{ $anak->nama }}" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <form id="form-hapus-{{ $anak->id }}"
                                                    action="{{ route('anak-asuh.destroy', $anak) }}" method="POST"
                                                    class="d-none">
                                                    @csrf @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->isAdminDinsos() ? 10 : 9 }}"
                                            class="p-0 text-center">
                                            <div class="empty-state">
                                                <div class="empty-state-icon">
                                                    <i class="fas fa-child"></i>
                                                </div>
                                                <div class="fw-semibold text-secondary mb-1">Belum ada data anak asuh</div>
                                                <div class="text-muted" style="font-size:.8rem;">
                                                    Coba ubah filter atau tambahkan data baru
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                @if ($anakAsuhs->hasPages())
                    <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <small class="text-muted">
                            Menampilkan
                            <strong>{{ $anakAsuhs->firstItem() }}</strong>–<strong>{{ $anakAsuhs->lastItem() }}</strong>
                            dari <strong>{{ $anakAsuhs->total() }}</strong> data
                        </small>
                        {{ $anakAsuhs->links() }}
                    </div>
                @endif

            </div>{{-- end .card --}}

        </div>{{-- end .page-inner --}}
    </div>{{-- end .container --}}
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.btn-hapus').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const nama = this.dataset.nama;
                swal({
                    title: 'Hapus Data?',
                    text: `Data anak asuh "${nama}" akan dihapus permanen.`,
                    icon: 'warning',
                    buttons: {
                        cancel: 'Batal',
                        confirm: {
                            text: 'Ya, Hapus!',
                            className: 'btn-danger'
                        }
                    },
                    dangerMode: true,
                }).then(confirmed => {
                    if (confirmed) {
                        document.getElementById('form-hapus-' + id).submit();
                    }
                });
            });
        });
    </script>
@endsection
