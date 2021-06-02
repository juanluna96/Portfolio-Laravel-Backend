<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
});

/* -------------------------------------------------------------------------- */
/*                           API FOR PORTFOLIO HOME                           */
/* -------------------------------------------------------------------------- */

Route::group([
    'prefix' => '{locale}'
], function () {
    Route::get('proyects', 'ProyectController@languages');
    Route::get('categories', 'CategoryController@CategoriesLanguageProyects');
    Route::get('categories/{id}', 'CategoryController@showCategoryProyects');
    Route::get('categories/{id}/descriptions', 'CategoryController@CategoriesDescriptionsLanguage');
});

/* -------------------------------------------------------------------------- */
/*                           API FOR ADMIN PORTFOLIO                          */
/* -------------------------------------------------------------------------- */

/* ---------------------- SHOW CATEGORIES WITH PROYECTS --------------------- */
Route::get('categoriesProyects', 'CategoryController@CategoriesProyects');
/* -------------------------------- PROYECTS -------------------------------- */
Route::resource('proyects', 'ProyectController', ['except' => ['create', 'edit']]);
/* ------------------------------- CATEGORIES ------------------------------- */
Route::resource('categories', 'CategoryController', ['except' => ['create', 'edit']]);
/* ---------------------- BIOGRAPHY WITH MY INFORMATION --------------------- */
Route::resource('biographies', 'BiographyController', ['except' => ['create', 'edit']]);
/* --------------------------- COMPANY FOR PROYECT -------------------------- */
Route::resource('companies', 'CompanyController', ['except' => ['create', 'edit']]);
/* -------------------------- MESSAGE TO CONTACT ME ------------------------- */
Route::resource('contacts', 'ContactController', ['except' => ['create', 'edit']]);
Route::get('newsmessages', 'ContactController@news');
