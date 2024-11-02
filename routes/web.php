<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;

Route::get('/', function () {
    return response()->json([
        'message' => 'Permission Denied',
    ], 419);
});


Route::get('pdf', [PDFController::class, 'preview'])->name('pdf.preview');
Route::get('pdf/download', [PDFController::class, 'generatePDF'])->name('pdf.download');
// Route::post('pdf/post', [PDFController::class, 'generatePDF'])->name('pdf.post');
