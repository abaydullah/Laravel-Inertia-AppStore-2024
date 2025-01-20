<?php

use Abaydullah\ApkParser\Parser;
use Abaydullah\GooglePlayScraper\Scraper;
use App\Http\Controllers\Admin\AppController;
use App\Http\Controllers\Frontend\AppController as FrontendAppController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Frontend\DeveloperController as FrontendDeveloperController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeveloperController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ScrapeController;
use App\Http\Controllers\Admin\VersionController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;



Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('/app/{app:slug}', [FrontendAppController::class,'view'])->name('app.view');
Route::get('/app/{app:slug}/versions', [FrontendAppController::class,'versions'])->name('app.view.versions');
Route::get('/app/{app:slug}/version/{version:version}', [FrontendAppController::class,'version'])->name('app.view.version');
Route::get('/app/{app:slug}/version/download/{version:version}', [FrontendAppController::class,'download'])->name('app.view.download');
Route::post('/app/review/store', [FrontendAppController::class,'store'])->name('app.view.review.store');
Route::put('/app/review/update/{id}', [FrontendAppController::class,'update'])->name('app.view.review.update');
Route::delete('/app/review/destroy/{id}', [FrontendAppController::class,'destroy'])->name('app.view.review.destroy');
Route::get('/apps', [FrontendAppController::class,'index'])->name('app.index');
Route::any('/search', [SearchController::class,'search'])->name('app.search');
Route::get('/categories/{category:slug}', [FrontendCategoryController::class,'view'])->name('categories.view');
Route::get('/developers/{developer:slug}', [FrontendDeveloperController::class,'view'])->name('developers.view');
Route::group(['middleware'=> ['auth', 'verified'],'prefix' => 'admin', 'as' => 'admin.'], function () {

Route::get('/dashboard', DashboardController::class)->name('dashboard');
Route::resource('/categories', CategoryController::class);
Route::resource('/developers', DeveloperController::class);
Route::resource('/apps', AppController::class);
Route::resource('apps.versions', VersionController::class);
Route::patch('/apps/{id}/versions/{id}', [VersionController::class,'updateVersion'])->name('apps.versions.update.data');
//scrape
Route::post('/apps/sync', [AppController::class,'sync'])->name('apps.sync');
Route::post('/apps/scrape', [AppController::class,'scrape'])->name('apps.scrape');
Route::any('/scrape/index', [ScrapeController::class,'index'])->name('scrape.index');
Route::post('/scrape/search', [ScrapeController::class,'search'])->name('scrape.search');
Route::post('/scrape/store', [ScrapeController::class,'store'])->name('scrape.store');

Route::resource('/reviews', ReviewController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
