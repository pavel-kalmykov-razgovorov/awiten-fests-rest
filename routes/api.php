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

// Auth
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::apiResources([
    'festivals' => 'FestivalController',
    'artists' => 'ArtistController',
    'genres' => 'GenreController',
    'photos' => 'PhotoController',
    'posts' => 'PostController'
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

Route::resource('festivals.posts', 'FestivalPostsController', ['only' => [
    'index', 'show'
]]);

// Multimedia (Images)
Route::get('/artists/{artist}/profile', [
    'uses' => 'ImagesController@getProfileImage',
    'as' => 'images.artist_profile'
]);

Route::get('/artists/{artist}/header', [
    'uses' => 'ImagesController@getHeaderImage',
    'as' => 'images.artist_header'
]);

Route::get('/festivals/{festival}/logo', [
    'uses' => 'ImagesController@getLogoImage',
    'as' => 'images.festival_logo'
]);

Route::get('/photos/{photo}/image', [
    'uses' => 'ImagesController@getFestivalPhoto',
    'as' => 'images.photo_image'
]);
