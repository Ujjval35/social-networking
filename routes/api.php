<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\v1\PostController,
    App\Http\Controllers\Api\v1\WebsiteController,
    App\Http\Controllers\Api\v1\WebsiteSubscribeController;

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

Route::group(['prefix' => 'v1'], function () {

    // Post Routes
    Route::apiResource('posts', PostController::class);

    // Website Routes
    Route::apiResource('websites', WebsiteController::class)->except('show');

    // Website Subscription Routes
    Route::apiResource('wesites-subscription', WebsiteSubscribeController::class)->except('show', 'destroy');

});
