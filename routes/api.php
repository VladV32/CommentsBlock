<?php

use App\Http\Controllers\Api\CommentController;
use Illuminate\Support\Facades\Route;

Route::middleware('recaptcha')->group(
    function () {
        Route::get('/comments', [CommentController::class, 'index'])
            ->withoutMiddleware('recaptcha')->name('api.comments.index');
        Route::post('/comments', [CommentController::class, 'store'])->name('api.comments.store');
        Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('api.comments.update');
        Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('api.comments.destroy');
    }
);
