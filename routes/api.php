<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    'festivals' => 'FestivalController',
    'artists' => 'ArtistController',
    'genres' => 'GenreController',
    'photos' => 'PhotoController'
]);

// Nested resources => only list and details
Route::resource('artists.festivals', 'ArtistFestivalsController', ['only' => [
    'index', 'show'
]]);

Route::resource('festivals.artists', 'FestivalArtistsController', ['only' => [
    'index', 'show'
]]);

Route::resource('genres.festivals', 'GenreFestivalsController', ['only' => [
    'index', 'show'
]]);

Route::resource('genres.artists', 'GenreArtistsController', ['only' => [
    'index', 'show'
]]);

Route::resource('festivals.photos', 'FestivalPhotosController', ['only' => [
    'index', 'show'
]]);