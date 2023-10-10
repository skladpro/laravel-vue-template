<?php

use Illuminate\Support\Facades\Route;

//Работа с авторизацией
Route::group(['namespace' => 'App\Http\Controllers\Auth', 'prefix' => 'login'], function () {
    // Страница с формой
    Route::get('/', 'IndexController')->name('auth.index');
    // Обработка формы
    Route::post('/', 'StoreController')->name('auth.store');

    // Выход с аккаунта
    Route::group(['namespace' => 'Logout', 'prefix' => 'logout'], function () {
        Route::get('/', 'IndexController')->name('logout.index');
    });
});


// Работа с главной страницей
Route::group(['namespace' => 'App\Http\Controllers\Main', 'prefix' => '', 'middleware' => ['login']], function () {
    // Главная страница
    Route::get('/', 'IndexController')->name('main.index');

    // Работа со списком одежды
    Route::group(['namespace' => 'ClothesList', 'prefix' => ''], function () {
        Route::get('/clothesList', 'IndexController')->name('clothes.index');
    });

    // Группы с одеждой
    Route::group(['namespace' => 'Groups', 'prefix' => ''], function () {
        Route::get('/groups', 'IndexController')->name('groups.index');
    });
});
