<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('tickets')->group(function () {
    Route::get('/', [TicketController::class, 'index']);
    Route::post('create', [TicketController::class, 'store']);
    Route::get('{id}', [TicketController::class, 'ticketDetails']);
    Route::patch('update/{id}', [TicketController::class, 'update']);
});