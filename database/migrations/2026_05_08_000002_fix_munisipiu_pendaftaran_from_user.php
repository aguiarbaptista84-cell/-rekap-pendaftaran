<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update rekord ne'ebe iha user_id maibe munisipiu null (SQLite compatible)
        DB::statement('
            UPDATE pendaftaran
            SET munisipiu = (
                SELECT users.munisipiu FROM users
                WHERE users.id = pendaftaran.user_id
                  AND users.role = \'user\'
                  AND users.munisipiu IS NOT NULL
            )
            WHERE pendaftaran.munisipiu IS NULL
              AND pendaftaran.user_id IS NOT NULL
        ');
    }

    public function down(): void {}
};
