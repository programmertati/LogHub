<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CreateUserController;
use App\Http\Controllers\Api\UpdateStatusPegawaiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware('api.token')->group(function () {
//     Route::post('/users', [CreateUserController::class, 'create']);
// });
Route::post('/users', [CreateUserController::class, 'create']);
Route::post('/statusPegawai', [UpdateStatusPegawaiController::class, 'updateStatusPegawai']);
