<?php

use App\Http\Controllers\API\BookController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/books', BookController::class);

//A book controller rent függvényét hívjuk meg vele
Route::post("/books/{id}/rent", [BookController::class, "rent"]);
