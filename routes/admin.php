<?php

use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\ServicesAdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactSubmissionsController;
use App\Http\Controllers\LanguagesController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PageSettingsController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProductCategoriesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SettingTypesController;
use Illuminate\Support\Facades\Route;

Route::get('/', 'AdminController@dashboard')->name('dashboard');

Route::resource('admins', AdminUsersController::class);
Route::resource('pages', PagesController::class);
Route::resource('setting-types', SettingTypesController::class);
Route::resource('page-settings', PageSettingsController::class);
Route::resource('languages', LanguagesController::class)->only(['index', 'store', 'destroy']);
Route::post('/languages/{language}/toggle', 'LanguagesController@toggle')->name('languages.toggle');
Route::get('/settings/delete-file/{settingId?}', 'PageSettingsController@deleteFile')->name('delete-setting-file');

Route::resource('product-categories', ProductCategoriesController::class);
Route::post('/products/reorder', 'ProductsController@reorder')->name('products.reorder');
Route::resource('products', ProductsController::class);

Route::resource('blog-posts', BlogController::class);

// Media library
Route::get('/media',             [MediaController::class, 'index'])->name('media.index');
Route::get('/media/picker-data', [MediaController::class, 'pickerData'])->name('media.picker-data');
Route::post('/media/upload',     [MediaController::class, 'upload'])->name('media.upload');
Route::get('/media/{medium}',    [MediaController::class, 'show'])->name('media.show');
Route::put('/media/{medium}',    [MediaController::class, 'update'])->name('media.update');
Route::delete('/media/{medium}', [MediaController::class, 'destroy'])->name('media.destroy');

Route::post('/services-admin/reorder', 'ServicesAdminController@reorder')->name('services-admin.reorder');
Route::resource('services-admin', ServicesAdminController::class)->parameters(['services-admin' => 'service']);
Route::get('/settings',       'SettingsController@index')->name('settings.index');

// Contact submissions
Route::get('/contact-submissions',                    [ContactSubmissionsController::class, 'index'])->name('contact-submissions.index');
Route::get('/contact-submissions/{contactSubmission}', [ContactSubmissionsController::class, 'show'])->name('contact-submissions.show');
Route::post('/contact-submissions/{contactSubmission}/mark-responded', [ContactSubmissionsController::class, 'markResponded'])->name('contact-submissions.mark-responded');
Route::delete('/contact-submissions/{contactSubmission}', [ContactSubmissionsController::class, 'destroy'])->name('contact-submissions.destroy');
