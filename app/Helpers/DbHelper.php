<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DbHelper
{
    /**
     * Returns SQL expression for date formatting compatible across SQLite, PostgreSQL, and MySQL.
     * $format uses strftime-style: %Y=year, %m=month, %d=day.
     */
    public static function dateFormat(string $column, string $format): string
    {
        return match (DB::getDriverName()) {
            'pgsql'             => "TO_CHAR($column, '" . strtr($format, ['%Y'=>'YYYY','%m'=>'MM','%d'=>'DD']) . "')",
            'mysql', 'mariadb'  => "DATE_FORMAT($column, '$format')",
            default             => "strftime('$format', $column)",
        };
    }
}
