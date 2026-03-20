<?php   
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\V1\RealEstate\RealEstateCatalogueController;
use App\Http\Controllers\Backend\V1\RealEstate\RealEstateController;

Route::group(['middleware' => ['admin','locale','backend_default_locale']], function () {
    
    Route::group(['prefix' => 'real/estate/catalogue'], function () {
        Route::get('index', [RealEstateCatalogueController::class, 'index'])->name('real_estate.catalogue.index');
        Route::get('create', [RealEstateCatalogueController::class, 'create'])->name('real_estate.catalogue.create');
        Route::post('store', [RealEstateCatalogueController::class, 'store'])->name('real_estate.catalogue.store');
        Route::get('{id}/edit', [RealEstateCatalogueController::class, 'edit'])->where(['id' => '[0-9]+'])->name('real_estate.catalogue.edit');
        Route::post('{id}/update', [RealEstateCatalogueController::class, 'update'])->where(['id' => '[0-9]+'])->name('real_estate.catalogue.update');
        Route::get('{id}/delete', [RealEstateCatalogueController::class, 'delete'])->where(['id' => '[0-9]+'])->name('real_estate.catalogue.delete');
        Route::delete('{id}/destroy', [RealEstateCatalogueController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('real_estate.catalogue.destroy');
    });

    Route::group(['prefix' => 'real/estate'], function () {
        Route::get('index', [RealEstateController::class, 'index'])->name('real.estate.index');
        Route::get('create', [RealEstateController::class, 'create'])->name('real.estate.create');
        Route::post('store', [RealEstateController::class, 'store'])->name('real.estate.store');
        Route::get('{id}/edit', [RealEstateController::class, 'edit'])->where(['id' => '[0-9]+'])->name('real.estate.edit');
        Route::post('{id}/update', [RealEstateController::class, 'update'])->where(['id' => '[0-9]+'])->name('real.estate.update');
        Route::get('{id}/delete', [RealEstateController::class, 'delete'])->where(['id' => '[0-9]+'])->name('real.estate.delete');
        Route::delete('{id}/destroy', [RealEstateController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('real.estate.destroy');
    });

});
