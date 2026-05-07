@extends('layouts.app')

@section('title', 'Daftar Pendaftaran')
@section('page-title', 'Daftar Pendaftaran Dokumentu')

@section('content')

{{-- Filter --}}
<div class="card stat-card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('pendaftaran.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <label class="form-label form-label-sm text-muted">Peskiza</label>
                <input type="text" name="cari" class="form-control form-control-sm" placeholder="Naran / No. Rejistu / BI..." value="{{ request('cari') }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label form-label-sm text-muted">Jenis Dokumentu</label>
                <select name="jenis_dokumen" class="form-select form-select-sm">
                    <option value="">Hotu</option>
                    @foreach($jenisDokumen as $key => $label)
                        <option value="{{ $key }}" {{ request('jenis_dokumen') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label form-label-sm text-muted">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Hotu</option>
                    <option value="halo_foun" {{ request('status') === 'halo_foun' ? 'selected' : '' }}>Halo Foun</option>
                    <option value="renova"    {{ request('status') === 'renova'    ? 'selected' : '' }}>Renova</option>
                    <option value="lakon"     {{ request('status') === 'lakon'     ? 'selected' : '' }}>Lakon</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label form-label-sm text-muted">Husi Data</label>
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label form-label-sm text-muted">Too Data</label>
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
            </div>
            <div class="col-12 col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-search"></i></button>
                <a href="{{ route('pendaftaran.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times"></i></a>
            </div>
        </form>
    </div>
</div>

{{-- Header Aksi --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="text-muted small">
        Haree <strong>{{ $pendaftaran->firstItem() ?? 0 }}–{{ $pendaftaran->lastItem() ?? 0 }}</strong>
        husi <strong>{{ $pendaftaran->total() }}</strong> rejistu
    </div>
    <a href="{{ route('pendaftaran.create') }}" class="btn btn-danger btn-sm">
        <i class="fas fa-plus me-1"></i> Rejistu Foun
    </a>
</div>

{{-- Tabel --}}
<div class="card stat-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th width="40">#</th>
                    <th>No. Rejistu</th>
                    <th>Naran Kompletu</th>
                    <th>Dokumentu</th>
                    <th>Status</th>
                    <th>Data Rejistu</th>
                    <th>Petugás</th>
                    <th width="100">Aksaun</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendaftaran as $i => $p)
                <tr>
                    <td class="text-muted small">{{ $pendaftaran->firstItem() + $i }}</td>
                    <td><code class="text-danger">{{ $p->no_registrasi }}</code></td>
                    <td>
                        <strong>{{ $p->nama_lengkap }}</strong>
                        @if($p->no_bi)
                            <br><small class="text-muted">BI: {{ $p->no_bi }}</small>
                        @endif
                    </td>
                    <td>
                        <span class="badge" style="background:{{ ['passaporte'=>'#3b5bdb','bi'=>'#0ca678','rejistu_kriminal'=>'#e8590c','rdtl'=>'#862e9c','eleitoral'=>'#1971c2','sim'=>'#f08c00'][$p->jenis_dokumen] ?? '#666' }}">
                            {{ $p->label_jenis_dokumen }}
                        </span>
                        @if($p->jenis_dokumen === 'sim' && $p->kategori_sim)
                            <small class="text-muted ms-1">({{ $p->kategori_sim }})</small>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ $p->badge_status }}">{{ $p->label_status }}</span>
                    </td>
                    <td class="text-nowrap">{{ $p->tanggal_daftar->format('d/m/Y') }}</td>
                    <td class="text-muted small">{{ $p->petugas ?? '—' }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('pendaftaran.show', $p->id) }}" class="btn btn-xs btn-outline-primary" title="Detail" style="font-size:.75rem;padding:.2rem .5rem;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('pendaftaran.edit', $p->id) }}" class="btn btn-xs btn-outline-warning" title="Edit" style="font-size:.75rem;padding:.2rem .5rem;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('pendaftaran.destroy', $p->id) }}" onsubmit="return confirm('Hamoos rejistu ida ne\'e?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-xs btn-outline-danger" title="Hamoos" style="font-size:.75rem;padding:.2rem .5rem;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                        La iha rejistu ne'ebé hetan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pendaftaran->hasPages())
    <div class="card-footer bg-white border-0">
        {{ $pendaftaran->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection
