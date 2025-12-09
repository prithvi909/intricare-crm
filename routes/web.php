<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\MergeHistoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('contacts.index');
});

// Contacts
Route::prefix('contacts')->group(function () {
    Route::get('/', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/datatable', [ContactController::class, 'datatable'])->name('contacts.datatable');
    Route::post('/', [ContactController::class, 'store'])->name('contacts.store');
    Route::get('/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::put('/{contact}', [ContactController::class, 'update'])->name('contacts.update');
    Route::delete('/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::post('/merge', [ContactController::class, 'merge'])->name('contacts.merge');
    Route::post('/merge-preview', [ContactController::class, 'getMergePreview'])->name('contacts.merge-preview');
});

// Custom Fields
Route::prefix('custom-fields')->group(function () {
    Route::get('/', [CustomFieldController::class, 'index'])->name('custom-fields.index');
    Route::get('/all', [CustomFieldController::class, 'getAll'])->name('custom-fields.all');
    Route::post('/', [CustomFieldController::class, 'store'])->name('custom-fields.store');
    Route::put('/{customField}', [CustomFieldController::class, 'update'])->name('custom-fields.update');
    Route::delete('/{customField}', [CustomFieldController::class, 'destroy'])->name('custom-fields.destroy');
    Route::patch('/{customField}/toggle', [CustomFieldController::class, 'toggleActive'])->name('custom-fields.toggle');
});

// Merge History
Route::get('/merge-history', [MergeHistoryController::class, 'index'])->name('merge-history.index');
