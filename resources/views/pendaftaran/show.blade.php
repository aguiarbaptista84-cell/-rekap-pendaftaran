@extends('layouts.app')

@section('title', 'Detail Rejistu')
@section('page-title', 'Detail Pendaftaran')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card stat-card mb-3">
            <div class="card-header bg-white border-0 pt-3 pb-2 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-file-alt me-2 text-danger"></i>
                        {{ $data->label_jenis_dokumen }}
                    </h6>
                    <code class="text-muted small">{{ $data->no_registrasi }}</code>
                </div>
                <span class="badge bg-{{ $data->badge_status }} fs-6">{{ $data->label_status }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Naran Kompletu</div>
                        <div class="fw-semibold">{{ $data->nama_lengkap }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted small">Jenis Kelamin</div>
                        <div class="fw-semibold">{{ $data->jenis_kelamin === 'L' ? 'Mane' : 'Feto' }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted small">Data Moris</div>
                        <div class="fw-semibold">{{ $data->tanggal_lahir?->format('d/m/Y') ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Número BI</div>
                        <div class="fw-semibold">{{ $data->no_bi ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">No. Telemovel</div>
                        <div class="fw-semibold">{{ $data->no_telpon ?? '—' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Enderesu</div>
                        <div class="fw-semibold">{{ $data->alamat ?? '—' }}</div>
                    </div>
                </div>

                <hr>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-muted small">Jenis Dokumentu</div>
                        <div class="fw-semibold">{{ $data->label_jenis_dokumen }}</div>
                        @if($data->kategori_sim)
                            <small class="text-muted">Kategória: {{ $data->kategori_sim }}</small>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Data Rejistu</div>
                        <div class="fw-semibold">{{ $data->tanggal_daftar->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Data Remata</div>
                        <div class="fw-semibold">{{ $data->tanggal_selesai?->format('d/m/Y') ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Número Dokumentu</div>
                        <div class="fw-semibold">{{ $data->nomor_dokumen ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Petugás</div>
                        <div class="fw-semibold">{{ $data->petugas ?? '—' }}</div>
                    </div>
                    @if($data->keterangan)
                    <div class="col-12">
                        <div class="text-muted small">Nota</div>
                        <div class="fw-semibold">{{ $data->keterangan }}</div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-footer bg-white border-0 d-flex gap-2">
                <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Fila Bá Lista
                </a>
                <a href="{{ route('pendaftaran.edit', $data->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                <form method="POST" action="{{ route('pendaftaran.destroy', $data->id) }}" onsubmit="return confirm('Hamoos rejistu ida ne\'e?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-trash me-1"></i> Hamoos
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Update Status Sidebar --}}
    <div class="col-lg-4">
        <div class="card stat-card">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-semibold mb-0"><i class="fas fa-sync-alt me-2 text-info"></i>Atualiza Status</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('pendaftaran.status', $data->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Foun</label>
                        <select name="status" class="form-select">
                            @foreach(['halo_foun'=>'Halo Foun','renova'=>'Renova','lakon'=>'Lakon'] as $v => $t)
                                <option value="{{ $v }}" {{ $data->status === $v ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Número Dokumentu</label>
                        <input type="text" name="nomor_dokumen" class="form-control" value="{{ $data->nomor_dokumen }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Data Remata</label>
                        <input type="date" name="tanggal_selesai" class="form-control" value="{{ $data->tanggal_selesai?->format('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nota</label>
                        <textarea name="keterangan" class="form-control" rows="2">{{ $data->keterangan }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-info w-100 text-white">
                        <i class="fas fa-check me-1"></i> Atualiza
                    </button>
                </form>
            </div>
        </div>

        <div class="card stat-card mt-3">
            <div class="card-body">
                <div class="text-muted small mb-1">Inputu Husi</div>
                <div class="small fw-semibold">
                    @if($data->inputOleh)
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                style="width:28px;height:28px;font-size:.65rem;background:#1971c2;flex-shrink:0;">
                                {{ strtoupper(substr($data->inputOleh->name, 0, 2)) }}
                            </div>
                            <div>
                                <div>{{ $data->inputOleh->name }}</div>
                                @if($data->inputOleh->munisipiu)
                                    <small class="text-muted">{{ $data->inputOleh->munisipiu }}</small>
                                @endif
                            </div>
                        </div>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </div>
                <div class="text-muted small mt-3 mb-1">Kria iha</div>
                <div class="small fw-semibold">{{ $data->created_at->format('d/m/Y H:i') }}</div>
                <div class="text-muted small mt-2 mb-1">Atualiza ikus</div>
                <div class="small fw-semibold">{{ $data->updated_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
