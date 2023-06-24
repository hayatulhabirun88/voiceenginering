<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\VoterController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\VotingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'proses']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [HomeController::class, 'index']);

Route::get('/peserta', [PesertaController::class, 'index']);
Route::get('/peserta/create', [PesertaController::class, 'create']);
Route::post('/peserta', [PesertaController::class, 'store']);
Route::delete('/peserta/{id}/delete', [PesertaController::class, 'destroy']);
Route::get('/peserta/{id}/edit', [PesertaController::class, 'edit']);
Route::post('/peserta/{id}/update', [PesertaController::class, 'update']);
Route::post('/peserta/vote-status', [PesertaController::class, 'vote_status']);
Route::post('/peserta/vote-off', [PesertaController::class, 'vote_off']);

Route::post('/voter/truncate', [VoterController::class, 'truncate']);
Route::get('/voter', [VoterController::class, 'index']);
Route::post('/voter/box-penilaian', [VoterController::class, 'box_penilaian']);

Route::get('/export/voter', [ExportController::class, 'export_excel']);
Route::get('/export/voter-nilai', [ExportController::class, 'export_nilai']);
Route::get('/export/report-data-peserta', [ExportController::class, 'report_data_peserta']);

Route::get('/voting', [VotingController::class, 'index']);
Route::post('/voting/peserta', [VotingController::class, 'peserta']);