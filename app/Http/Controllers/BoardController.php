<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $boards = Auth::user()->boards()->with(['todos' => function($query) {
            $query->orderBy('position');
        }])->orderBy('position')->get();
        
        return view('boards.index', compact('boards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('boards.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);

        $position = Auth::user()->boards()->max('position') + 1;

        Auth::user()->boards()->create([
            'name' => $request->name,
            'color' => $request->color,
            'position' => $position,
        ]);

        return redirect()->route('boards.index')
            ->with('success', 'Доска успешно создана');
    }

    /**
     * Display the specified resource.
     */
    public function show(Board $board)
    {
        $this->authorize('view', $board);
        
        $board->load('todos');
        
        return view('boards.show', compact('board'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Board $board)
    {
        Gate::authorize('update', $board);
        return view('boards.edit', compact('board'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $board)
    {
        Gate::authorize('update', $board);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);

        $board->update([
            'name' => $request->name,
            'color' => $request->color,
        ]);

        return redirect()->route('boards.index')
            ->with('success', 'Доска успешно обновлена');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board)
    {
        Gate::authorize('delete', $board);
        
        $board->delete();

        return redirect()->route('boards.index')
            ->with('success', 'Доска успешно удалена');
    }
    
    /**
     * Обновление порядка и принадлежности задачи
     */
    public function updateTodoBoard(Request $request)
    {
        $request->validate([
            'todo_id' => 'required|exists:todos,id',
            'board_id' => 'required|exists:boards,id',
        ]);
        
        $todo = Auth::user()->todos()->findOrFail($request->todo_id);
        $board = Auth::user()->boards()->findOrFail($request->board_id);
        
        $todo->update([
            'board_id' => $board->id,
        ]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Обновление порядка досок
     */
    public function updateBoardsOrder(Request $request)
    {
        $request->validate([
            'boards' => 'required|array',
            'boards.*.id' => 'required|exists:boards,id',
            'boards.*.position' => 'required|integer|min:0',
        ]);
        
        foreach ($request->boards as $boardData) {
            $board = Auth::user()->boards()->findOrFail($boardData['id']);
            $board->update(['position' => $boardData['position']]);
        }
        
        return response()->json(['success' => true]);
    }
}
