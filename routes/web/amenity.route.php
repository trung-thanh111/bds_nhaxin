<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\V1\Amenity\AmenityCatalogueController;
use App\Http\Controllers\Backend\V1\Amenity\AmenityController;

Route::group(['middleware' => ['admin', 'locale', 'backend_default_locale']], function () {
    /* AMENITY CATALOGUE */
    Route::get('amenity/catalogue/index', [AmenityCatalogueController::class, 'index'])->name('amenity.catalogue.index');
    Route::get('amenity/catalogue/create', [AmenityCatalogueController::class, 'create'])->name('amenity.catalogue.create');
    Route::post('amenity/catalogue/store', [AmenityCatalogueController::class, 'store'])->name('amenity.catalogue.store');
    Route::get('amenity/catalogue/{id}/edit', [AmenityCatalogueController::class, 'edit'])->where(['id' => '[0-9]+'])->name('amenity.catalogue.edit');
    Route::post('amenity/catalogue/{id}/update', [AmenityCatalogueController::class, 'update'])->where(['id' => '[0-9]+'])->name('amenity.catalogue.update');
    Route::get('amenity/catalogue/{id}/delete', [AmenityCatalogueController::class, 'delete'])->where(['id' => '[0-9]+'])->name('amenity.catalogue.delete');
    Route::delete('amenity/catalogue/{id}/destroy', [AmenityCatalogueController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('amenity.catalogue.destroy');

    /* AMENITY */
    Route::get('amenity/index', [AmenityController::class, 'index'])->name('amenity.index');
    Route::get('amenity/create', [AmenityController::class, 'create'])->name('amenity.create');
    Route::post('amenity/store', [AmenityController::class, 'store'])->name('amenity.store');
    Route::get('amenity/{id}/edit', [AmenityController::class, 'edit'])->where(['id' => '[0-9]+'])->name('amenity.edit');
    Route::post('amenity/{id}/update', [AmenityController::class, 'update'])->where(['id' => '[0-9]+'])->name('amenity.update');
    Route::get('amenity/{id}/delete', [AmenityController::class, 'delete'])->where(['id' => '[0-9]+'])->name('amenity.delete');
    Route::delete('amenity/{id}/destroy', [AmenityController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('amenity.destroy');
});
