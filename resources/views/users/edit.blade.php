@extends('layouts.app')

@section('title', 'Edit Utilizadór')
@section('page-title', 'Edit Utilizadór')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card stat-card">
            <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0"><i class="fas fa-user-edit me-2 text-warning"></i>Edit: <strong>{{ $user->name }}</strong></h6>
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Fila
                </a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.update', $user->id) }}">
                    @csrf @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Naran <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password Foun <small class="text-muted">(kosong = la muda)</small></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder="Mínimu 6 karakter">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Konfirma Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repete password">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Papel (Role) <span class="text-danger">*</span></label>
                            <select name="role" id="roleSelect" class="form-select @error('role') is-invalid @enderror"
                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                @foreach($roleLabels as $key => $label)
                                    <option value="{{ $key }}" {{ old('role', $user->role) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($user->id === auth()->id())
                                <input type="hidden" name="role" value="{{ $user->role }}">
                                <small class="text-muted">La bele muda papel konta rasik.</small>
                            @endif
                            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6" id="munisipiuWrap">
                            <label class="form-label fw-semibold">Munisipiu</label>
                            <select name="munisipiu" class="form-select @error('munisipiu') is-invalid @enderror">
                                <option value="">— Hili Munisipiu —</option>
                                @foreach($munisipiuList as $m)
                                    <option value="{{ $m }}" {{ old('munisipiu', $user->munisipiu) === $m ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                            @error('munisipiu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" name="aktif" value="1" class="form-check-input" id="aktifCheck"
                                {{ $user->aktif ? 'checked' : '' }}
                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            <label class="form-check-label fw-semibold" for="aktifCheck">Konta Ativu</label>
                            @if($user->id === auth()->id())
                                <input type="hidden" name="aktif" value="1">
                            @endif
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Kansela
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i> Atualiza
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
const roleSelect = document.getElementById('roleSelect');
const muniWrap   = document.getElementById('munisipiuWrap');
function updateRole() {
    muniWrap.style.display = (roleSelect && roleSelect.value === 'user') ? 'block' : 'none';
}
if (roleSelect) roleSelect.addEventListener('change', updateRole);
updateRole();
</script>
@endpush
