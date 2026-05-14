@extends('layouts.app')

@section('title', 'Rekapitulasaun')
@section('page-title', 'Rekapitulasaun Pendaftaran Dokumentu')

@section('content')

{{-- Filter --}}
<div class="card stat-card mb-4">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('rekap.index') }}" class="row g-2 align-items-end">
            <div class="col-6 col-md-2">
                <label class="form-label form-label-sm text-muted">Tinan</label>
                <select name="tahun" class="form-select form-select-sm">
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label form-label-sm text-muted">Fulan</label>
                <select name="bulan" class="form-select form-select-sm">
                    <option value="">Fulan Hotu</option>
                    @foreach($bulanList as $bk => $bl)
                        <option value="{{ $bk }}" {{ $bulan == $bk ? 'selected' : '' }}>{{ $bl }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label form-label-sm text-muted">Jenis Dokumentu</label>
                <select name="jenis_dokumen" class="form-select form-select-sm">
                    <option value="">Hotu</option>
                    @foreach($jenisDokumen as $key => $label)
                        <option value="{{ $key }}" {{ $jenis == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @if(auth()->user()->canSeeAll())
            <div class="col-6 col-md-3">
                <label class="form-label form-label-sm text-muted">Munisipiu</label>
                <select name="munisipiu" class="form-select form-select-sm">
                    <option value="">Hotu-hotu</option>
                    @foreach($munisipiuList as $m)
                        <option value="{{ $m }}" {{ $muni === $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-12 col-md-2 d-flex flex-wrap gap-2 align-items-end">
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-filter me-1"></i> Filtru
                </button>
                <a href="{{ route('rekap.export-csv',  request()->query()) }}" class="btn btn-success btn-sm" title="Baixa CSV">
                    <i class="fas fa-file-csv me-1"></i> CSV
                </a>
                <a href="{{ route('rekap.export-pdf',  request()->query()) }}" class="btn btn-danger btn-sm" title="Baixa PDF">
                    <i class="fas fa-file-pdf me-1"></i> PDF
                </a>
                <a href="{{ route('rekap.export-word', request()->query()) }}" class="btn btn-primary btn-sm" title="Baixa Word">
                    <i class="fas fa-file-word me-1"></i> Word
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card text-center">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-primary">{{ number_format($totalKeseluruhan) }}</div>
                <div class="text-muted small">Total Rejistu</div>
            </div>
        </div>
    </div>
    @foreach(['halo_foun'=>['primary','Halo Foun'],'renova'=>['warning','Renova'],'lakon'=>['danger','Lakon']] as $sk => [$sc, $sl])
    <div class="col-6 col-lg-3">
        <div class="card stat-card text-center">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-{{ $sc }}">{{ number_format($perStatus[$sk]->total ?? 0) }}</div>
                <div class="text-muted small">{{ $sl }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Chart Bulanan --}}
<div class="card stat-card mb-4">
    <div class="card-header bg-white border-0 pt-3 pb-0 fw-semibold">
        <i class="fas fa-chart-bar me-2 text-danger"></i>
        Pendaftaran per Fulan — Tinan {{ $tahun }}
        @if($bulan) <small class="text-muted ms-1">({{ $bulanList[$bulan] ?? $bulan }})</small> @endif
        @if(auth()->user()->canSeeAll() && $muni) <span class="badge bg-primary ms-1">BU {{ $muni }}</span> @endif
    </div>
    <div class="card-body">
        <canvas id="chartBulan" style="max-height:220px;"></canvas>
    </div>
</div>

{{-- Tabel Per Munisipiu --}}
@if(auth()->user()->canSeeAll())
<div class="card stat-card mb-4">
    <div class="card-header bg-white border-0 pt-3 pb-0 fw-semibold">
        <i class="fas fa-map-marker-alt me-2 text-danger"></i>Rekap per Munisipiu
        @if($bulan) <small class="text-muted ms-1">({{ $bulanList[$bulan] ?? $bulan }})</small> @endif
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th width="30">#</th>
                    <th>Munisipiu</th>
                    <th class="text-center">Total</th>
                    <th class="text-center text-primary">Halo Foun</th>
                    <th class="text-center text-warning">Renova</th>
                    <th class="text-center text-danger">Lakon</th>
                    <th class="text-center">% Halo Foun</th>
                    <th class="text-center"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($perMunisipiu as $i => $row)
                <tr>
                    <td class="text-muted small">{{ $i + 1 }}</td>
                    <td class="fw-semibold">
                        <i class="fas fa-building me-1 text-primary"></i>BU {{ $row->munisipiu }}
                    </td>
                    <td class="text-center fw-bold">{{ number_format($row->total) }}</td>
                    <td class="text-center text-primary">{{ number_format($row->halo_foun) }}</td>
                    <td class="text-center text-warning">{{ number_format($row->renova) }}</td>
                    <td class="text-center text-danger">{{ number_format($row->lakon) }}</td>
                    <td class="text-center">
                        @if($row->total > 0)
                            @php $pct = ($row->halo_foun / $row->total) * 100 @endphp
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:6px;">
                                    <div class="progress-bar bg-success" style="width:{{ $pct }}%"></div>
                                </div>
                                <small>{{ number_format($pct, 1) }}%</small>
                            </div>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('pendaftaran.index', array_merge(request()->query(), ['munisipiu' => $row->munisipiu])) }}"
                           class="btn btn-xs btn-outline-primary" style="font-size:.75rem;padding:.2rem .5rem;">
                            <i class="fas fa-list"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-3">La iha dadus munisipiu.</td>
                </tr>
                @endforelse
            </tbody>
            @if($perMunisipiu->count() > 0)
            <tfoot class="table-dark">
                <tr>
                    <td colspan="2" class="fw-bold">TOTAL</td>
                    <td class="text-center fw-bold">{{ number_format($perMunisipiu->sum('total')) }}</td>
                    <td class="text-center">{{ number_format($perMunisipiu->sum('halo_foun')) }}</td>
                    <td class="text-center">{{ number_format($perMunisipiu->sum('renova')) }}</td>
                    <td class="text-center">{{ number_format($perMunisipiu->sum('lakon')) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endif

{{-- Tabel Detail per Jenis --}}
<div class="card stat-card">
    <div class="card-header bg-white border-0 pt-3 pb-0 fw-semibold">
        <i class="fas fa-table me-2 text-danger"></i>Detail Rekap per Jenis Dokumentu
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Jenis Dokumentu</th>
                    <th class="text-center">Total</th>
                    <th class="text-center text-primary">Halo Foun</th>
                    <th class="text-center text-warning">Renova</th>
                    <th class="text-center text-danger">Lakon</th>
                    <th class="text-center">% Halo Foun</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($detailPerJenis as $key => $d)
                <tr class="{{ $d['total'] === 0 ? 'text-muted' : '' }}">
                    <td>
                        <span class="badge me-2" style="background:{{ ['passaporte'=>'#3b5bdb','bi'=>'#0ca678','rejistu_kriminal'=>'#e8590c','rdtl'=>'#862e9c','eleitoral'=>'#1971c2','sim'=>'#f08c00'][$key] }}">
                            &nbsp;
                        </span>
                        {{ $d['label'] }}
                    </td>
                    <td class="text-center fw-bold">{{ number_format($d['total']) }}</td>
                    <td class="text-center text-primary">{{ number_format($d['halo_foun']) }}</td>
                    <td class="text-center text-warning">{{ number_format($d['renova']) }}</td>
                    <td class="text-center text-danger">{{ number_format($d['lakon']) }}</td>
                    <td class="text-center">
                        @if($d['total'] > 0)
                            @php $pct = ($d['halo_foun'] / $d['total']) * 100 @endphp
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:6px;">
                                    <div class="progress-bar bg-success" style="width:{{ $pct }}%"></div>
                                </div>
                                <small>{{ number_format($pct, 1) }}%</small>
                            </div>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($d['total'] > 0)
                        <a href="{{ route('pendaftaran.index', ['jenis_dokumen' => $key]) }}" class="btn btn-xs btn-outline-secondary" style="font-size:.75rem;padding:.2rem .5rem;">
                            <i class="fas fa-list"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="table-dark">
                <tr>
                    <td class="fw-bold">TOTAL</td>
                    <td class="text-center fw-bold">{{ number_format($totalKeseluruhan) }}</td>
                    <td class="text-center">{{ number_format(array_sum(array_column($detailPerJenis, 'halo_foun'))) }}</td>
                    <td class="text-center">{{ number_format(array_sum(array_column($detailPerJenis, 'renova'))) }}</td>
                    <td class="text-center">{{ number_format(array_sum(array_column($detailPerJenis, 'lakon'))) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
const bulanLabels = ['Jan','Feb','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
const bulanData   = Array(12).fill(0);
@foreach($perBulan as $bk => $bv)
    bulanData[parseInt('{{ $bk }}') - 1] = {{ $bv }};
@endforeach

new Chart(document.getElementById('chartBulan').getContext('2d'), {
    type: 'bar',
    data: {
        labels: bulanLabels,
        datasets: [{
            label: 'Pendaftaran',
            data: bulanData,
            backgroundColor: 'rgba(220,20,60,.7)',
            borderColor: '#DC143C',
            borderWidth: 1,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>
@endpush
