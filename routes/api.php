<?php

use Illuminate\Support\Facades\Route;

Route::get('/user', 'UserController@show')
     ->name('user');

Route::get('/search', 'SearchProfileController@index')
     ->name('search');

Route::get('/search/{profile}', 'SearchProfileController@show');

Route::name('favorites.')
     ->prefix('/favorites')
     ->group(function () {

         Route::get('/', 'FavoriteController@index')
              ->name('index');

         Route::prefix('/{profile}')
              ->group(function () {

                  Route::get('/', 'FavoriteController@show')
                       ->name('show');

                  Route::put('/', 'FavoriteController@update')
                       ->name('store');
              });
     });

Route::name('profiles.')
     ->prefix('/profiles')
     ->group(function () {

         Route::get('/', 'ProfileController@index')
              ->name('index');
         Route::post('/', 'ProfileController@store')
              ->name('store');

         Route::prefix('/{profile}')
              ->group(function () {

                  Route::get('/', 'ProfileController@show')
                       ->name('show');
                  Route::patch('/', 'ProfileController@update')
                       ->name('update');
                  Route::delete('/', 'ProfileController@destroy')
                       ->name('destroy');

                  Route::name('relations.')
                       ->prefix('/relations')
                       ->group(function () {
                           Route::get('/', 'ProfileRelationController@index')
                                ->name('index');

                           Route::get('/unrelated', 'ProfileRelationController@unrelatedProfiles');
                           Route::post('/', 'ProfileRelationController@store')
                                ->name('store');

                           Route::prefix('/{relation}')
                                ->group(function () {
                                    Route::patch('/', 'ProfileRelationController@update')
                                         ->name('update');
                                    Route::delete('/', 'ProfileRelationController@destroy')
                                         ->name('destroy');
                                });
                       });

                  Route::prefix('/locations')
                      ->name('locations.')
                      ->group(function () {
                          Route::get('/', 'LocationController@index')
                              ->name('index');


                          Route::get('/get-marker', 'LocationController@getMarkerData')
                              ->name('get-marker');

                          Route::get('/get-address', 'LocationController@getAddress')
                              ->name('get-address');

                          Route::post('/', 'LocationController@store')
                              ->name('store');

                          Route::prefix('/{location}')
                              ->group(function () {
                                  Route::patch('/', 'LocationController@update')
                                      ->name('update');

                                  Route::delete('/', 'LocationController@destroy')
                                      ->name('delete');
                              });
                      });

                  Route::name('tags.')
                       ->prefix('/tags')
                       ->group(function () {
                           Route::get('/', 'ProfileTagController@index');
                           Route::post('/', 'ProfileTagController@store');

                           Route::delete('/{tag}', 'ProfileTagController@destroy');
                       });

                  Route::prefix('/contacts')
                       ->name('contacts.')
                       ->group(function () {
                           Route::get('/', 'ContactController@index')
                                ->name('index');

                           Route::post('/', 'ContactController@store')
                                ->name('store');

                           Route::prefix('/{contact}')
                                ->group(function () {
                                    Route::patch('/', 'ContactController@update')
                                         ->name('update');

                                    Route::delete('/', 'ContactController@destroy')
                                         ->name('delete');
                                });
                       });
                  
                  Route::prefix('/files')
                       ->name('files.')
                       ->group(function () {
                           Route::get('/', 'FileController@index')
                                ->name('index');

                           Route::post('/', 'FileController@store')
                                ->name('store');

                           Route::prefix('/{file}')
                                ->group(function () {
                                    Route::patch('/', 'FileController@update')
                                         ->name('update');

                                    Route::delete('/', 'FileController@destroy')
                                         ->name('delete');
                                });
                       });

              });
     });
