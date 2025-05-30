@extends('layouts.app')

@section('title', 'Все задачи')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="page-header">
        <h1>Все мои задачи</h1>
        <div class="header-buttons">
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
                        <div class="action-buttons">
                            <button onclick="openReminderModal({{ $todo->id }})" class="action-button reminder {{ $todo->reminder_at ? 'active' : '' }}" title="Настроить уведомление о задаче">
                                <i class="fas fa-check"></i>
                                <span>Выбрать</span>
                            </button>

                            <form action="{{ route('todos.toggle', $todo) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-button toggle" title="{{ $todo->completed ? 'Отметить задачу как невыполненную' : 'Отметить задачу как выполненную' }}">
                                    <i class="fas {{ $todo->completed ? 'fa-check-circle' : 'fa-circle' }}"></i>
                                    <span>{{ $todo->completed ? 'Выполнено' : 'Не выполнено' }}</span>
                                </button>
                            </form>

                            <form action="{{ route('todos.edit', $todo) }}" method="GET" class="inline">
                                <button type="submit" class="action-button edit" title="Изменить название и описание задачи">
                                    <i class="fas fa-edit"></i>
                                    <span>Изменить</span>
                                </button>
                            </form>

                            <form action="{{ route('todos.destroy', $todo) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-button delete" title="Удалить задачу навсегда" onclick="return confirm('Вы уверены, что хотите удалить эту задачу?')">
                                    <i class="fas fa-trash"></i>
                                    <span>Удалить</span>
                                </button>
                            </form>
                        </div>
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

                    @if($todo->reminder_at)
                        @php
                            $reminderTime = $todo->reminder_at->format('Y-m-d H:i:s');
                            \Illuminate\Support\Facades\Log::info('Reminder time in template', [
                                'task_id' => $todo->id,
                                'reminder_at' => $todo->reminder_at,
                                'formatted' => $reminderTime,
                                'raw' => $todo->reminder_at->toDateTimeString()
                            ]);
                        @endphp
                        <div data-reminder="{{ $reminderTime }}" 
                             data-task-id="{{ $todo->id }}" 
                             style="display: none;">
                        </div>
                    @endif
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

    <!-- Модальное окно для установки напоминания -->
    <div id="reminderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Установить напоминание</h3>
                <form id="reminderForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="reminder_date" class="block text-sm font-medium text-gray-700">Дата напоминания</label>
                        <input type="date" name="reminder_date" id="reminder_date" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label for="reminder_time" class="block text-sm font-medium text-gray-700">Время напоминания</label>
                        <input type="time" name="reminder_time" id="reminder_time" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="modal-buttons">
                        <button type="button" onclick="closeReminderModal()" 
                                class="action-button cancel">
                            Отмена
                        </button>
                        <button type="submit" 
                                class="action-button submit">
                            Установить
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
    
    .action-buttons {
        display: flex;
        gap: 12px;
        align-items: center;
    }
    
    .action-button {
        background: none;
        border: none;
        padding: 12px 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        border-radius: 8px;
        transition: all 0.2s ease;
        color: var(--text-muted);
        font-size: 1.1rem;
        min-width: auto;
        min-height: 48px;
    }
    
    .action-button i {
        font-size: 1.2rem;
    }
    
    .action-button span {
        font-size: 1rem;
        white-space: nowrap;
    }
    
    .action-button:hover {
        transform: scale(1.1);
        background-color: var(--hover-color);
    }
    
    .action-button.reminder {
        position: relative;
    }
    
    .action-button.reminder i {
        color: var(--text-muted);
    }
    
    .action-button.reminder:hover i {
        color: var(--success-color, #10B981);
    }
    
    .action-button.reminder:hover span {
        color: var(--success-color, #10B981);
    }
    
    .action-button.reminder.active {
        background-color: var(--success-color, #10B981);
        color: white;
        box-shadow: 0 0 0 2px var(--success-color, #10B981),
                    0 0 10px var(--success-color, #10B981);
    }
    
    .action-button.reminder.active i,
    .action-button.reminder.active span {
        color: white;
    }
    
    .action-button.toggle i {
        color: var(--text-muted);
    }
    
    .action-button.toggle:hover i {
        color: var(--success-color, #10B981);
    }
    
    .todo-item.completed .action-button.toggle i {
        color: var(--success-color, #10B981);
    }
    
    .todo-item.completed .action-button.toggle span {
        color: var(--success-color, #10B981);
    }
    
    .action-button.edit i {
        color: var(--text-muted);
    }
    
    .action-button.edit:hover i {
        color: var(--info-color, #3B82F6);
    }
    
    .action-button.delete i {
        color: var(--text-muted);
    }
    
    .action-button.delete:hover i {
        color: var(--danger-color, #EF4444);
    }
    
    .modal-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 20px;
    }
    
    .action-button.cancel {
        background-color: var(--gray-300, #D1D5DB);
        color: var(--gray-700, #374151);
        padding: 8px 16px;
        font-size: 1rem;
    }
    
    .action-button.submit {
        background-color: var(--primary-color, #4F46E5);
        color: white;
        padding: 8px 16px;
        font-size: 1rem;
    }
    
    .page-header {
        margin-bottom: 24px;
    }
    
    .header-buttons {
        display: flex;
        gap: 12px;
        margin-top: 16px;
    }
    
    .btn {
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        background-color: var(--primary-color, #4F46E5);
        color: white;
        transition: background-color 0.2s ease;
        font-size: 1rem;
    }
    
    .btn:hover {
        background-color: var(--primary-dark, #4338CA);
    }
</style>
@endsection

@push('scripts')
@vite(['resources/js/reminders.js'])
@endpush 