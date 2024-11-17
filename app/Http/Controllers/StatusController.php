<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    public function checkStatus()
    {
        try {
            DB::connection()->getPdo();
            $databaseStatus = 'connected';
        } catch (\Exception $e) {
            $databaseStatus = 'disconnected';
        }

        return response()->json([
            'status' => 'ok',
            'database' => $databaseStatus,
            'message' => 'Sistema funcionando corretamente',
        ]);
    }
}
