<?php

use App\Http\Controllers\Web\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CommentController::class, 'index'])->name('web.comments.index');
