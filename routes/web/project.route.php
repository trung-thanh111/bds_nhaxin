<?php   
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\V1\RealEstate\ProjectController;
use App\Http\Controllers\Backend\V1\RealEstate\ProjectCatalogueController;

Route::group(['middleware' => ['admin','locale','backend_default_locale']], function () {
    
    Route::group(['prefix' => 'project'], function () {
        Route::get('index', [ProjectController::class, 'index'])->name('project.index');
        Route::get('create', [ProjectController::class, 'create'])->name('project.create');
        Route::post('store', [ProjectController::class, 'store'])->name('project.store');
        Route::get('{id}/edit', [ProjectController::class, 'edit'])->where(['id' => '[0-9]+'])->name('project.edit');
        Route::post('{id}/update', [ProjectController::class, 'update'])->where(['id' => '[0-9]+'])->name('project.update');
        Route::get('{id}/delete', [ProjectController::class, 'delete'])->where(['id' => '[0-9]+'])->name('project.delete');
        Route::delete('{id}/destroy', [ProjectController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('project.destroy');

        Route::group(['prefix' => 'catalogue'], function () {
            Route::get('index', [ProjectCatalogueController::class, 'index'])->name('project.catalogue.index');
            Route::get('create', [ProjectCatalogueController::class, 'create'])->name('project.catalogue.create');
            Route::post('store', [ProjectCatalogueController::class, 'store'])->name('project.catalogue.store');
            Route::get('{id}/edit', [ProjectCatalogueController::class, 'edit'])->where(['id' => '[0-9]+'])->name('project.catalogue.edit');
            Route::post('{id}/update', [ProjectCatalogueController::class, 'update'])->where(['id' => '[0-9]+'])->name('project.catalogue.update');
            Route::get('{id}/delete', [ProjectCatalogueController::class, 'delete'])->where(['id' => '[0-9]+'])->name('project.catalogue.delete');
            Route::delete('{id}/destroy', [ProjectCatalogueController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('project.catalogue.destroy');
        });
    });

});
