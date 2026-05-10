<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class HealthController extends Controller
{
    public function check()
    {
        $checks = [];
        $status = 'ok';

        // Database check
        try {
            DB::select('SELECT 1');
            $checks['database'] = 'ok';
        } catch (\Exception $e) {
            $checks['database'] = 'error';
            $status = 'error';
        }

        // Storage writable
        $checks['storage'] = is_writable(storage_path()) ? 'ok' : 'error';
        if ($checks['storage'] === 'error') {
            $status = 'error';
        }

        // Cache check
        try {
            Cache::put('health_check', true, 10);
            $checks['cache'] = Cache::get('health_check') ? 'ok' : 'error';
        } catch (\Exception $e) {
            $checks['cache'] = 'error';
        }

        $httpStatus = $status === 'ok' ? 200 : 503;

        return response()->json([
            'status'  => $status,
            'app'     => config('app.name'),
            'env'     => config('app.env'),
            'checks'  => $checks,
            'time'    => now()->toISOString(),
        ], $httpStatus);
    }
}
