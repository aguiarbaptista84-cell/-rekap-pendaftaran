@extends('layouts.app')

@section('title', 'Edit Rejistu')
@section('page-title', 'Edit Pendaftaran')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card stat-card">
            <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0"><i class="fas fa-edit me-2 text-warning"></i>Edit Rejistu: <code>{{ $data->no_registrasi }}</code></h6>
                <a href="{{ route('pendaftaran.show', $data->id) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Fila
                </a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('pendaftaran.update', $data->id) }}" id="formPendaftaran">
                    @csrf @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Naran Kompletu <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror"
                                value="{{ old('nama_lengkap', $data->nama_lengkap) }}">
                            @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                <option value="L" {{ old('jenis_kelamin', $data->jenis_kelamin) === 'L' ? 'selected' : '' }}>Mane (L)</option>
                                <option value="P" {{ old('jenis_kelamin', $data->jenis_kelamin) === 'P' ? 'selected' : '' }}>Feto (P)</option>
                            </select>
                            @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Número BI</label>
                            <input type="text" name="no_bi" class="form-control @error('no_bi') is-invalid @enderror"
                                value="{{ old('no_bi', $data->no_bi) }}">
                            @error('no_bi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Data Moris</label>
                            <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                value="{{ old('tanggal_lahir', $data->tanggal_lahir?->format('Y-m-d')) }}">
                            @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Enderesu</label>
                            <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                                value="{{ old('alamat', $data->alamat) }}">
                            @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">No. Telemovel</label>
                            <input type="text" name="no_telpon" class="form-control @error('no_telpon') is-invalid @enderror"
                                value="{{ old('no_telpon', $data->no_telpon) }}">
                            @error('no_telpon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr class="my-3">
                    <h6 class="text-muted mb-3"><i class="fas fa-file-alt me-2"></i>Informasaun Dokumentu</h6>

                    <div class="row mb-3">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Jenis Dokumentu <span class="text-danger">*</span></label>
                            <select name="jenis_dokumen" id="jenisDokumen" class="form-select @error('jenis_dokumen') is-invalid @enderror">
                                @foreach($jenisDokumen as $key => $label)
                                    <option value="{{ $key }}" {{ old('jenis_dokumen', $data->jenis_dokumen) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('jenis_dokumen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3" id="simKategoriWrap" style="display:none;">
                            <label class="form-label fw-semibold">Kategória SIM</label>
                            <select name="kategori_sim" class="form-select">
                                <option value="">— Hili —</option>
                                @foreach(['A'=>'A - Mutiisiklu','B'=>'B - Karreta','C'=>'C - Kamiaun','D'=>'D - Omnibus'] as $v => $t)
                                    <option value="{{ $v }}" {{ old('kategori_sim', $data->kategori_sim) === $v ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                @foreach(['halo_foun'=>'Halo Foun','renova'=>'Renova','lakon'=>'Lakon'] as $v => $t)
                                    <option value="{{ $v }}" {{ old('status', $data->status) === $v ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Data Rejistu <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_daftar" class="form-control @error('tanggal_daftar') is-invalid @enderror"
                                value="{{ old('tanggal_daftar', $data->tanggal_daftar->format('Y-m-d')) }}">
                            @error('tanggal_daftar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Data Remata</label>
                            <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                value="{{ old('tanggal_selesai', $data->tanggal_selesai?->format('Y-m-d')) }}">
                            @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Número Dokumentu</label>
                            <input type="text" name="nomor_dokumen" class="form-control @error('nomor_dokumen') is-invalid @enderror"
                                value="{{ old('nomor_dokumen', $data->nomor_dokumen) }}">
                            @error('nomor_dokumen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Petugás</label>
                            <select name="petugas" class="form-select">
                                <option value="">— Hili Petugás —</option>
                                @foreach($petugas as $pt)
                                    <option value="{{ $pt->nama }}" {{ old('petugas', $data->petugas) === $pt->nama ? 'selected' : '' }}>{{ $pt->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nota / Observasaun</label>
                            <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $data->keterangan) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('pendaftaran.show', $data->id) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Kansela
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i> Atualiza Rejistu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const jenisDokumenEl = document.getElementById('jenisDokumen');
const simWrap = document.getElementById('simKategoriWrap');
function toggleSim() { simWrap.style.display = jenisDokumenEl.value === 'sim' ? 'block' : 'none'; }
jenisDokumenEl.addEventListener('change', toggleSim);
toggleSim();
</script>
@endpush
