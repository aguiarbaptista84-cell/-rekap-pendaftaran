<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendaftaranController extends Controller
{
    private function baseQuery()
    {
        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $query = Pendaftaran::query();
        if ($user->isUser()) {
            $query->where('munisipiu', $user->munisipiu);
        }
        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->baseQuery();

        if ($request->filled('jenis_dokumen')) {
            $query->where('jenis_dokumen', $request->jenis_dokumen);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nama_lengkap', 'like', "%$cari%")
                  ->orWhere('no_registrasi', 'like', "%$cari%")
                  ->orWhere('no_bi', 'like', "%$cari%");
            });
        }
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_daftar', [$request->dari, $request->sampai]);
        }
        if ($request->filled('munisipiu') && Auth::user()->canSeeAll()) {
            $query->where('munisipiu', $request->munisipiu);
        }

        $pendaftaran  = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $jenisDokumen = Pendaftaran::$jenisDokumen;
        $munisipiuList = \App\Models\User::$munisipiuList;

        return view('pendaftaran.index', compact('pendaftaran', 'jenisDokumen', 'munisipiuList'));
    }

    public function create()
    {
        $this->authCanWrite();
        $jenisDokumen  = Pendaftaran::$jenisDokumen;
        $petugas       = Petugas::where('aktif', true)->orderBy('nama')->get();
        $munisipiuList = \App\Models\User::$munisipiuList;
        return view('pendaftaran.create', compact('jenisDokumen', 'petugas', 'munisipiuList'));
    }

    public function store(Request $request)
    {
        $this->authCanWrite();
        $user = Auth::user();
        $validated = $request->validate([
            'nama_lengkap'   => 'required|string|max:150',
            'no_bi'          => 'nullable|string|max:30',
            'tanggal_lahir'  => 'nullable|date',
            'jenis_kelamin'  => 'required|in:L,P',
            'alamat'         => 'nullable|string|max:255',
            'no_telpon'      => 'nullable|string|max:20',
            'jenis_dokumen'  => 'required|in:passaporte,bi,rejistu_kriminal,rdtl,eleitoral,sim',
            'kategori_sim'   => 'nullable|in:A,B,C,D',
            'status'         => 'required|in:halo_foun,renova,lakon',
            'tanggal_daftar' => 'required|date',
            'tanggal_selesai'=> 'nullable|date|after_or_equal:tanggal_daftar',
            'nomor_dokumen'  => 'nullable|string|max:50',
            'keterangan'     => 'nullable|string',
            'petugas'        => 'nullable|string|max:100',
            'munisipiu'      => $user->isUser() ? 'nullable' : 'required|string|max:50',
        ]);

        $validated['munisipiu']     = $user->isUser() ? $user->munisipiu : $request->munisipiu;
        $validated['no_registrasi'] = Pendaftaran::generateNoRegistrasi($validated['jenis_dokumen']);

        Pendaftaran::create($validated);

        return redirect()->route('pendaftaran.index')
            ->with('sukses', 'Pendaftaran berhasil disimpan dengan nomor ' . $validated['no_registrasi']);
    }

    public function show(string $id)
    {
        $data = $this->baseQuery()->findOrFail($id);
        return view('pendaftaran.show', compact('data'));
    }

    public function edit(string $id)
    {
        $this->authCanWrite();
        $data          = $this->baseQuery()->findOrFail($id);
        $jenisDokumen  = Pendaftaran::$jenisDokumen;
        $petugas       = Petugas::where('aktif', true)->orderBy('nama')->get();
        $munisipiuList = \App\Models\User::$munisipiuList;
        return view('pendaftaran.edit', compact('data', 'jenisDokumen', 'petugas', 'munisipiuList'));
    }

    public function update(Request $request, string $id)
    {
        $this->authCanWrite();
        $data = $this->baseQuery()->findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap'   => 'required|string|max:150',
            'no_bi'          => 'nullable|string|max:30',
            'tanggal_lahir'  => 'nullable|date',
            'jenis_kelamin'  => 'required|in:L,P',
            'alamat'         => 'nullable|string|max:255',
            'no_telpon'      => 'nullable|string|max:20',
            'jenis_dokumen'  => 'required|in:passaporte,bi,rejistu_kriminal,rdtl,eleitoral,sim',
            'kategori_sim'   => 'nullable|in:A,B,C,D',
            'status'         => 'required|in:halo_foun,renova,lakon',
            'tanggal_daftar' => 'required|date',
            'tanggal_selesai'=> 'nullable|date|after_or_equal:tanggal_daftar',
            'nomor_dokumen'  => 'nullable|string|max:50',
            'keterangan'     => 'nullable|string',
            'petugas'        => 'nullable|string|max:100',
            'munisipiu'      => 'nullable|string|max:50',
        ]);

        $user = Auth::user();
        $validated['munisipiu'] = $user->isUser() ? $user->munisipiu : $request->munisipiu;

        $data->update($validated);

        return redirect()->route('pendaftaran.show', $data->id)
            ->with('sukses', 'Data pendaftaran berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $this->authCanWrite();
        $data = $this->baseQuery()->findOrFail($id);
        $data->delete();

        return redirect()->route('pendaftaran.index')
            ->with('sukses', 'Data pendaftaran berhasil dihapus.');
    }

    public function updateStatus(Request $request, string $id)
    {
        $this->authCanWrite();
        $data = $this->baseQuery()->findOrFail($id);

        $request->validate([
            'status'         => 'required|in:halo_foun,renova,lakon',
            'nomor_dokumen'  => 'nullable|string|max:50',
            'tanggal_selesai'=> 'nullable|date',
            'keterangan'     => 'nullable|string',
        ]);

        $data->update($request->only('status', 'nomor_dokumen', 'tanggal_selesai', 'keterangan'));

        return redirect()->route('pendaftaran.show', $data->id)
            ->with('sukses', 'Status pendaftaran berhasil diperbarui.');
    }

    private function authCanWrite(): void
    {
        if (!Auth::user()->canWrite()) {
            abort(403, 'La iha permisaun atu halo asaun ne\'e.');
        }
    }
}
