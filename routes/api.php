<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post("/register", [AuthenticationController::class, "register"]);

Route::post("/login", [AuthenticationController::class, "login"]);

Route::get("/albums", [AlbumController::class, "getAlbums"]);

Route::get("/albums/{id}", [AlbumController::class, "getAlbum"]);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post("/logout", [AuthenticationController::class, "logout"]);

    Route::get("/user", [UserController::class, "getUser"]);

    Route::get("/user/{id}", [UserController::class, "getUserById"]);

    Route::get("/played", [ListController::class, "getPlayedAlbums"]);

    Route::get("/listenlist", [ListController::class, "getListenList"]);

    Route::get("/albums/{id}/reviews", [AlbumController::class, "getAlbumReviews"]);

    Route::get("/user/{id}/reviews", [UserController::class, "getUserReviews"]);

    Route::get("/user/albums/{id}", [ListController::class, "getUserAlbum"]);

    Route::post("/played", [ListController::class, "setPlayedAlbum"]);

    Route::post("/listenlist", [ListController::class, "setListenListAlbum"]);

    Route::put("/user", [UserController::class, "updateUser"]);

    Route::put("/user/rating/{albumId}", [ListController::class, "updateAlbumRating"]);

    Route::put("/user/review/{albumId}", [ListController::class, "updateAlbumReview"]);

    Route::delete("/played/{albumId}", [ListController::class, "deletePlayedAlbum"]);

    Route::delete("/listenlist/{albumId}", [ListController::class, "deleteListenListAlbum"]);
});
