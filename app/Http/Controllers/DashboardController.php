<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Helpers\DbHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $query = Pendaftaran::query();

        if ($user->isUser()) {
            $query->where(function ($q) use ($user) {
                $q->where('munisipiu', $user->munisipiu)
                  ->orWhere('user_id', $user->id);
            });
        }

        $total     = (clone $query)->count();
        $halo_foun = (clone $query)->where('status', 'halo_foun')->count();
        $renova    = (clone $query)->where('status', 'renova')->count();
        $lakon     = (clone $query)->where('status', 'lakon')->count();

        $perDokumen = (clone $query)
            ->select('jenis_dokumen', DB::raw('count(*) as total'))
            ->groupBy('jenis_dokumen')
            ->pluck('total', 'jenis_dokumen')
            ->toArray();

        $perBulan = (clone $query)
            ->select(
                DB::raw(DbHelper::dateFormat('tanggal_daftar', '%Y-%m') . ' as bulan'),
                DB::raw('count(*) as total')
            )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Untuk user role: daftar lengkap entri munisipiu mereka (limit 20)
        // Untuk admin/diretor: 10 entri terbaru semua munisipiu
        $limitTerbaru = $user->isUser() ? 20 : 10;
        $terbaru = (clone $query)->with('inputOleh')->orderBy('created_at', 'desc')->limit($limitTerbaru)->get();

        // Ringkasan per munisipiu (semua munisipiu tanpa filter untuk admin/diretor)
        $perMunisipiu = Pendaftaran::query()
            ->select('munisipiu',
                DB::raw('count(*) as total'),
                DB::raw("sum(case when status='halo_foun' then 1 else 0 end) as halo_foun"),
                DB::raw("sum(case when status='renova' then 1 else 0 end) as renova"),
                DB::raw("sum(case when status='lakon' then 1 else 0 end) as lakon")
            )
            ->whereNotNull('munisipiu')
            ->groupBy('munisipiu')
            ->orderBy('munisipiu')
            ->get();

        return view('dashboard', compact(
            'total', 'halo_foun', 'renova', 'lakon',
            'perDokumen', 'perBulan', 'terbaru', 'perMunisipiu', 'user'
        ));
    }
}
