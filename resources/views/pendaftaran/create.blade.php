@extends('layouts.app')

@section('title', 'Rejistu Foun')
@section('page-title', 'Rejistu Pendaftaran Foun')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card stat-card">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-semibold mb-0"><i class="fas fa-file-plus me-2 text-danger"></i>Formuláriu Rejistu Dokumentu Foun</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('pendaftaran.store') }}" id="formPendaftaran">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Naran Kompletu <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror"
                                value="{{ old('nama_lengkap') }}" placeholder="Naran kompletu tuir BI/Passaporte">
                            @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                                <option value="L" {{ old('jenis_kelamin','L') === 'L' ? 'selected' : '' }}>Mane (L)</option>
                                <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Feto (P)</option>
                            </select>
                            @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Número BI</label>
                            <input type="text" name="no_bi" class="form-control @error('no_bi') is-invalid @enderror"
                                value="{{ old('no_bi') }}" placeholder="ex: 1234567890">
                            @error('no_bi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Data Moris</label>
                            <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                value="{{ old('tanggal_lahir') }}">
                            @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Enderesu</label>
                            <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                                value="{{ old('alamat') }}" placeholder="Suku, Aldeia, Postu, Munisípiu">
                            @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">No. Telemovel</label>
                            <input type="text" name="no_telpon" class="form-control @error('no_telpon') is-invalid @enderror"
                                value="{{ old('no_telpon') }}" placeholder="77x xxx xxx">
                            @error('no_telpon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr class="my-3">
                    <h6 class="text-muted mb-3"><i class="fas fa-file-alt me-2"></i>Informasaun Dokumentu</h6>

                    <div class="row mb-3">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Jenis Dokumentu <span class="text-danger">*</span></label>
                            <select name="jenis_dokumen" id="jenisDokumen" class="form-select @error('jenis_dokumen') is-invalid @enderror">
                                <option value="">— Hili Dokumentu —</option>
                                @foreach($jenisDokumen as $key => $label)
                                    <option value="{{ $key }}" {{ old('jenis_dokumen') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('jenis_dokumen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3" id="simKategoriWrap" style="display:none;">
                            <label class="form-label fw-semibold">Kategória SIM</label>
                            <select name="kategori_sim" class="form-select @error('kategori_sim') is-invalid @enderror">
                                <option value="">— Hili —</option>
                                <option value="A" {{ old('kategori_sim') === 'A' ? 'selected' : '' }}>A - Mutiisiklu</option>
                                <option value="B" {{ old('kategori_sim') === 'B' ? 'selected' : '' }}>B - Karreta</option>
                                <option value="C" {{ old('kategori_sim') === 'C' ? 'selected' : '' }}>C - Kamiaun</option>
                                <option value="D" {{ old('kategori_sim') === 'D' ? 'selected' : '' }}>D - Omnibus</option>
                            </select>
                            @error('kategori_sim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="halo_foun" {{ old('status','halo_foun') === 'halo_foun' ? 'selected' : '' }}>Halo Foun</option>
                                <option value="renova"    {{ old('status') === 'renova'    ? 'selected' : '' }}>Renova</option>
                                <option value="lakon"     {{ old('status') === 'lakon'     ? 'selected' : '' }}>Lakon</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Data Rejistu <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_daftar" class="form-control @error('tanggal_daftar') is-invalid @enderror"
                                value="{{ old('tanggal_daftar', date('Y-m-d')) }}">
                            @error('tanggal_daftar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Data Remata</label>
                            <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                value="{{ old('tanggal_selesai') }}">
                            @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Número Dokumentu</label>
                            <input type="text" name="nomor_dokumen" class="form-control @error('nomor_dokumen') is-invalid @enderror"
                                value="{{ old('nomor_dokumen') }}" placeholder="Se iha ona">
                            @error('nomor_dokumen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Petugás</label>
                            <select name="petugas" class="form-select @error('petugas') is-invalid @enderror">
                                <option value="">— Hili Petugás —</option>
                                @foreach($petugas as $p)
                                    <option value="{{ $p->nama }}" {{ old('petugas') === $p->nama ? 'selected' : '' }}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                            @error('petugas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nota / Observasaun</label>
                            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="2" placeholder="Nota adisionál se iha...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Kansela
                        </a>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-save me-1"></i> Salva Rejistu
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

function toggleSim() {
    simWrap.style.display = jenisDokumenEl.value === 'sim' ? 'block' : 'none';
}
jenisDokumenEl.addEventListener('change', toggleSim);
toggleSim();
</script>
@endpush
