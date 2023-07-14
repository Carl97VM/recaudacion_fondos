<?php

use App\Http\Controllers\AportanteController;
use App\Http\Controllers\Aporte_candidatoController;
use App\Http\Controllers\AportesController;
use App\Http\Controllers\CandidatosController;
use App\Http\Controllers\DesembolsosController;
use App\Http\Controllers\DetallesController;
use App\Http\Controllers\RendicionCuentasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
// candidatos
Route::get('candidatos/index', [CandidatosController::class, 'index'])->name('candidatos.index');
Route::get('candidatos/index_canidatos', [CandidatosController::class, 'index_canidatos'])->name('candidatos.index_canidatos');
Route::get('candidatos/list', [CandidatosController::class, 'list'])->name('candidatos.list');
Route::get('candidatos/{id}/edit', [CandidatosController::class, 'edit'])->name('candidatos.edit');
Route::post('candidatos/store', [CandidatosController::class, 'store'])->name('candidatos.store');
Route::put('candidatos/update', [CandidatosController::class, 'update'])->name('candidatos.update');
Route::delete('candidatos/{id}/destroy', [CandidatosController::class, 'destroy'])->name('candidatos.destroy');

// aportantes
Route::get('aportantes/index', [AportanteController::class, 'index'])->name('aportantes.index');
Route::get('aportantes/list', [AportanteController::class, 'list'])->name('aportantes.list');
Route::get('aportantes/{id}/edit', [AportanteController::class, 'edit'])->name('aportantes.edit');
Route::post('aportantes/store', [AportanteController::class, 'store'])->name('aportantes.store');
Route::put('aportantes/{id}/update', [AportanteController::class, 'update'])->name('aportantes.update');
Route::delete('aportantes/{id}/destroy', [AportanteController::class, 'destroy'])->name('aportantes.destroy');

// aportes
Route::get('aportes/index', [AportesController::class, 'index'])->name('aportes.index');
Route::post('aportes/store', [AportesController::class, 'store'])->name('aportes.store');
Route::get('aportes/{id}/edit', [AportesController::class, 'edit'])->name('aportes.edit');
Route::put('aportes/{id}/update', [AportesController::class, 'update'])->name('aportes.update');
Route::delete('aportes/{id}/destroy', [AportesController::class, 'destroy'])->name('aportes.destroy');

// aportes candidatos
Route::get('aportes_candidato/{id}/informes_candidato', [Aporte_candidatoController::class, 'informes_candidato'])->name('aportes_candidato.informes_candidato');
Route::get('aportes_candidato/{id}/informes_aportantes', [Aporte_candidatoController::class, 'informes_aportantes'])->name('aportes_candidato.informes_aportantes');
Route::get('aportes_candidato/{id}/pdf_informes_candidato', [Aporte_candidatoController::class, 'pdf_informes_candidato'])->name('aportes_candidato.pdf_informes_candidato');
Route::get('aportes_candidato/estadisticas', [Aporte_candidatoController::class, 'estadisticas'])->name('aportes_candidato.estadisticas');
// Route::get('aportes_candidato/{id}/pdf_informes_candidato', [Aporte_candidatoController::class, 'pdf_informes_candidato'])->name('aportes_candidato.pdf_informes_candidato');

// desembolsos
Route::get('desembolso/list', [DesembolsosController::class, 'list'])->name('desembolso.list');
Route::post('desembolso/store', [DesembolsosController::class, 'store'])->name('desembolso.store');
Route::put('desembolso/update', [DesembolsosController::class, 'update'])->name('desembolso.update');
Route::delete('desembolso/{id}/destroy', [DesembolsosController::class, 'destroy'])->name('desembolso.destroy');
Route::get('desembolso/{id}/informes_candidato_desembolsos', [Aporte_candidatoController::class, 'informes_candidato_desembolsos'])->name('aportes_candidato.informes_candidato_desembolsos');

// rendicion de cuentas
Route::get('rendicion_cuentas/{id}/index', [RendicionCuentasController::class, 'index'])->name('rendicion_cuentas.index');
Route::get('rendicion_cuentas/{id}/list', [RendicionCuentasController::class, 'list'])->name('rendicion_cuentas.list');
Route::post('rendicion_cuentas/store', [RendicionCuentasController::class, 'store'])->name('rendicion_cuentas.store');
Route::put('rendicion_cuentas/update', [RendicionCuentasController::class, 'update'])->name('rendicion_cuentas.update');
Route::delete('rendicion_cuentas/{id}/destroy', [RendicionCuentasController::class, 'destroy'])->name('rendicion_cuentas.destroy');

// detalles
Route::get('detalles/{id}/index', [DetallesController::class, 'index'])->name('detalles.index');
Route::get('detalles/{id}/list', [DetallesController::class, 'list'])->name('detalles.list');
Route::post('detalles/store', [DetallesController::class, 'store'])->name('detalles.store');
Route::put('detalles/update', [DetallesController::class, 'update'])->name('detalles.update');
Route::delete('detalles/destroy', [DetallesController::class, 'destroy'])->name('detalles.destroy');
