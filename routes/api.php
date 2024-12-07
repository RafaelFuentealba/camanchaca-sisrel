<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BitacoraController;
use App\Http\Controllers\Api\DigitadorBitacoraController;

Route::get("mobile/role", [AuthController::class, 'getRole']);
Route::post("mobile/login", [AuthController::class, 'logIn']);
Route::post("mobile/check-token", [AuthController::class, 'checkToken']);
Route::put("mobile/change-password", [AuthController::class, 'changePassword']);
Route::get("mobile/activities", [BitacoraController::class, 'getActivities']);
Route::post("mobile/filter-activities", [BitacoraController::class,'filterActivities']);
Route::post("mobile/new-activity", [BitacoraController::class, 'createActivity']);
Route::put("mobile/edit-activity", [BitacoraController::class, 'updateActivity']);
Route::get("mobile/activity/data", [BitacoraController::class, 'getInfo']);
Route::get("mobile/activity/{id}", [BitacoraController::class, 'getActivity']);
Route::delete("mobile/activity/{acti_codigo}", [BitacoraController::class, 'deleteActivity']);
Route::get("mobile/activity/more/{acti_codigo}", [BitacoraController::class, 'showActivity']);
Route::post("mobile/activity_date", [BitacoraController::class, 'getActivityByDate']);

Route::post("mobile/digitador/actividad/listar-participantes", [DigitadorBitacoraController::class, 'listarParticipantes']);
Route::post("mobile/digitador/actividad/obtener-dirigente", [DigitadorBitacoraController::class, 'obtenerDirigente']);
Route::post("mobile/digitador/actividad/agregar-participante", [DigitadorBitacoraController::class, 'agregarParticipante']);
Route::delete("mobile/digitador/actividad/eliminar-participante", [DigitadorBitacoraController::class, 'eliminarParticipante']);