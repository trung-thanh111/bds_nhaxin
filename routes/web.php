<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\RouterController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\Payment\VnpayController;
use App\Http\Controllers\Frontend\Payment\PaypalController;
use App\Http\Controllers\Frontend\ProductCatalogueController as FeProductCatalogueController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\AmenitiesController;
use App\Http\Controllers\Frontend\NeighbourhoodController;
use App\Http\Controllers\Frontend\ContactRequestController;
use App\Http\Controllers\CrawlerController;
use App\Http\Controllers\Frontend\PostCatalogueController;
use App\Http\Controllers\Frontend\PostController;

//@@useController@@

require __DIR__ . '/web/user.route.php';
require __DIR__ . '/web/customer.route.php';
require __DIR__ . '/web/core.route.php';
require __DIR__ . '/web/product.route.php';
require __DIR__ . '/web/post.route.php';
require __DIR__ . '/web/auth.route.php';
require __DIR__ . '/web/ajax.route.php';
require __DIR__ . '/web/custom.route.php';
require __DIR__ . '/web/realestate.route.php';
require __DIR__ . '/web/real_estate.route.php';
require __DIR__ . '/web/amenity.route.php';
require __DIR__ . '/web/floorplan.route.php';
require __DIR__ . '/web/project.route.php';

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
/* FRONTEND ROUTES  */
Route::group(['middleware' => ['locale']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home.index');
    Route::get('bat-dong-san.html', [AboutController::class, 'index'])->name('about.index');
    Route::get('thu-vien-anh.html', [GalleryController::class, 'index'])->name('fe.gallery.index');
    Route::get('tien-nghi.html', [AmenitiesController::class, 'index'])->name('amenities.index');
    Route::get('xung-quanh.html', [NeighbourhoodController::class, 'index'])->name('neighbourhood.index');
    Route::get('lien-he.html', [ContactController::class, 'index'])->name('contact.index');
    Route::get('bai-viet.html', [PostController::class, 'index'])->name('fe.post.index');
    Route::post('ajax/contact-request/store', [ContactRequestController::class, 'store'])->name('contact-request.store');
    Route::get('/thumb', [App\Http\Controllers\ImageResizerController::class, 'resize'])->name('thumb');

    Route::get('tim-kiem.html', [\App\Http\Controllers\Frontend\SearchController::class, 'index'])->name('search.index');

    Route::get('du-an.html', [App\Http\Controllers\Frontend\ProjectCatalogueController::class, 'all'])->name('project.all');
    Route::get('du-an/trang-{page}.html', [App\Http\Controllers\Frontend\ProjectCatalogueController::class, 'all'])->where(['page' => '[0-9]+'])->name('project.all.paginate');

    Route::get('{canonical}/trang-{page}', [RouterController::class, 'page'])
        ->where(['page' => '[0-9]+'])
        ->name('router.page');

    Route::get('{canonical}', [RouterController::class, 'index'])
        ->name('router.index');
});
