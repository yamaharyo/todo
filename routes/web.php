<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

// Маршруты аутентификации
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Перенаправление на страницу задач при входе на главную
Route::get('/', function () {
    return redirect()->route('todos.index');
});

// Маршруты для задач
Route::middleware('auth')->group(function () {
    Route::resource('todos', TodoController::class);
    Route::patch('todos/{todo}/toggle', [TodoController::class, 'toggleComplete'])->name('todos.toggle');
});
