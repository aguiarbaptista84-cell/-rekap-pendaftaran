@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Rekapan Dokumentu')

@push('styles')
<style>
    .doc-badge { display: inline-block; padding: .25rem .6rem; border-radius: 5px; color: #fff; font-size: .78rem; font-weight: 600; }
</style>
@endpush

@section('content')

{{-- Statistik Utama --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-2">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-wrap bg-primary bg-opacity-15 text-primary"><i class="fas fa-file-alt"></i></div>
                <div>
                    <div class="fs-3 fw-bold text-primary">{{ number_format($total) }}</div>
                    <div class="text-muted small">Total</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-wrap bg-primary bg-opacity-15 text-primary"><i class="fas fa-file-plus"></i></div>
                <div>
                    <div class="fs-3 fw-bold text-primary">{{ number_format($halo_foun) }}</div>
                    <div class="text-muted small">Halo Foun</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-wrap bg-warning bg-opacity-15 text-warning"><i class="fas fa-sync-alt"></i></div>
                <div>
                    <div class="fs-3 fw-bold text-warning">{{ number_format($renova) }}</div>
                    <div class="text-muted small">Renova</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-wrap bg-danger bg-opacity-15 text-danger"><i class="fas fa-exclamation-triangle"></i></div>
                <div>
                    <div class="fs-3 fw-bold text-danger">{{ number_format($lakon) }}</div>
                    <div class="text-muted small">Lakon</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card stat-card h-100 border-0" style="background: linear-gradient(135deg,#DC143C,#a00e2b);">
            <div class="card-body d-flex flex-column justify-content-center text-center text-white">
                <div class="small mb-1">Halo Foun %</div>
                <div class="fs-3 fw-bold">
                    {{ $total > 0 ? number_format(($halo_foun / $total) * 100, 1) : 0 }}%
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Per Jenis Dokumen --}}
<div class="row g-3 mb-4">
    @php
        $dokColors = [
            'passaporte'=>['bg'=>'#3b5bdb','icon'=>'fa-passport'],
            'bi'=>['bg'=>'#0ca678','icon'=>'fa-id-card'],
            'rejistu_kriminal'=>['bg'=>'#e8590c','icon'=>'fa-gavel'],
            'rdtl'=>['bg'=>'#862e9c','icon'=>'fa-flag'],
            'eleitoral'=>['bg'=>'#1971c2','icon'=>'fa-vote-yea'],
            'sim'=>['bg'=>'#f08c00','icon'=>'fa-car'],
        ];
        $dokLabels = \App\Models\Pendaftaran::$jenisDokumen;
    @endphp
    @foreach($dokLabels as $key => $label)
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card h-100" style="border-top: 3px solid {{ $dokColors[$key]['bg'] }}">
            <div class="card-body text-center py-3">
                <i class="fas {{ $dokColors[$key]['icon'] }} fa-2x mb-2" style="color: {{ $dokColors[$key]['bg'] }}"></i>
                <div class="fs-4 fw-bold">{{ $perDokumen[$key] ?? 0 }}</div>
                <div class="text-muted" style="font-size:.75rem;">{{ $label }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Charts --}}
<div class="row g-3 mb-4">
    <div class="col-lg-5">
        <div class="card stat-card h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0 fw-semibold">
                <i class="fas fa-chart-pie me-2 text-danger"></i>Distribuisaun per Jenis Dokumentu
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="chartDokumen" style="max-height:260px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card stat-card h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0 fw-semibold">
                <i class="fas fa-chart-line me-2 text-danger"></i>Pendaftaran per Bulan ({{ date('Y') }})
            </div>
            <div class="card-body">
                <canvas id="chartBulan" style="max-height:260px;"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Per Munisipiu --}}
<div class="card stat-card mb-4">
    <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
        <span class="fw-semibold">
            <i class="fas fa-map-marker-alt me-2 text-danger"></i>
            Rejistu Per Munisipiu
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Munisipiu</th>
                        <th class="text-center">Total</th>
                        <th class="text-center text-primary">Halo Foun</th>
                        <th class="text-center text-warning">Renova</th>
                        <th class="text-center text-danger">Lakon</th>
                        <th class="text-center">Aksaun</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perMunisipiu as $row)
                    <tr>
                        <td>
                            <span class="fw-semibold">
                                <i class="fas fa-building me-1 text-primary"></i>{{ $row->munisipiu }}
                            </span>
                        </td>
                        <td class="text-center fw-bold">{{ $row->total }}</td>
                        <td class="text-center"><span class="badge bg-primary">{{ $row->halo_foun }}</span></td>
                        <td class="text-center"><span class="badge bg-warning text-dark">{{ $row->renova }}</span></td>
                        <td class="text-center"><span class="badge bg-danger">{{ $row->lakon }}</span></td>
                        <td class="text-center">
                            <a href="{{ route('pendaftaran.index', ['munisipiu' => $row->munisipiu]) }}"
                               class="btn btn-xs btn-outline-primary" style="font-size:.75rem;padding:.2rem .5rem;">
                                <i class="fas fa-list me-1"></i>Haree
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                            La iha rejistu seidauk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($perMunisipiu->count() > 1)
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td>Total Hotu</td>
                        <td class="text-center">{{ $perMunisipiu->sum('total') }}</td>
                        <td class="text-center text-primary">{{ $perMunisipiu->sum('halo_foun') }}</td>
                        <td class="text-center text-warning">{{ $perMunisipiu->sum('renova') }}</td>
                        <td class="text-center text-danger">{{ $perMunisipiu->sum('lakon') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

{{-- Tabel Terbaru --}}
<div class="card stat-card">
    <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="fas fa-clock me-2 text-danger"></i>Rejistu Ikus (10 Ikus)</span>
        <a href="{{ route('pendaftaran.index') }}" class="btn btn-sm btn-outline-danger">Haree Hotu</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No. Rejistu</th>
                        <th>Naran</th>
                        <th>Dokumentu</th>
                        <th>Status</th>
                        @if($user->canSeeAll())
                        <th>Munisipiu</th>
                        <th>Inputu Husi</th>
                        @endif
                        <th>Data Rejistu</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($terbaru as $p)
                    <tr>
                        <td><code>{{ $p->no_registrasi }}</code></td>
                        <td>{{ $p->nama_lengkap }}</td>
                        <td>
                            <span class="doc-badge badge-{{ $p->jenis_dokumen }}">
                                {{ $p->label_jenis_dokumen }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $p->badge_status }}">{{ $p->label_status }}</span>
                        </td>
                        @if($user->canSeeAll())
                        <td>
                            @if($p->munisipiu)
                                <span class="badge" style="background:#e8f4fd;color:#0a58ca;font-size:.78rem;">
                                    {{ $p->munisipiu }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="text-muted small">
                            @if($p->inputOleh)
                                <div class="d-flex align-items-center gap-1">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                        style="width:22px;height:22px;font-size:.6rem;background:#1971c2;flex-shrink:0;">
                                        {{ strtoupper(substr($p->inputOleh->name, 0, 2)) }}
                                    </div>
                                    <span>{{ $p->inputOleh->name }}</span>
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        @endif
                        <td class="text-nowrap">{{ $p->tanggal_daftar->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('pendaftaran.show', $p->id) }}" class="btn btn-xs btn-outline-secondary" style="font-size:.75rem;padding:.15rem .5rem;">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $user->canSeeAll() ? 8 : 6 }}" class="text-center text-muted py-4">
                            La iha rejistu seidauk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Pie chart per jenis dokumen
const ctxDok = document.getElementById('chartDokumen').getContext('2d');
new Chart(ctxDok, {
    type: 'doughnut',
    data: {
        labels: @json(array_values(\App\Models\Pendaftaran::$jenisDokumen)),
        datasets: [{
            data: [
                {{ $perDokumen['passaporte'] ?? 0 }},
                {{ $perDokumen['bi'] ?? 0 }},
                {{ $perDokumen['rejistu_kriminal'] ?? 0 }},
                {{ $perDokumen['rdtl'] ?? 0 }},
                {{ $perDokumen['eleitoral'] ?? 0 }},
                {{ $perDokumen['sim'] ?? 0 }},
            ],
            backgroundColor: ['#3b5bdb','#0ca678','#e8590c','#862e9c','#1971c2','#f08c00'],
            borderWidth: 2,
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } } }
});

// Bar chart per bulan
const bulanLabels = ['Jan','Feb','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
const bulanData   = Array(12).fill(0);
@foreach($perBulan as $item)
    bulanData[parseInt('{{ $item->bulan }}') - 1] = {{ $item->total }};
@endforeach

const ctxBulan = document.getElementById('chartBulan').getContext('2d');
new Chart(ctxBulan, {
    type: 'bar',
    data: {
        labels: bulanLabels,
        datasets: [{
            label: 'Pendaftaran',
            data: bulanData,
            backgroundColor: 'rgba(220, 20, 60, 0.7)',
            borderColor: '#DC143C',
            borderWidth: 1,
            borderRadius: 5,
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
