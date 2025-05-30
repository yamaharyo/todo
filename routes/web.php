<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StatisticsController;
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

// Маршруты для досок и задач
Route::middleware(['auth'])->group(function () {
    // Маршруты для досок
    Route::resource('boards', BoardController::class);
    Route::post('/boards/order', [OrderController::class, 'updateBoardOrder'])->name('boards.update-order');
    Route::post('/boards/reorder', [BoardController::class, 'reorder'])->name('boards.reorder');
    Route::post('/update-todo-board', [BoardController::class, 'updateTodoBoard'])->name('update.todo.board');
    Route::post('/update-boards-order', [BoardController::class, 'updateBoardsOrder'])->name('update.boards.order');

    // Маршруты для задач
    Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
    Route::get('/todos/create', [TodoController::class, 'create'])->name('todos.create');
    Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
    Route::get('/todos/{todo}/edit', [TodoController::class, 'edit'])->name('todos.edit');
    Route::put('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');
    Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');
    Route::patch('/todos/{todo}/toggle', [TodoController::class, 'toggle'])->name('todos.toggle');
    Route::post('/todos/{todo}/reminder', [TodoController::class, 'setReminder'])->name('todos.reminder');
    Route::post('/todos/{todo}/send-reminder', [TodoController::class, 'sendReminder'])->name('todos.send-reminder');
    Route::post('/todos/{todo}/move', [TodoController::class, 'moveToBoard'])->name('todos.move');
    Route::post('/todos/order', [OrderController::class, 'updateTodoOrder'])->name('todos.update-order');

    // Маршруты для статистики
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
});
