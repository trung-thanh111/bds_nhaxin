<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\V1\RealEstate\FloorplanController;

Route::group(['middleware' => ['admin', 'locale', 'backend_default_locale']], function () {
    Route::get('floorplan/index', [FloorplanController::class, 'index'])->name('floorplan.index');
    Route::get('floorplan/create', [FloorplanController::class, 'create'])->name('floorplan.create');
    Route::post('floorplan/store', [FloorplanController::class, 'store'])->name('floorplan.store');
    Route::get('floorplan/{id}/edit', [FloorplanController::class, 'edit'])->where(['id' => '[0-9]+'])->name('floorplan.edit');
    Route::post('floorplan/{id}/update', [FloorplanController::class, 'update'])->where(['id' => '[0-9]+'])->name('floorplan.update');
    Route::get('floorplan/{id}/delete', [FloorplanController::class, 'delete'])->where(['id' => '[0-9]+'])->name('floorplan.delete');
    Route::delete('floorplan/{id}/destroy', [FloorplanController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('floorplan.destroy');
});
