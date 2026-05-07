<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Petugas;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = [
            ['nama' => 'Mario da Costa',      'nip' => 'NIP001', 'jabatan' => 'Petugás Senior'],
            ['nama' => 'Ana Soares',           'nip' => 'NIP002', 'jabatan' => 'Petugás'],
            ['nama' => 'João Gusmão',          'nip' => 'NIP003', 'jabatan' => 'Xefe Unidade'],
            ['nama' => 'Maria Freitas',        'nip' => 'NIP004', 'jabatan' => 'Petugás'],
            ['nama' => 'Domingos Fernandes',   'nip' => 'NIP005', 'jabatan' => 'Petugás'],
        ];

        foreach ($petugas as $p) {
            Petugas::firstOrCreate(['nip' => $p['nip']], $p + ['aktif' => true]);
        }
    }
}
