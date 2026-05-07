<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\User;
use App\Helpers\DbHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    private function baseQuery(int $tahun, string $bulan = '', string $jenis = '')
    {
        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $query = Pendaftaran::whereYear('tanggal_daftar', $tahun);

        if ($user->isUser()) {
            $query->where('munisipiu', $user->munisipiu);
        }
        if ($bulan) {
            $query->whereMonth('tanggal_daftar', $bulan);
        }
        if ($jenis) {
            $query->where('jenis_dokumen', $jenis);
        }
        return $query;
    }

    public function index(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        $bulan = $request->query('bulan', '');
        $jenis = $request->query('jenis_dokumen', '');
        $muni  = $request->query('munisipiu', '');

        /** @var \App\Models\User $user */
        $user  = Auth::user();

        $query = $this->baseQuery($tahun, $bulan, $jenis);
        if ($muni && $user->canSeeAll()) {
            $query->where('munisipiu', $muni);
        }

        $perJenis = (clone $query)
            ->select('jenis_dokumen', DB::raw('count(*) as total'))
            ->groupBy('jenis_dokumen')
            ->get()->keyBy('jenis_dokumen');

        $perStatus = (clone $query)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()->keyBy('status');

        $perBulan = $this->baseQuery($tahun, '', $jenis)
            ->when($muni && $user->canSeeAll(), fn($q) => $q->where('munisipiu', $muni))
            ->select(
                DB::raw(DbHelper::dateFormat('tanggal_daftar', '%m') . ' as bulan'),
                DB::raw('count(*) as total')
            )
            ->groupBy('bulan')->orderBy('bulan')
            ->pluck('total', 'bulan')->toArray();

        $detailPerJenis = [];
        foreach (Pendaftaran::$jenisDokumen as $key => $label) {
            $q = $this->baseQuery($tahun, $bulan, '')
                ->where('jenis_dokumen', $key);
            if ($muni && $user->canSeeAll()) {
                $q->where('munisipiu', $muni);
            }
            $detailPerJenis[$key] = [
                'label'     => $label,
                'total'     => $q->count(),
                'halo_foun' => (clone $q)->where('status', 'halo_foun')->count(),
                'renova'    => (clone $q)->where('status', 'renova')->count(),
                'lakon'     => (clone $q)->where('status', 'lakon')->count(),
            ];
        }

        $totalKeseluruhan = array_sum(array_column($detailPerJenis, 'total'));
        $tahunList        = range(date('Y'), 2020);
        $jenisDokumen     = Pendaftaran::$jenisDokumen;
        $munisipiuList    = User::$munisipiuList;
        $bulanList        = [
            '01'=>'Janeiru','02'=>'Fevereiro','03'=>'Marsu','04'=>'Abril',
            '05'=>'Maiu','06'=>'Juniu','07'=>'Juliu','08'=>'Agustus',
            '09'=>'Setembru','10'=>'Outubru','11'=>'Novembru','12'=>'Dezembru',
        ];

        return view('rekap.index', compact(
            'perJenis', 'perStatus', 'perBulan', 'detailPerJenis',
            'totalKeseluruhan', 'tahunList', 'tahun', 'bulan',
            'jenisDokumen', 'jenis', 'bulanList', 'munisipiuList', 'muni'
        ));
    }

    public function exportCsv(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        $bulan = $request->query('bulan', '');
        $jenis = $request->query('jenis_dokumen', '');
        $muni  = $request->query('munisipiu', '');
        /** @var \App\Models\User $user */
        $user  = Auth::user();

        $query = $this->baseQuery($tahun, $bulan, $jenis);
        if ($muni && $user->canSeeAll()) {
            $query->where('munisipiu', $muni);
        }

        $rows     = $query->orderBy('tanggal_daftar')->get();
        $filename = 'rekap-pendaftaran-' . $tahun . ($bulan ? '-' . $bulan : '') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, [
                'No', 'No Registrasi', 'Nama Lengkap', 'No BI', 'Jenis Kelamin',
                'Munisipiu', 'Jenis Dokumen', 'Kategori SIM', 'Status',
                'Tanggal Daftar', 'Tanggal Selesai', 'Nomor Dokumen', 'Petugas', 'Keterangan',
            ]);
            foreach ($rows as $i => $r) {
                fputcsv($handle, [
                    $i + 1,
                    $r->no_registrasi,
                    $r->nama_lengkap,
                    $r->no_bi,
                    $r->jenis_kelamin === 'L' ? 'Mane' : 'Feto',
                    $r->munisipiu ?? '-',
                    $r->label_jenis_dokumen,
                    $r->kategori_sim ?? '-',
                    $r->label_status,
                    $r->tanggal_daftar?->format('d/m/Y'),
                    $r->tanggal_selesai?->format('d/m/Y') ?? '-',
                    $r->nomor_dokumen ?? '-',
                    $r->petugas ?? '-',
                    $r->keterangan ?? '',
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
