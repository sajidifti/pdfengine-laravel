<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;

Route::get('/', function () {
    return response()->json([
        'message' => 'Permission Denied',
    ], 419);
});


Route::get('pdf', [PDFController::class, 'generatePDF'])->name('pdf');
