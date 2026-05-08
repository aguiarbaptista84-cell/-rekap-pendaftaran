@extends('layouts.app')

@section('title', 'Manajemen Utilizadór')
@section('page-title', 'Manajemen Utilizadór Sistema')

@section('content')

@php
    $byRole      = $users->groupBy('role');
    $admins      = $users->whereIn('role', ['super_admin', 'diretor']);
    $byMunisipiu = $users->where('role', 'user')->groupBy('munisipiu');
    $colors      = ['#DC143C', '#f08c00', '#1971c2'];
    function avatarBg($role) {
        return ['super_admin'=>'#DC143C','diretor'=>'#f08c00','user'=>'#1971c2'][$role] ?? '#666';
    }
@endphp

{{-- Header & Tombol Baru --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="text-muted small">Total: <strong>{{ $users->count() }}</strong> utilizadór</div>
    <a href="{{ route('users.create') }}" class="btn btn-danger btn-sm">
        <i class="fas fa-user-plus me-1"></i> Utilizadór Foun
    </a>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    @foreach(['super_admin'=>['danger','Super Admin','fa-shield-alt'],'diretor'=>['warning','Diretur','fa-eye'],'user'=>['primary','Utilizadór Munisipiu','fa-users']] as $r => [$c,$l,$ic])
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3 py-2">
                <div class="icon-wrap bg-{{ $c }} bg-opacity-15 text-{{ $c }}">
                    <i class="fas {{ $ic }}"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4 text-{{ $c }}">{{ isset($byRole[$r]) ? $byRole[$r]->count() : 0 }}</div>
                    <div class="text-muted small">{{ $l }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ===== TABEL ADMIN & DIRETOR ===== --}}
<div class="mb-4">
    <h6 class="fw-bold text-uppercase text-muted mb-2" style="letter-spacing:.05em;">
        <i class="fas fa-shield-alt me-1 text-danger"></i> Admin &amp; Diretur
        <span class="badge bg-secondary ms-1">{{ $admins->count() }}</span>
    </h6>
    <div class="card stat-card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th>Naran</th>
                        <th>Email</th>
                        <th>Papel (Role)</th>
                        <th>Asesu</th>
                        <th class="text-center">Status</th>
                        <th width="130">Aksaun</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $i => $u)
                    <tr class="{{ !$u->aktif ? 'table-secondary text-muted' : '' }}">
                        <td class="text-muted small">{{ $i + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                    style="width:32px;height:32px;font-size:.75rem;background:{{ ['super_admin'=>'#DC143C','diretor'=>'#f08c00','user'=>'#1971c2'][$u->role] ?? '#666' }}">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <span class="fw-semibold">{{ $u->name }}</span>
                                @if($u->id === auth()->id())
                                    <span class="badge bg-secondary" style="font-size:.65rem;">Ita Boot</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-muted small">{{ $u->email }}</td>
                        <td><span class="badge bg-{{ $u->badge_role }}">{{ $u->label_role }}</span></td>
                        <td>
                            @if($u->role === 'super_admin')
                                <span class="text-muted small"><i class="fas fa-globe me-1"></i>Hotu-hotu</span>
                            @else
                                <span class="text-muted small"><i class="fas fa-eye me-1"></i>Haree Hotu</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($u->aktif)
                                <span class="badge bg-success">Ativu</span>
                            @else
                                <span class="badge bg-secondary">La Ativu</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('users.edit', $u->id) }}" class="btn btn-xs btn-outline-warning" title="Edit" style="font-size:.75rem;padding:.2rem .5rem;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($u->id !== auth()->id())
                                <form method="POST" action="{{ route('users.toggle', $u->id) }}">
                                    @csrf
                                    <button class="btn btn-xs {{ $u->aktif ? 'btn-outline-secondary' : 'btn-outline-success' }}" title="{{ $u->aktif ? 'Desativu' : 'Ativu' }}" style="font-size:.75rem;padding:.2rem .5rem;">
                                        <i class="fas fa-{{ $u->aktif ? 'ban' : 'check' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('users.destroy', $u->id) }}" onsubmit="return confirm('Hamoos utilizadór {{ $u->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-outline-danger" title="Hamoos" style="font-size:.75rem;padding:.2rem .5rem;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">Laiha admin ka diretur registadu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ===== TABEL PER MUNISIPIU ===== --}}
<h6 class="fw-bold text-uppercase text-muted mb-3" style="letter-spacing:.05em;">
    <i class="fas fa-map-marker-alt me-1 text-primary"></i> Utilizadór Munisipiu
</h6>

@forelse($byMunisipiu->sortKeys() as $munisipiu => $muUsers)
<div class="mb-3">
    <div class="d-flex align-items-center gap-2 mb-1">
        <span class="fw-bold text-primary" style="font-size:.95rem;">
            <i class="fas fa-building me-1"></i>{{ $munisipiu ? 'BU ' . $munisipiu : 'Munisipiu La Defini' }}
        </span>
        <span class="badge bg-primary bg-opacity-15 text-primary">{{ $muUsers->count() }} utilizadór</span>
    </div>
    <div class="card stat-card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40">#</th>
                        <th>Naran</th>
                        <th>Email</th>
                        <th class="text-center">Status</th>
                        <th width="130">Aksaun</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($muUsers as $i => $u)
                    <tr class="{{ !$u->aktif ? 'table-secondary text-muted' : '' }}">
                        <td class="text-muted small">{{ $i + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                    style="width:32px;height:32px;font-size:.75rem;background:#1971c2">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <span class="fw-semibold">{{ $u->name }}</span>
                                @if($u->id === auth()->id())
                                    <span class="badge bg-secondary" style="font-size:.65rem;">Ita Boot</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-muted small">{{ $u->email }}</td>
                        <td class="text-center">
                            @if($u->aktif)
                                <span class="badge bg-success">Ativu</span>
                            @else
                                <span class="badge bg-secondary">La Ativu</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('users.edit', $u->id) }}" class="btn btn-xs btn-outline-warning" title="Edit" style="font-size:.75rem;padding:.2rem .5rem;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($u->id !== auth()->id())
                                <form method="POST" action="{{ route('users.toggle', $u->id) }}">
                                    @csrf
                                    <button class="btn btn-xs {{ $u->aktif ? 'btn-outline-secondary' : 'btn-outline-success' }}" title="{{ $u->aktif ? 'Desativu' : 'Ativu' }}" style="font-size:.75rem;padding:.2rem .5rem;">
                                        <i class="fas fa-{{ $u->aktif ? 'ban' : 'check' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('users.destroy', $u->id) }}" onsubmit="return confirm('Hamoos utilizadór {{ $u->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-outline-danger" title="Hamoos" style="font-size:.75rem;padding:.2rem .5rem;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@empty
<div class="text-center text-muted py-4">
    <i class="fas fa-users fa-2x mb-2 d-block opacity-25"></i>
    Laiha utilizadór munisipiu registadu.
</div>
@endforelse

@endsection
