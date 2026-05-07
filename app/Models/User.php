<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'munisipiu', 'aktif'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'aktif'    => 'boolean',
        ];
    }

    public static $munisipiuList = [
        'Aileu', 'Ainaro', 'Baucau', 'Bobonaro', 'Covalima',
        'Dili', 'Ermera', 'Lautém', 'Liquiçá', 'Manatuto',
        'Manufahi', 'Oecusse', 'Viqueque', 'Ataúro',
    ];

    public static $roleLabels = [
        'super_admin' => 'Super Admin',
        'diretor'     => 'Diretur',
        'user'        => 'Utilizadór',
    ];

    public static $roleBadge = [
        'super_admin' => 'danger',
        'diretor'     => 'warning',
        'user'        => 'primary',
    ];

    public function isSuperAdmin(): bool   { return $this->role === 'super_admin'; }
    public function isDiretor(): bool      { return $this->role === 'diretor'; }
    public function isUser(): bool         { return $this->role === 'user'; }

    public function canWrite(): bool       { return in_array($this->role, ['super_admin', 'user']); }
    public function canSeeAll(): bool      { return in_array($this->role, ['super_admin', 'diretor']); }

    public function getLabelRoleAttribute(): string
    {
        return self::$roleLabels[$this->role] ?? $this->role;
    }

    public function getBadgeRoleAttribute(): string
    {
        return self::$roleBadge[$this->role] ?? 'secondary';
    }
}
