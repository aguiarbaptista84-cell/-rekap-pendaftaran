<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::create([
            'name'      => 'Super Administradór',
            'email'     => 'superadmin@bu.gov.tl',
            'password'  => Hash::make('admin123'),
            'role'      => 'super_admin',
            'munisipiu' => null,
            'aktif'     => true,
        ]);

        // Diretor
        User::create([
            'name'      => 'Diretur Nasionál',
            'email'     => 'diretor@bu.gov.tl',
            'password'  => Hash::make('diretor123'),
            'role'      => 'diretor',
            'munisipiu' => null,
            'aktif'     => true,
        ]);

        // 14 Munisipiu users
        $munisipiuList = [
            'Aileu', 'Ainaro', 'Baucau', 'Bobonaro', 'Covalima',
            'Dili', 'Ermera', 'Lautém', 'Liquiçá', 'Manatuto',
            'Manufahi', 'Oecusse', 'Viqueque', 'Ataúro',
        ];

        foreach ($munisipiuList as $muni) {
            $slug = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $muni));
            User::create([
                'name'      => 'Petugás ' . $muni,
                'email'     => $slug . '@bu.gov.tl',
                'password'  => Hash::make('user123'),
                'role'      => 'user',
                'munisipiu' => $muni,
                'aktif'     => true,
            ]);
        }
    }
}
