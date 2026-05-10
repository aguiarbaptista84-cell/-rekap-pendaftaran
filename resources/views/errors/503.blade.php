<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="refresh" content="{{ $retryAfter ?? 15 }}">
<title>Manutensaun Sistema — BU RDTL</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        min-height: 100vh; display: flex; align-items: center; justify-content: center;
        color: #fff;
    }
    .card {
        background: rgba(255,255,255,.07);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 16px;
        padding: 48px 40px;
        text-align: center;
        max-width: 480px;
        width: 90%;
    }
    .icon {
        font-size: 56px;
        margin-bottom: 20px;
        display: block;
    }
    .badge {
        display: inline-block;
        background: #DC143C;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 2px;
        padding: 4px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        margin-bottom: 20px;
    }
    h1 { font-size: 26px; font-weight: 700; margin-bottom: 12px; }
    p  { color: rgba(255,255,255,.65); font-size: 15px; line-height: 1.6; margin-bottom: 8px; }
    .countdown {
        margin-top: 28px;
        background: rgba(255,255,255,.08);
        border-radius: 8px;
        padding: 14px 20px;
        font-size: 13px;
        color: rgba(255,255,255,.5);
    }
    .brand {
        margin-top: 32px;
        font-size: 12px;
        color: rgba(255,255,255,.3);
        letter-spacing: 1px;
    }
    .spinner {
        display: inline-block;
        width: 14px; height: 14px;
        border: 2px solid rgba(255,255,255,.2);
        border-top-color: #DC143C;
        border-radius: 50%;
        animation: spin .8s linear infinite;
        margin-right: 6px;
        vertical-align: middle;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
</head>
<body>
<div class="card">
    <span class="icon">🔧</span>
    <div class="badge">Manutensaun</div>
    <h1>Sistema iha Manutensaun</h1>
    <p>Ami halo atualização ba sistema.<br>Hein momentu, sei fila lalais.</p>
    <p style="margin-top:8px;">Sistema is under maintenance.<br>We'll be back shortly.</p>
    <div class="countdown">
        <span class="spinner"></span>
        Atualiza automaticamente iha {{ $retryAfter ?? 15 }} segundu...
    </div>
    <div class="brand">REPUBLIKA DEMOKRATIKA TIMOR-LESTE &bull; Sistema BU</div>
</div>
</body>
</html>
