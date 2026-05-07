<!DOCTYPE html>
<html lang="pt-TL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rekapan Pendaftaran Dokumentu') — BU</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <style>
        :root {
            --rdtl-red:  #DC143C;
            --rdtl-gold: #F4C400;
            --rdtl-dark: #1a1a2e;
        }
        body { background: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            width: 260px; min-height: 100vh; background: var(--rdtl-dark);
            position: fixed; top: 0; left: 0; z-index: 1000;
            transition: width .3s;
        }
        .sidebar .brand {
            background: var(--rdtl-red);
            padding: 1rem 1.2rem;
            display: flex; align-items: center; gap: .75rem;
        }
        .sidebar .brand img { width: 40px; }
        .sidebar .brand-text { color: #fff; }
        .sidebar .brand-text h6 { margin: 0; font-weight: 700; font-size: .9rem; }
        .sidebar .brand-text small { font-size: .7rem; opacity: .85; }
        .sidebar .nav-link {
            color: rgba(255,255,255,.75); padding: .6rem 1.2rem;
            border-radius: 0; transition: .2s; font-size: .9rem;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,.12); border-left: 3px solid var(--rdtl-gold); }
        .sidebar .nav-heading { color: rgba(255,255,255,.4); font-size: .7rem; padding: 1rem 1.2rem .3rem; text-transform: uppercase; letter-spacing: .08em; }
        .main-content { margin-left: 260px; min-height: 100vh; }
        .topbar { background: #fff; border-bottom: 1px solid #e0e0e0; padding: .75rem 1.5rem; position: sticky; top: 0; z-index: 999; }
        .stat-card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
        .stat-card .icon-wrap { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
        .badge-passaporte  { background: #3b5bdb; }
        .badge-bi          { background: #0ca678; }
        .badge-rejistu_kriminal { background: #e8590c; }
        .badge-rdtl        { background: #862e9c; }
        .badge-eleitoral   { background: #1971c2; }
        .badge-sim         { background: #f08c00; }
        .table thead th { background: var(--rdtl-dark); color: #fff; font-size: .82rem; }
        @media (max-width: 768px) {
            .sidebar { width: 0; overflow: hidden; }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar">
    <div class="brand">
        <div>
            <svg width="38" height="38" viewBox="0 0 38 38">
                <circle cx="19" cy="19" r="19" fill="#DC143C"/>
                <text x="19" y="25" text-anchor="middle" fill="white" font-size="13" font-weight="bold">BU</text>
            </svg>
        </div>
        <div class="brand-text">
            <h6>Sistema Rekapan</h6>
            <small>Dokumentu Sivil RDTL</small>
        </div>
    </div>
    <nav class="pt-2">
        <div class="nav-heading">Menu Prinsipál</div>
        <a href="{{ route('dashboard') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt fa-fw"></i> Dashboard
        </a>
        <a href="{{ route('pendaftaran.index') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('pendaftaran.*') ? 'active' : '' }}">
            <i class="fas fa-file-alt fa-fw"></i> Pendaftaran
        </a>
        <a href="{{ route('rekap.index') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('rekap.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar fa-fw"></i> Rekapitulasaun
        </a>

        <div class="nav-heading">Dokumentu</div>
        <a href="{{ route('pendaftaran.index', ['jenis_dokumen' => 'passaporte']) }}" class="nav-link d-flex align-items-center gap-2">
            <i class="fas fa-passport fa-fw"></i> Passaporte
        </a>
        <a href="{{ route('pendaftaran.index', ['jenis_dokumen' => 'bi']) }}" class="nav-link d-flex align-items-center gap-2">
            <i class="fas fa-id-card fa-fw"></i> Bilhete Identidade
        </a>
        <a href="{{ route('pendaftaran.index', ['jenis_dokumen' => 'rejistu_kriminal']) }}" class="nav-link d-flex align-items-center gap-2">
            <i class="fas fa-gavel fa-fw"></i> Rejistu Kriminal
        </a>
        <a href="{{ route('pendaftaran.index', ['jenis_dokumen' => 'rdtl']) }}" class="nav-link d-flex align-items-center gap-2">
            <i class="fas fa-flag fa-fw"></i> Dokumentu RDTL
        </a>
        <a href="{{ route('pendaftaran.index', ['jenis_dokumen' => 'eleitoral']) }}" class="nav-link d-flex align-items-center gap-2">
            <i class="fas fa-vote-yea fa-fw"></i> Kartaun Eleitoral
        </a>
        <a href="{{ route('pendaftaran.index', ['jenis_dokumen' => 'sim']) }}" class="nav-link d-flex align-items-center gap-2">
            <i class="fas fa-car fa-fw"></i> SIM
        </a>

        @if(auth()->user()->isSuperAdmin())
        <div class="nav-heading">Administrasaun</div>
        <a href="{{ route('users.index') }}" class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="fas fa-users-cog fa-fw"></i> Manajemen Utilizadór
        </a>
        @endif

        <div class="nav-heading">Konta</div>
        <div class="px-3 py-2">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                    style="width:32px;height:32px;font-size:.7rem;background:{{ ['super_admin'=>'#DC143C','diretor'=>'#f08c00','user'=>'#1971c2'][auth()->user()->role] ?? '#666' }};flex-shrink:0;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div style="min-width:0;">
                    <div class="text-white small fw-semibold text-truncate" style="font-size:.82rem;">{{ auth()->user()->name }}</div>
                    <div style="font-size:.68rem;">
                        <span class="badge bg-{{ auth()->user()->badge_role }}" style="font-size:.65rem;">{{ auth()->user()->label_role }}</span>
                        @if(auth()->user()->isUser() && auth()->user()->munisipiu)
                            <span class="ms-1 text-white opacity-75">{{ auth()->user()->munisipiu }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm w-100" style="background:rgba(255,255,255,.12);color:rgba(255,255,255,.85);font-size:.78rem;">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </button>
            </form>
        </div>
    </nav>
</aside>

<div class="main-content">
    <div class="topbar d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <h6 class="mb-0 fw-bold text-secondary">@yield('page-title', 'Dashboard')</h6>
        </div>
        <div class="d-flex align-items-center gap-3">
            @if(auth()->user()->isUser())
                <span class="badge" style="background:#e8f4fd;color:#0a58ca;">
                    <i class="fas fa-map-marker-alt me-1"></i>{{ auth()->user()->munisipiu }}
                </span>
            @endif
            <span class="text-muted small d-none d-md-inline"><i class="fas fa-calendar-alt me-1"></i>{{ now()->format('d/m/Y') }}</span>
            @if(auth()->user()->canWrite())
            <a href="{{ route('pendaftaran.create') }}" class="btn btn-sm btn-danger">
                <i class="fas fa-plus me-1"></i> Rejistu Foun
            </a>
            @endif
        </div>
    </div>

    <div class="p-4">
        @if(session('sukses'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('sukses') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
@stack('scripts')
</body>
</html>
