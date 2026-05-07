<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran';

    protected $fillable = [
        'no_registrasi',
        'nama_lengkap',
        'no_bi',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_telpon',
        'jenis_dokumen',
        'kategori_sim',
        'status',
        'tanggal_daftar',
        'tanggal_selesai',
        'nomor_dokumen',
        'keterangan',
        'petugas',
        'munisipiu',
        'user_id',
    ];

    protected $casts = [
        'tanggal_daftar'  => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_lahir'   => 'date',
    ];

    public static $jenisDokumen = [
        'passaporte'      => 'Passaporte',
        'bi'              => 'Bilhete Identidade (BI)',
        'rejistu_kriminal'=> 'Rejistu Kriminal',
        'rdtl'            => 'Dokumentu RDTL',
        'eleitoral'       => 'Kartaun Eleitoral',
        'sim'             => 'SIM (Lisensa Kondusaun)',
    ];

    public static $statusLabels = [
        'halo_foun' => 'Halo Foun',
        'renova'    => 'Renova',
        'lakon'     => 'Lakon',
    ];

    public static $statusBadge = [
        'halo_foun' => 'primary',
        'renova'    => 'warning',
        'lakon'     => 'danger',
    ];

    public function getLabelJenisDokumenAttribute(): string
    {
        return self::$jenisDokumen[$this->jenis_dokumen] ?? $this->jenis_dokumen;
    }

    public function getLabelStatusAttribute(): string
    {
        return self::$statusLabels[$this->status] ?? $this->status;
    }

    public function getBadgeStatusAttribute(): string
    {
        return self::$statusBadge[$this->status] ?? 'secondary';
    }

    public function inputOleh(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function generateNoRegistrasi(string $jenisDokumen): string
    {
        $prefix = strtoupper(match ($jenisDokumen) {
            'passaporte'       => 'PSP',
            'bi'               => 'BI',
            'rejistu_kriminal' => 'RK',
            'rdtl'             => 'RDTL',
            'eleitoral'        => 'EL',
            'sim'              => 'SIM',
            default            => 'DOC',
        });

        $year  = date('Y');
        $month = date('m');
        $last  = static::where('jenis_dokumen', $jenisDokumen)
                       ->whereYear('created_at', $year)
                       ->whereMonth('created_at', $month)
                       ->count() + 1;

        return sprintf('%s/%s/%s/%04d', $prefix, $year, $month, $last);
    }
}
