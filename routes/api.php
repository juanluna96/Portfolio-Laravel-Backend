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
    /* ---------------------- GET ALL PROYECTS BY LANGUAGE ---------------------- */
    Route::get('proyects', 'ProyectController@languages');
    /* -------------- GET CATEGORIES THAT HAVE PROYECTS BY LANGUAGE ------------- */
    Route::get('categories', 'CategoryController@CategoriesWithProyects');
    Route::get('categories_descriptions', 'CategoryController@CategoriesDescriptionWithAllLanguages');
    Route::get('categories/{id}', 'CategoryController@showCategoryProyects');
    /* --------------- GET DESCRIPTIONS OF CATEGORIES BY LANGUAGES -------------- */
    Route::get('categories/{id}/descriptions', 'CategoryController@CategoriesDescriptionsLanguage');
    /* --------------- SEND CONTACT FORM AND VALIDATE BY LANGUAGE --------------- */
    Route::post('contacts', 'ContactController@store');
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
Route::resource('contacts', 'ContactController', ['except' => ['create', 'edit', 'store']]);
Route::get('newsmessages', 'ContactController@news');
/* --------------------------- IMAGES FOR PROYECTS -------------------------- */
Route::resource('images', 'ImageController', ['except' => ['create', 'edit', 'update']]);
/* -------------------------------- LANGUAGES ------------------------------- */
Route::resource('languages', 'LanguageController', ['only' => ['index']]);
