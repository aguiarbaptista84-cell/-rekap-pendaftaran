<!DOCTYPE html>
<html lang="pt-TL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistema Rekapan Dokumentu RDTL</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --rdtl-red: #DC143C; --rdtl-dark: #1a1a2e; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--rdtl-dark) 0%, #16213e 50%, #0f3460 100%);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-card {
            width: 100%; max-width: 430px;
            background: #fff; border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,.4);
            overflow: hidden;
        }
        .login-header {
            background: #fff;
            padding: 2rem 2rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        .login-header .logo-img {
            width: 110px; height: auto;
            margin: 0 auto .75rem; display: block;
        }
        .login-header .brand-name {
            margin: 0; font-weight: 800; font-size: 1.3rem;
            color: #1a1a2e; letter-spacing: .02em;
        }
        .login-header h5 {
            margin: .35rem 0 0; font-weight: 800; font-size: 1.05rem;
            color: #0f3460; letter-spacing: .08em; text-transform: uppercase;
        }
        .login-body { padding: 2rem; }
        .form-control:focus { border-color: var(--rdtl-red); box-shadow: 0 0 0 .2rem rgba(220,20,60,.15); }
        .btn-login {
            background: var(--rdtl-red); border: none; color: #fff;
            padding: .75rem; font-weight: 600; letter-spacing: .03em;
            transition: .2s;
        }
        .btn-login:hover { background: #a00e2b; color: #fff; }
        .role-badge {
            display: inline-block; padding: .2rem .6rem; border-radius: 20px;
            font-size: .72rem; font-weight: 600; margin: .1rem;
        }
        .rdtl-footer {
            background: #f8f8f8; border-top: 1px solid #eee;
            padding: 1rem; text-align: center;
        }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-header">
        <img src="{{ asset('images/logo.png') }}" alt="Balkaun Uniku" class="logo-img">
        <p class="brand-name">Balkaun Uniku</p>
        <h5>Sistema Atendementu</h5>
    </div>
    <div class="login-body">
        <h6 class="fw-semibold mb-4 text-center text-muted">Login ba Sistema</h6>

        @if(session('error'))
            <div class="alert alert-danger alert-sm py-2 small">
                <i class="fas fa-exclamation-circle me-1"></i>{{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold small text-muted">EMAIL</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-envelope text-muted small"></i>
                    </span>
                    <input type="email" name="email"
                        class="form-control border-start-0 @error('email') is-invalid @enderror"
                        placeholder="email@domain.com"
                        value="{{ old('email') }}" autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small text-muted">PASSWORD</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-lock text-muted small"></i>
                    </span>
                    <input type="password" name="password" id="passInput"
                        class="form-control border-start-0 @error('password') is-invalid @enderror"
                        placeholder="••••••••">
                    <button class="btn btn-outline-secondary border-start-0" type="button"
                        onclick="togglePass()">
                        <i class="fas fa-eye small" id="eyeIcon"></i>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-4 d-flex align-items-center justify-content-between">
                <label class="d-flex align-items-center gap-2 small text-muted" style="cursor:pointer;">
                    <input type="checkbox" name="remember" class="form-check-input mt-0"> Lembra-me
                </label>
            </div>
            <button type="submit" class="btn btn-login w-100 rounded-3">
                <i class="fas fa-sign-in-alt me-2"></i>Tama Sistema
            </button>
        </form>
    </div>
    <div class="rdtl-footer">
        <div class="small text-muted mb-2">Aksés tuir nivel:</div>
        <span class="role-badge" style="background:#fde8ec;color:#DC143C;">Super Admin — Aksés Kompletu</span>
        <span class="role-badge" style="background:#fff3cd;color:#856404;">Diretur — Haree Deit</span>
        <span class="role-badge" style="background:#e8f4fd;color:#0a58ca;">Utilizadór — Per Munisipiu</span>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePass() {
    const inp = document.getElementById('passInput');
    const ico = document.getElementById('eyeIcon');
    if (inp.type === 'password') {
        inp.type = 'text'; ico.classList.replace('fa-eye','fa-eye-slash');
    } else {
        inp.type = 'password'; ico.classList.replace('fa-eye-slash','fa-eye');
    }
}
</script>
</body>
</html>
