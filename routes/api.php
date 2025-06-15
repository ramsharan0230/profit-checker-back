
<?php

use App\Http\Controllers\ExportReportController;
use App\Http\Controllers\OpenAISuggestionController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('/products')->group(function () {
    Route::get('/', [ProductController::class, 'fetchProducts']);
});

Route::post('/suggestion', [OpenAISuggestionController::class, 'suggestion'])->name('suggestion');

Route::post('/export-quote/csv', [ExportReportController::class, 'exportCsv']);

Route::prefix('/report')->group(function(){
    Route::post('/export-quote-summary', [ExportReportController::class, 'exportReport']);
});