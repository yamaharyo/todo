@extends('layouts.app')

@section('title', $board->name)

@section('content')
    <div class="board-header">
        <h1>{{ $board->name }}</h1>
        <div class="board-actions">
            <a href="{{ route('boards.edit', $board->id) }}" class="btn btn-sm">Редактировать</a>
            <form action="{{ route('boards.destroy', $board->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm" style="background-color: #dc3545;">Удалить</button>
            </form>
            <a href="{{ route('boards.index') }}" class="btn btn-sm">Назад к доскам</a>
        </div>
    </div>

    <div class="board-container" style="border-left: 5px solid {{ $board->color }}">
        <div class="board-tasks">
            @if($board->todos->count() > 0)
                @foreach($board->todos as $todo)
                    <div class="todo-item {{ $todo->completed ? 'completed' : '' }}" id="todo-{{ $todo->id }}">
                        <div class="todo-actions">
                            <form action="{{ route('todos.toggle-complete', $todo->id) }}" method="POST" class="toggle-form">
                                @csrf
                                <button type="submit" class="toggle-button">
                                    <span class="checkmark {{ $todo->completed ? 'checked' : '' }}"></span>
                                </button>
                            </form>
                        </div>
                        
                        <div class="todo-content">
                            <h3>{{ $todo->title }}</h3>
                            @if($todo->description)
                                <p>{{ $todo->description }}</p>
                            @endif
                        </div>
                        
                        <div class="todo-actions right">
                            <a href="{{ route('todos.edit', $todo->id) }}" class="edit-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                </svg>
                            </a>
                            
                            <form action="{{ route('todos.destroy', $todo->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 6h18"></path>
                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="no-tasks">На этой доске нет задач</p>
            @endif

            <div class="add-task-section">
                <a href="{{ route('todos.create') }}" class="btn btn-add">+ Добавить задачу</a>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    .board-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .board-actions {
        display: flex;
        gap: 10px;
    }
    
    .board-container {
        background-color: var(--card-color);
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .board-tasks {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .btn-sm {
        padding: 5px 10px;
        font-size: 0.9rem;
    }
    
    .d-inline {
        display: inline;
    }
    
    .no-tasks {
        text-align: center;
        color: var(--text-muted);
        padding: 20px;
    }
    
    .add-task-section {
        margin-top: 15px;
    }
    
    .btn-add {
        width: 100%;
        text-align: center;
        background-color: transparent;
        border: 2px dashed var(--border-color);
        color: var(--text-muted);
    }
    
    .btn-add:hover {
        background-color: var(--hover-color);
        color: var(--text-color);
    }
    
    .toggle-button {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s;
    }
    
    .toggle-button:hover {
        transform: scale(1.1);
    }
    
    .edit-button, .delete-button {
        background: none;
        border: none;
        padding: 5px;
        margin: 0 2px;
        cursor: pointer;
        color: var(--text-muted);
        transition: color 0.2s, transform 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .edit-button:hover, .delete-button:hover {
        color: var(--text-color);
        transform: scale(1.1);
    }
</style>
@endsection 