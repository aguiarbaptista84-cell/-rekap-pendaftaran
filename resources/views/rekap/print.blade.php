<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Rekapitulasaun Rejistu — {{ $tahun }}{{ $bulan ? ' / '.$bulanList[$bulan] : '' }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10pt; color: #1a1a2e; background: #fff; }

    /* Header */
    .header { text-align: center; border-bottom: 3px solid #DC143C; padding-bottom: 10px; margin-bottom: 14px; }
    .header .logo-row { display: table; width: 100%; margin-bottom: 6px; }
    .header .logo-cell { display: table-cell; vertical-align: middle; }
    .header .logo-cell.left { text-align: left; width: 60px; }
    .header .logo-cell.right { text-align: right; width: 60px; }
    .header .logo-cell.center { text-align: center; }
    .header .rdtl-circle {
        display: inline-block; width: 52px; height: 52px; border-radius: 50%;
        background: #DC143C; color: #fff; font-size: 14pt; font-weight: bold;
        line-height: 52px; text-align: center;
    }
    .header h1 { font-size: 13pt; font-weight: bold; color: #DC143C; letter-spacing: .5px; }
    .header h2 { font-size: 10.5pt; font-weight: bold; color: #333; margin-top: 2px; }
    .header .sub { font-size: 8.5pt; color: #666; margin-top: 3px; }

    /* Meta info */
    .meta-box { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; padding: 7px 12px; margin-bottom: 14px; font-size: 9pt; }
    .meta-box table { width: 100%; }
    .meta-box td { padding: 2px 4px; }
    .meta-box td.label { color: #666; width: 100px; }
    .meta-box td.val { font-weight: bold; color: #333; }

    /* Summary cards */
    .summary-row { display: table; width: 100%; margin-bottom: 14px; border-collapse: separate; border-spacing: 6px; }
    .summary-cell { display: table-cell; text-align: center; border: 1.5px solid #dee2e6; border-radius: 4px; padding: 8px 4px; }
    .summary-cell .num { font-size: 18pt; font-weight: bold; }
    .summary-cell .lbl { font-size: 8pt; color: #666; margin-top: 2px; }
    .c-primary { color: #1971c2; }
    .c-warning { color: #e07b00; }
    .c-danger  { color: #DC143C; }
    .c-green   { color: #0ca678; }

    /* Section title */
    .section-title { font-size: 9.5pt; font-weight: bold; color: #DC143C; border-left: 3px solid #DC143C; padding-left: 7px; margin-bottom: 7px; margin-top: 14px; }

    /* Table */
    table.data { width: 100%; border-collapse: collapse; font-size: 9pt; }
    table.data th { background: #DC143C; color: #fff; padding: 5px 8px; text-align: center; }
    table.data th.left { text-align: left; }
    table.data td { padding: 5px 8px; border-bottom: 1px solid #e9ecef; }
    table.data td.center { text-align: center; }
    table.data tr:nth-child(even) td { background: #f8f9fa; }
    table.data tr.zero td { color: #aaa; }
    table.data tfoot td { background: #1a1a2e; color: #fff; font-weight: bold; padding: 5px 8px; }
    table.data tfoot td.center { text-align: center; }

    /* Progress bar */
    .bar-wrap { display: table; width: 100%; }
    .bar-bg { display: table-cell; background: #e9ecef; border-radius: 3px; height: 7px; vertical-align: middle; width: 60px; }
    .bar-fill { background: #0ca678; height: 7px; border-radius: 3px; }
    .bar-pct { display: table-cell; vertical-align: middle; padding-left: 5px; font-size: 8pt; color: #666; white-space: nowrap; }

    /* Monthly table */
    .months-row { display: table; width: 100%; border-collapse: separate; border-spacing: 4px; margin-bottom: 4px; }
    .month-cell { display: table-cell; text-align: center; border: 1px solid #dee2e6; border-radius: 3px; padding: 5px 2px; width: 8.33%; }
    .month-cell .m-lbl { font-size: 7.5pt; color: #888; }
    .month-cell .m-num { font-size: 11pt; font-weight: bold; color: #DC143C; margin-top: 2px; }
    .month-cell.has-data { background: #fff5f5; border-color: #DC143C; }

    /* Color dots */
    .dot { display: inline-block; width: 9px; height: 9px; border-radius: 50%; margin-right: 5px; vertical-align: middle; }

    /* Footer */
    .footer { margin-top: 20px; border-top: 1px solid #dee2e6; padding-top: 8px; display: table; width: 100%; font-size: 8pt; color: #888; }
    .footer .left { display: table-cell; }
    .footer .right { display: table-cell; text-align: right; }

    /* Signature area */
    .sign-area { margin-top: 20px; display: table; width: 100%; }
    .sign-cell { display: table-cell; text-align: center; width: 33%; vertical-align: top; font-size: 9pt; }
    .sign-line { border-top: 1px solid #333; margin: 50px 20px 5px; }
</style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <div class="logo-row">
        <div class="logo-cell left">
            <div class="rdtl-circle">BU</div>
        </div>
        <div class="logo-cell center">
            <h1>REPUBLIKA DEMOKRATIKA TIMOR-LESTE</h1>
            <h2>Ministeriu Justisa — Unidade Dokumentu Viajen</h2>
            <div class="sub">Rekapitulasaun Rejistu Dokumentu Sivil</div>
        </div>
        <div class="logo-cell right">
            <div class="rdtl-circle" style="background:#1a1a2e;">🇹🇱</div>
        </div>
    </div>
</div>

<!-- META INFO -->
<div class="meta-box">
    <table>
        <tr>
            <td class="label">Tinan</td>
            <td class="val">{{ $tahun }}</td>
            <td class="label">Fulan</td>
            <td class="val">{{ $bulan ? ($bulanList[$bulan] ?? $bulan) : 'Fulan Hotu' }}</td>
            <td class="label">Munisipiu</td>
            <td class="val">{{ $muni ? 'BU '.$muni : 'Hotu-hotu' }}</td>
            <td class="label">Jenis Dok.</td>
            <td class="val">{{ $jenis ? ($jenisDokumen[$jenis] ?? $jenis) : 'Hotu' }}</td>
        </tr>
        <tr>
            <td class="label">Dadaun Imprime</td>
            <td class="val" colspan="3">{{ now()->format('d/m/Y H:i') }}</td>
            <td class="label">Imprime Husi</td>
            <td class="val" colspan="3">{{ auth()->user()->name }} ({{ auth()->user()->label_role }})</td>
        </tr>
    </table>
</div>

<!-- SUMMARY CARDS -->
<div class="summary-row">
    <div class="summary-cell">
        <div class="num c-primary">{{ number_format($totalKeseluruhan) }}</div>
        <div class="lbl">Total Rejistu</div>
    </div>
    <div class="summary-cell">
        <div class="num c-primary">{{ number_format($perStatus['halo_foun']->total ?? 0) }}</div>
        <div class="lbl">Halo Foun</div>
    </div>
    <div class="summary-cell">
        <div class="num c-warning">{{ number_format($perStatus['renova']->total ?? 0) }}</div>
        <div class="lbl">Renova</div>
    </div>
    <div class="summary-cell">
        <div class="num c-danger">{{ number_format($perStatus['lakon']->total ?? 0) }}</div>
        <div class="lbl">Lakon</div>
    </div>
    <div class="summary-cell">
        <div class="num c-green">
            {{ $totalKeseluruhan > 0 ? number_format(($perStatus['halo_foun']->total ?? 0) / $totalKeseluruhan * 100, 1) : 0 }}%
        </div>
        <div class="lbl">% Halo Foun</div>
    </div>
</div>

<!-- MONTHLY TABLE -->
<div class="section-title">Rejistu tuir Fulan — {{ $tahun }}</div>
@php $bulanShort = ['01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Abr','05'=>'Mai','06'=>'Jun','07'=>'Jul','08'=>'Ago','09'=>'Set','10'=>'Out','11'=>'Nov','12'=>'Dez']; @endphp
<div class="months-row">
    @foreach($bulanShort as $bk => $bs)
    @php $val = $perBulan[$bk] ?? 0; @endphp
    <div class="month-cell {{ $val > 0 ? 'has-data' : '' }}">
        <div class="m-lbl">{{ $bs }}</div>
        <div class="m-num">{{ $val > 0 ? $val : '—' }}</div>
    </div>
    @endforeach
</div>

<!-- DETAIL TABLE -->
<div class="section-title">Detalle Rekap tuir Tipu Dokumentu</div>

@php
$dotColors = [
    'passaporte'=>'#3b5bdb','bi'=>'#0ca678','rejistu_kriminal'=>'#e8590c',
    'rdtl'=>'#862e9c','eleitoral'=>'#1971c2','sim'=>'#f08c00'
];
@endphp

<table class="data">
    <thead>
        <tr>
            <th class="left" style="width:30px;">No.</th>
            <th class="left">Tipu Dokumentu</th>
            <th>Total</th>
            <th>Halo Foun</th>
            <th>Renova</th>
            <th>Lakon</th>
            <th style="width:130px;">% Halo Foun</th>
        </tr>
    </thead>
    <tbody>
        @foreach($detailPerJenis as $key => $d)
        <tr class="{{ $d['total'] === 0 ? 'zero' : '' }}">
            <td class="center">{{ $loop->iteration }}</td>
            <td>
                <span class="dot" style="background:{{ $dotColors[$key] ?? '#666' }};"></span>
                {{ $d['label'] }}
            </td>
            <td class="center"><strong>{{ number_format($d['total']) }}</strong></td>
            <td class="center" style="color:#1971c2;">{{ number_format($d['halo_foun']) }}</td>
            <td class="center" style="color:#e07b00;">{{ number_format($d['renova']) }}</td>
            <td class="center" style="color:#DC143C;">{{ number_format($d['lakon']) }}</td>
            <td class="center">
                @if($d['total'] > 0)
                    @php $pct = ($d['halo_foun'] / $d['total']) * 100 @endphp
                    <div class="bar-wrap">
                        <div class="bar-bg">
                            <div class="bar-fill" style="width:{{ min($pct, 100) }}%;"></div>
                        </div>
                        <span class="bar-pct">{{ number_format($pct, 1) }}%</span>
                    </div>
                @else
                    <span style="color:#ccc;">—</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">TOTAL</td>
            <td class="center">{{ number_format($totalKeseluruhan) }}</td>
            <td class="center">{{ number_format(array_sum(array_column($detailPerJenis, 'halo_foun'))) }}</td>
            <td class="center">{{ number_format(array_sum(array_column($detailPerJenis, 'renova'))) }}</td>
            <td class="center">{{ number_format(array_sum(array_column($detailPerJenis, 'lakon'))) }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>

<!-- SIGNATURE -->
<div class="sign-area">
    <div class="sign-cell"></div>
    <div class="sign-cell">
        <div>Dili, {{ now()->format('d') }} {{ ['01'=>'Janeiru','02'=>'Fevereiro','03'=>'Marsu','04'=>'Abril','05'=>'Maiu','06'=>'Juniu','07'=>'Juliu','08'=>'Agustus','09'=>'Setembru','10'=>'Outubru','11'=>'Novembru','12'=>'Dezembru'][now()->format('m')] }} {{ now()->format('Y') }}</div>
        <div style="margin-top:4px; color:#666; font-size:8.5pt;">Responsavel</div>
        <div class="sign-line"></div>
        <div style="font-size:8.5pt;">(_________________________)</div>
    </div>
    <div class="sign-cell"></div>
</div>

<!-- FOOTER -->
<div class="footer">
    <div class="left">Rekapitulasaun Rejistu Dokumentu Sivil — Sistema BU RDTL</div>
    <div class="right">Imprime: {{ now()->format('d/m/Y H:i') }}</div>
</div>

</body>
</html>
