<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatusController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViagemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('status', [StatusController::class, 'checkStatus']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::prefix('pedidos')->middleware(['transaction', 'auth.jwt'])->group(function () {
    Route::post('/', [ViagemController::class, 'criarPedido']);
    Route::patch('/{id}/status', [ViagemController::class, 'atualizarStatus']);
    Route::get('/{id}', [ViagemController::class, 'consultarPedido'])->name('pedidos.consultar');
    Route::get('/', [ViagemController::class, 'listarPedidos'])->name('pedidos.listar');

    Route::post('logout', [AuthController::class, 'logout']);
});


Route::fallback(function () {
    return response()->json([
        'message' => 'A rota que você tentou acessar não existe.',
        'status' => 'erro',
    ], 404);
});