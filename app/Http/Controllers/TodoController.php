<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\TelegramService;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->todos()->with('board');
        
        // Применяем фильтры
        $filter = $request->query('filter');
        if ($filter === 'completed') {
            $query->where('completed', true);
        } elseif ($filter === 'incomplete') {
            $query->where('completed', false);
        }
        
        // Получаем задачи
        $todos = $query->latest()->get();
        
        return view('todos.index', compact('todos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $boards = Auth::user()->boards()->orderBy('position')->get();
        $selectedBoardId = $request->input('board_id');
        
        // Проверяем, существует ли доска и принадлежит ли она пользователю
        if ($selectedBoardId) {
            $board = Board::find($selectedBoardId);
            if (!$board || $board->user_id !== Auth::id()) {
                $selectedBoardId = null;
            }
        }
        
        return view('todos.create', compact('boards', 'selectedBoardId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'board_id' => 'nullable|exists:boards,id',
        ]);

        // Определяем позицию для новой задачи
        $position = 0;
        if ($request->board_id) {
            // Если задача добавляется на доску, находим максимальную позицию
            $maxPosition = Todo::where('board_id', $request->board_id)->max('position');
            $position = $maxPosition !== null ? $maxPosition + 1 : 0;
        } else {
            // Если задача без доски, просто используем следующую доступную позицию
            $maxPosition = Auth::user()->todos()->whereNull('board_id')->max('position');
            $position = $maxPosition !== null ? $maxPosition + 1 : 0;
        }

        Auth::user()->todos()->create([
            'title' => $request->title,
            'description' => $request->description,
            'board_id' => $request->board_id,
            'position' => $position,
        ]);

        return redirect()->route('boards.index')
            ->with('success', 'Задача успешно создана');
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        Gate::authorize('view', $todo);
        return view('todos.show', compact('todo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        Gate::authorize('update', $todo);
        $boards = Auth::user()->boards()->orderBy('position')->get();
        return view('todos.edit', compact('todo', 'boards'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        Gate::authorize('update', $todo);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'board_id' => 'nullable|exists:boards,id',
        ]);

        $todo->update([
            'title' => $request->title,
            'description' => $request->description,
            'completed' => $request->has('completed'),
            'board_id' => $request->board_id,
        ]);

        return redirect()->route('boards.index')
            ->with('success', 'Задача успешно обновлена');
    }

    /**
     * Toggle completion status.
     */
    public function toggleComplete(Todo $todo)
    {
        Gate::authorize('update', $todo);
        
        $todo->update([
            'completed' => !$todo->completed
        ]);

        return redirect()->back()
            ->with('success', 'Статус задачи изменен');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        Gate::authorize('delete', $todo);
        
        $todo->delete();

        return redirect()->back()
            ->with('success', 'Задача успешно удалена');
    }
    
    /**
     * Move a todo to a different board
     */
    public function moveToBoard(Todo $todo, Request $request)
    {
        // Проверка прав доступа
        Gate::authorize('update', $todo);
        
        // Валидация данных
        $validatedData = $request->validate([
            'board_id' => 'required|exists:boards,id'
        ]);
        
        // Проверка, принадлежит ли доска текущему пользователю
        $board = Board::findOrFail($validatedData['board_id']);
        Gate::authorize('view', $board);
        
        // Определяем новую позицию для задачи
        $maxPosition = Todo::where('board_id', $validatedData['board_id'])->max('position');
        $position = $maxPosition !== null ? $maxPosition + 1 : 0;
        
        // Обновление задачи
        $todo->board_id = $validatedData['board_id'];
        $todo->position = $position;
        $todo->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Задача успешно перемещена на новую доску'
        ]);
    }

    public function setReminder(Request $request, Todo $todo)
    {
        \Illuminate\Support\Facades\Log::info('Setting reminder', [
            'task_id' => $todo->id,
            'reminder_at' => $request->reminder_at,
            'request_data' => $request->all()
        ]);

        try {
            $request->validate([
                'reminder_at' => 'required|date_format:Y-m-d H:i:s|after:now'
            ]);

            $reminderAt = Carbon::parse($request->reminder_at);
            
            \Illuminate\Support\Facades\Log::info('Parsed reminder time', [
                'task_id' => $todo->id,
                'original' => $request->reminder_at,
                'parsed' => $reminderAt->toDateTimeString()
            ]);
            
            if ($reminderAt->isPast()) {
                \Illuminate\Support\Facades\Log::warning('Reminder time is in the past', [
                    'task_id' => $todo->id,
                    'reminder_at' => $reminderAt->toDateTimeString()
                ]);

                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Время напоминания не может быть в прошлом'
                    ], 422);
                }
                return back()->with('error', 'Время напоминания не может быть в прошлом');
            }

            $todo->update([
                'reminder_at' => $reminderAt
            ]);

            \Illuminate\Support\Facades\Log::info('Reminder set successfully', [
                'task_id' => $todo->id,
                'reminder_at' => $reminderAt->toDateTimeString()
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Напоминание установлено на ' . $reminderAt->format('d.m.Y H:i')
                ]);
            }

            return redirect()->route('todos.index')
                ->with('success', 'Напоминание установлено на ' . $reminderAt->format('d.m.Y H:i'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error setting reminder', [
                'task_id' => $todo->id,
                'reminder_at' => $request->reminder_at,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Ошибка при установке напоминания: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Ошибка при установке напоминания: ' . $e->getMessage());
        }
    }

    public function toggle(Todo $todo)
    {
        // Проверка прав доступа
        Gate::authorize('update', $todo);
        
        // Инвертируем статус задачи
        $todo->update([
            'completed' => !$todo->completed
        ]);
        
        return redirect()->back()->with('success', 'Статус задачи обновлен');
    }

    public function sendReminder(Todo $todo)
    {
        Gate::authorize('update', $todo);
        
        Log::info('Attempting to send reminder', [
            'task_id' => $todo->id,
            'reminder_at' => $todo->reminder_at
        ]);
        
        if (!$todo->reminder_at) {
            Log::warning('No reminder set for task', ['task_id' => $todo->id]);
            return response()->json(['error' => 'No reminder set for this task'], 400);
        }
        
        try {
            $telegram = new TelegramService();
            $message = "🔔 Напоминание!\n\n";
            $message .= "Задача: {$todo->title}\n";
            $message .= "Время: " . $todo->reminder_at->format('d.m.Y H:i');
            
            $result = $telegram->sendMessage($message);
            Log::info('Telegram message sent', [
                'task_id' => $todo->id,
                'result' => $result
            ]);
            
            // Очищаем время напоминания после отправки
            $todo->update(['reminder_at' => null]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Failed to send reminder', [
                'task_id' => $todo->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Failed to send reminder: ' . $e->getMessage()], 500);
        }
    }
}
