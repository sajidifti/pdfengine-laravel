<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;

Route::get('/url', [PDFController::class, 'urlPreview'])->name('pdf.url.preview');
Route::post('/html', [PDFController::class, 'htmlPreview'])->name('pdf.html.preview');

Route::get('/html/render/{id}', [PDFController::class, 'renderHtml'])->name('pdf.html.render');

Route::get('pdf', [PDFController::class, 'preview'])->name('pdf.preview');
Route::match(['get', 'post'], 'pdf/download', [PDFController::class, 'generatePDF'])->name('pdf.download');
