<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $nullCount = DB::table('pendaftaran')
            ->whereNull('munisipiu')
            ->whereNull('user_id')
            ->count();

        if ($nullCount === 0) {
            return;
        }

        $usersByMuni = DB::table('users')
            ->where('role', 'user')
            ->whereNotNull('munisipiu')
            ->orderBy('id')
            ->get(['id', 'munisipiu']);

        // Jika hanya ada 1 user role=user, assign semua null record ke user tersebut
        if ($usersByMuni->count() === 1) {
            $u = $usersByMuni->first();
            DB::table('pendaftaran')
                ->whereNull('munisipiu')
                ->whereNull('user_id')
                ->update(['munisipiu' => $u->munisipiu, 'user_id' => $u->id]);
            return;
        }

        // Jika ada beberapa user, coba assign per user berdasarkan urutan waktu buat akun
        // Ambil semua null records lalu bagi rata ke users yang ada berdasarkan created_at
        if ($usersByMuni->count() > 1) {
            $nullRecords = DB::table('pendaftaran')
                ->whereNull('munisipiu')
                ->whereNull('user_id')
                ->orderBy('created_at')
                ->pluck('id');

            // Coba link via petugas field jika ada kesamaan nama user
            foreach ($nullRecords as $recId) {
                $rec = DB::table('pendaftaran')->where('id', $recId)->first();
                // Coba cocokkan nama petugas dengan nama user
                if ($rec->petugas) {
                    $matchUser = $usersByMuni->first(function ($u) use ($rec) {
                        return stripos($rec->petugas, explode(' ', $u->munisipiu ?? '')[0]) !== false;
                    });
                    if ($matchUser) {
                        DB::table('pendaftaran')->where('id', $recId)->update([
                            'munisipiu' => $matchUser->munisipiu,
                            'user_id'   => $matchUser->id,
                        ]);
                    }
                }
            }
        }
    }

    public function down(): void {}
};
