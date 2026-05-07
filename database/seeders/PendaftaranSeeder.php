<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pendaftaran;
use Carbon\Carbon;

class PendaftaranSeeder extends Seeder
{
    public function run(): void
    {
        $jenis    = ['passaporte', 'bi', 'rejistu_kriminal', 'rdtl', 'eleitoral', 'sim'];
        $statuses = ['halo_foun', 'halo_foun', 'halo_foun', 'renova', 'renova', 'lakon'];
        $nomes    = [
            'João da Silva', 'Maria Soares', 'Pedro Gusmão', 'Ana Freitas',
            'Domingos Costa', 'Rosa Fernandes', 'Manuel Belo', 'Filomena Marques',
            'Agostinho Pinto', 'Beatriz Alves', 'Carlos Mau-Chiga', 'Dina Sarmento',
            'Eduardo Tilman', 'Fatima Araújo', 'Gaspar Nunes', 'Helena Correia',
            'Isidoro Santos', 'Joana Carvalho', 'Kostantino Pereira', 'Lurdes Xavier',
            'Miguel Moniz', 'Natalia Pires', 'Olavo Gonçalves', 'Paula Rangel',
            'Quintino Leite', 'Rita Borges', 'Sandro Matos', 'Teresa Brandão',
        ];
        $petugas = ['Mario da Costa', 'Ana Soares', 'João Gusmão', 'Maria Freitas', 'Domingos Fernandes'];
        $munisipiuList = [
            'Aileu', 'Ainaro', 'Baucau', 'Bobonaro', 'Covalima',
            'Dili', 'Ermera', 'Lautém', 'Liquiçá', 'Manatuto',
            'Manufahi', 'Oecusse', 'Viqueque', 'Ataúro',
        ];

        $counter = [];
        $now = Carbon::create(2026, 1, 1);

        for ($i = 0; $i < 120; $i++) {
            $j = $jenis[array_rand($jenis)];
            $counter[$j] = ($counter[$j] ?? 0) + 1;
            $prefix = strtoupper(match($j) {
                'passaporte'       => 'PSP',
                'bi'               => 'BI',
                'rejistu_kriminal' => 'RK',
                'rdtl'             => 'RDTL',
                'eleitoral'        => 'EL',
                'sim'              => 'SIM',
                default            => 'DOC',
            });

            $date   = $now->copy()->addDays(rand(0, 126));
            $status = $statuses[array_rand($statuses)];
            $selesai = ($status === 'selesai') ? $date->copy()->addDays(rand(3, 14)) : null;

            Pendaftaran::create([
                'no_registrasi'  => sprintf('%s/2026/%02d/%04d', $prefix, $date->month, $counter[$j]),
                'nama_lengkap'   => $nomes[array_rand($nomes)],
                'no_bi'          => rand(100, 999) . rand(100000, 999999),
                'tanggal_lahir'  => Carbon::now()->subYears(rand(18, 60))->subDays(rand(0, 365)),
                'jenis_kelamin'  => rand(0, 1) ? 'L' : 'P',
                'alamat'         => 'Dili, Timor-Leste',
                'no_telpon'      => '77' . rand(1000000, 9999999),
                'jenis_dokumen'  => $j,
                'kategori_sim'   => $j === 'sim' ? ['A','B','C','D'][rand(0,3)] : null,
                'status'         => $status,
                'tanggal_daftar' => $date->format('Y-m-d'),
                'tanggal_selesai'=> $selesai?->format('Y-m-d'),
                'nomor_dokumen'  => null,
                'petugas'        => $petugas[array_rand($petugas)],
                'munisipiu'      => $munisipiuList[array_rand($munisipiuList)],
                'keterangan'     => null,
                'created_at'     => $date,
                'updated_at'     => $selesai ?? $date,
            ]);
        }
    }
}
