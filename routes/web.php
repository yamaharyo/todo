<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\OrderController;
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

// Перенаправление на страницу досок при входе на главную
Route::get('/', function () {
    return redirect()->route('boards.index');
});

// Маршруты для досок
Route::middleware('auth')->group(function () {
    Route::resource('boards', BoardController::class);
    
    // AJAX маршруты для перетаскивания
    Route::post('/update-todo-board', [BoardController::class, 'updateTodoBoard'])->name('update.todo.board');
    Route::post('/update-boards-order', [BoardController::class, 'updateBoardsOrder'])->name('update.boards.order');
});

// Маршруты для задач
Route::middleware('auth')->group(function () {
    Route::resource('todos', TodoController::class);
    Route::patch('todos/{todo}/toggle', [TodoController::class, 'toggleComplete'])->name('todos.toggle-complete');
    Route::post('/todos/{todo}/move-to-board', [TodoController::class, 'moveToBoard'])->name('todos.move-to-board');
    
    // Маршруты для обновления порядка элементов
    Route::post('/boards/order', [OrderController::class, 'updateBoardOrder'])->name('boards.update-order');
    Route::post('/todos/order', [OrderController::class, 'updateTodoOrder'])->name('todos.update-order');
});
