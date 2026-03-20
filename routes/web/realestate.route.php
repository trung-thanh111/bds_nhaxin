<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\V2\RealEstate\PropertyController;
use App\Http\Controllers\Backend\V2\RealEstate\PropertyFacilityController;
use App\Http\Controllers\Backend\V2\RealEstate\FloorplanController;
use App\Http\Controllers\Backend\V2\RealEstate\FloorplanRoomController;
use App\Http\Controllers\Backend\V2\RealEstate\GalleryController;
use App\Http\Controllers\Backend\V2\RealEstate\GalleryCatalogueController;
use App\Http\Controllers\Backend\V2\RealEstate\LocationHighlightController;
use App\Http\Controllers\Backend\V2\RealEstate\AgentController;
use App\Http\Controllers\Backend\V2\RealEstate\ContactRequestController;

Route::group(['middleware' => ['admin', 'locale', 'backend_default_locale'], 'as' => ''], function () {
    // Property
    Route::get('property/index', [PropertyController::class, 'index'])->name('property.index');
    Route::get('property/create', [PropertyController::class, 'create'])->name('property.create');
    Route::post('property/store', [PropertyController::class, 'store'])->name('property.store');
    Route::get('property/{id}/edit', [PropertyController::class, 'edit'])->where(['id' => '[0-9]+'])->name('property.edit');
    Route::post('property/{id}/update', [PropertyController::class, 'update'])->where(['id' => '[0-9]+'])->name('property.update');
    Route::get('property/{id}/delete', [PropertyController::class, 'delete'])->where(['id' => '[0-9]+'])->name('property.delete');
    Route::delete('property/{id}/destroy', [PropertyController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('property.destroy');

    // PropertyFacility
    Route::get('property_facility/index', [PropertyFacilityController::class, 'index'])->name('property_facility.index');
    Route::get('property_facility/create', [PropertyFacilityController::class, 'create'])->name('property_facility.create');
    Route::post('property_facility/store', [PropertyFacilityController::class, 'store'])->name('property_facility.store');
    Route::get('property_facility/{id}/edit', [PropertyFacilityController::class, 'edit'])->where(['id' => '[0-9]+'])->name('property_facility.edit');
    Route::post('property_facility/{id}/update', [PropertyFacilityController::class, 'update'])->where(['id' => '[0-9]+'])->name('property_facility.update');
    Route::get('property_facility/{id}/delete', [PropertyFacilityController::class, 'delete'])->where(['id' => '[0-9]+'])->name('property_facility.delete');
    Route::delete('property_facility/{id}/destroy', [PropertyFacilityController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('property_facility.destroy');


    // Gallery
    Route::get('gallery/index', [GalleryController::class, 'index'])->name('gallery.index');
    Route::get('gallery/create', [GalleryController::class, 'create'])->name('gallery.create');
    Route::post('gallery/store', [GalleryController::class, 'store'])->name('gallery.store');
    Route::get('gallery/{id}/edit', [GalleryController::class, 'edit'])->where(['id' => '[0-9]+'])->name('gallery.edit');
    Route::post('gallery/{id}/update', [GalleryController::class, 'update'])->where(['id' => '[0-9]+'])->name('gallery.update');
    Route::get('gallery/{id}/delete', [GalleryController::class, 'delete'])->where(['id' => '[0-9]+'])->name('gallery.delete');
    Route::delete('gallery/{id}/destroy', [GalleryController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('gallery.destroy');

    // GalleryCatalogue
    Route::group(['prefix' => 'gallery/catalogue'], function () {
        Route::get('index', [GalleryCatalogueController::class, 'index'])->name('gallery.catalogue.index');
        Route::get('create', [GalleryCatalogueController::class, 'create'])->name('gallery.catalogue.create');
        Route::post('store', [GalleryCatalogueController::class, 'store'])->name('gallery.catalogue.store');
        Route::get('{id}/edit', [GalleryCatalogueController::class, 'edit'])->where(['id' => '[0-9]+'])->name('gallery.catalogue.edit');
        Route::post('{id}/update', [GalleryCatalogueController::class, 'update'])->where(['id' => '[0-9]+'])->name('gallery.catalogue.update');
        Route::get('{id}/delete', [GalleryCatalogueController::class, 'delete'])->where(['id' => '[0-9]+'])->name('gallery.catalogue.delete');
        Route::delete('{id}/destroy', [GalleryCatalogueController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('gallery.catalogue.destroy');
    });

    // LocationHighlight
    Route::get('location_highlight/index', [LocationHighlightController::class, 'index'])->name('location_highlight.index');
    Route::get('location_highlight/create', [LocationHighlightController::class, 'create'])->name('location_highlight.create');
    Route::post('location_highlight/store', [LocationHighlightController::class, 'store'])->name('location_highlight.store');
    Route::get('location_highlight/{id}/edit', [LocationHighlightController::class, 'edit'])->where(['id' => '[0-9]+'])->name('location_highlight.edit');
    // NOTE: Check if there's a typo in the original file (location_highlight/{id}/update) vs property
    Route::post('location_highlight/{id}/update', [LocationHighlightController::class, 'update'])->where(['id' => '[0-9]+'])->name('location_highlight.update');
    Route::get('location_highlight/{id}/delete', [LocationHighlightController::class, 'delete'])->where(['id' => '[0-9]+'])->name('location_highlight.delete');
    Route::delete('location_highlight/{id}/destroy', [LocationHighlightController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('location_highlight.destroy');

    // Agent
    Route::get('agent/index', [AgentController::class, 'index'])->name('agent.index');
    Route::get('agent/create', [AgentController::class, 'create'])->name('agent.create');
    Route::post('agent/store', [AgentController::class, 'store'])->name('agent.store');
    Route::get('agent/{id}/edit', [AgentController::class, 'edit'])->where(['id' => '[0-9]+'])->name('agent.edit');
    Route::post('agent/{id}/update', [AgentController::class, 'update'])->where(['id' => '[0-9]+'])->name('agent.update');
    Route::get('agent/{id}/delete', [AgentController::class, 'delete'])->where(['id' => '[0-9]+'])->name('agent.delete');
    Route::delete('agent/{id}/destroy', [AgentController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('agent.destroy');

    // ContactRequest
    Route::get('contact_request/index', [ContactRequestController::class, 'index'])->name('contact_request.index');
    Route::get('contact_request/create', [ContactRequestController::class, 'create'])->name('contact_request.create');
    Route::post('contact_request/store', [ContactRequestController::class, 'store'])->name('contact_request.store');
    Route::get('contact_request/{id}/edit', [ContactRequestController::class, 'edit'])->where(['id' => '[0-9]+'])->name('contact_request.edit');
    Route::post('contact_request/{id}/update', [ContactRequestController::class, 'update'])->where(['id' => '[0-9]+'])->name('contact_request.update');
    Route::get('contact_request/{id}/delete', [ContactRequestController::class, 'delete'])->where(['id' => '[0-9]+'])->name('contact_request.delete');
    Route::delete('contact_request/{id}/destroy', [ContactRequestController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('contact_request.destroy');
});
