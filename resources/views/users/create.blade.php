@extends('layouts.app')

@section('title', 'Utilizadór Foun')
@section('page-title', 'Kria Utilizadór Foun')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card stat-card">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-semibold mb-0"><i class="fas fa-user-plus me-2 text-danger"></i>Formuláriu Utilizadór Foun</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Naran <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="Naran kompletu">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="email@domain.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder="Mínimu 6 karakter">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Konfirma Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repete password">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Papel (Role) <span class="text-danger">*</span></label>
                            <select name="role" id="roleSelect" class="form-select @error('role') is-invalid @enderror">
                                <option value="">— Hili Role —</option>
                                @foreach($roleLabels as $key => $label)
                                    <option value="{{ $key }}" {{ old('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6" id="munisipiuWrap" style="display:none;">
                            <label class="form-label fw-semibold">Munisipiu <span class="text-danger">*</span></label>
                            <select name="munisipiu" class="form-select @error('munisipiu') is-invalid @enderror">
                                <option value="">— Hili Munisipiu —</option>
                                @foreach($munisipiuList as $m)
                                    <option value="{{ $m }}" {{ old('munisipiu') === $m ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                            @error('munisipiu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Role info cards --}}
                    <div id="roleInfo" class="mb-3" style="display:none;"></div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="aktif" value="1" class="form-check-input" id="aktifCheck"
                                {{ old('aktif', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="aktifCheck">Konta Ativu</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Kansela
                        </a>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-save me-1"></i> Salva
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
const roleInfo = {
    super_admin:       { color:'#DC143C', icon:'fa-shield-alt', text:'Aksés kompletu ba hotu-hotu munisipiu no funsaun sistema.' },
    diretur_jeral:     { color:'#0aa2c0', icon:'fa-eye',        text:'Monitorizasaun deit (read-only) ba hotu-hotu dadus munisipiu. La bele kria, edita ka hamoos.' },
    diretor:           { color:'#f08c00', icon:'fa-eye',        text:'Bele haree deit (read-only) ba hotu-hotu dadus. La bele kria, edita ka hamoos.' },
    xefe_departamentu: { color:'#6c757d', icon:'fa-eye',        text:'Monitorizasaun deit (read-only) ba hotu-hotu dadus munisipiu. La bele kria, edita ka hamoos.' },
    user:        { color:'#1971c2', icon:'fa-map-marker-alt', text:'Aksés CRUD, maibé limitadu ba munisipiu ne\'ebé atribui deit.' },
};
const roleSelect = document.getElementById('roleSelect');
const muniWrap   = document.getElementById('munisipiuWrap');
const infoBox    = document.getElementById('roleInfo');

function updateRole() {
    const v = roleSelect.value;
    muniWrap.style.display = v === 'user' ? 'block' : 'none';
    if (v && roleInfo[v]) {
        const r = roleInfo[v];
        infoBox.style.display = 'block';
        infoBox.innerHTML = `<div class="alert alert-light border d-flex gap-3 align-items-start py-2" style="border-left:4px solid ${r.color} !important;">
            <i class="fas ${r.icon} mt-1" style="color:${r.color}"></i>
            <small>${r.text}</small>
        </div>`;
    } else {
        infoBox.style.display = 'none';
    }
}
roleSelect.addEventListener('change', updateRole);
updateRole();
</script>
@endpush
