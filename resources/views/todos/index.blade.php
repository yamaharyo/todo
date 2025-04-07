@extends('layouts.app')

@section('title', 'Все задачи')

@section('content')
    <div class="page-header">
        <h1>Все мои задачи</h1>
        <div>
            <a href="{{ route('todos.create') }}" class="btn">Создать задачу</a>
            <a href="{{ route('boards.index') }}" class="btn">Доски</a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="tasks-container">
        @if($todos->count() > 0)
            <div class="filter-container">
                <a href="{{ route('todos.index') }}" class="filter-link {{ request()->query('filter') ? '' : 'active' }}">Все</a>
                <a href="{{ route('todos.index', ['filter' => 'incomplete']) }}" class="filter-link {{ request()->query('filter') === 'incomplete' ? 'active' : '' }}">Незавершенные</a>
                <a href="{{ route('todos.index', ['filter' => 'completed']) }}" class="filter-link {{ request()->query('filter') === 'completed' ? 'active' : '' }}">Завершенные</a>
            </div>
            
            @foreach($todos as $todo)
                <div class="todo-item {{ $todo->completed ? 'completed' : '' }}">
                    <div class="todo-actions">
                        <form action="{{ route('todos.toggle-complete', $todo->id) }}" method="POST" class="toggle-form">
                            @csrf
                            @method('PATCH')
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
                        
                        @if($todo->board)
                            <div class="todo-board" style="border-color: {{ $todo->board->color }}">
                                {{ $todo->board->name }}
                            </div>
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
            <div class="empty-state">
                <div class="empty-state-content">
                    <div class="empty-state-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <h2>У вас нет задач</h2>
                    <p>Создайте свою первую задачу, чтобы начать работу</p>
                    <a href="{{ route('todos.create') }}" class="btn btn-lg">Создать задачу</a>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('styles')
<style>
    .tasks-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .filter-container {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .filter-link {
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        color: var(--text-color);
        background-color: var(--card-color);
        border: 1px solid var(--border-color);
    }
    
    .filter-link.active {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    
    .todo-board {
        display: inline-block;
        margin-top: 8px;
        font-size: 0.8rem;
        padding: 2px 8px;
        border-radius: 4px;
        border-left: 3px solid;
        background-color: var(--hover-color);
    }
</style>
@endsection 