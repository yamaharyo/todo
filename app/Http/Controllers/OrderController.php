<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    /**
     * Обновление порядка досок.
     */
    public function updateBoardOrder(Request $request)
    {
        $request->validate([
            'boards' => 'required|array',
            'boards.*.id' => 'required|exists:boards,id',
            'boards.*.position' => 'required|integer|min:0',
        ]);
        
        $boardsData = $request->boards;
        
        foreach ($boardsData as $boardData) {
            $board = Board::find($boardData['id']);
            
            // Проверка прав доступа
            Gate::authorize('update', $board);
            
            // Обновление позиции
            $board->position = $boardData['position'];
            $board->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Порядок досок успешно обновлен'
        ]);
    }
    
    /**
     * Обновление порядка задач в пределах одной доски.
     */
    public function updateTodoOrder(Request $request)
    {
        $request->validate([
            'todos' => 'required|array',
            'todos.*.id' => 'required|exists:todos,id',
            'todos.*.position' => 'required|integer|min:0',
        ]);
        
        $todosData = $request->todos;
        
        foreach ($todosData as $todoData) {
            $todo = Todo::find($todoData['id']);
            
            // Проверка прав доступа
            Gate::authorize('update', $todo);
            
            // Обновление позиции
            $todo->position = $todoData['position'];
            $todo->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Порядок задач успешно обновлен'
        ]);
    }
}
