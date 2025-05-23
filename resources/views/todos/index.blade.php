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
                        <div class="flex items-center space-x-2">
                            <form action="{{ route('todos.toggle', $todo) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="toggle-button" title="{{ $todo->completed ? 'Отметить как невыполненную' : 'Отметить как выполненную' }}">
                                    <i class="fas {{ $todo->completed ? 'fa-check-circle text-green-500' : 'fa-circle text-gray-400' }}"></i>
                                </button>
                            </form>

                            <button onclick="openReminderModal({{ $todo->id }})" class="reminder-button" title="Установить напоминание">
                                <i class="fas fa-check text-gray-400 hover:text-yellow-500"></i>
                            </button>

                            <a href="{{ route('todos.edit', $todo) }}" class="edit-button" title="Редактировать">
                                <i class="fas fa-edit text-gray-400 hover:text-blue-500"></i>
                            </a>

                            <form action="{{ route('todos.destroy', $todo) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-button" title="Удалить" onclick="return confirm('Вы уверены?')">
                                    <i class="fas fa-trash text-gray-400 hover:text-red-500"></i>
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
                    @method('PATCH')
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
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeReminderModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Отмена
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Установить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function openReminderModal(todoId) {
        const modal = document.getElementById('reminderModal');
        const form = document.getElementById('reminderForm');
        form.action = `/todos/${todoId}/reminder`;
        
        // Устанавливаем минимальную дату как сегодня
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('reminder_date').min = today;
        
        modal.classList.remove('hidden');
    }

    function closeReminderModal() {
        const modal = document.getElementById('reminderModal');
        modal.classList.add('hidden');
    }

    // Обработка отправки формы
    document.getElementById('reminderForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const date = document.getElementById('reminder_date').value;
        const time = document.getElementById('reminder_time').value;
        
        if (!date || !time) {
            alert('Пожалуйста, выберите дату и время');
            return;
        }
        
        const reminderAt = `${date}T${time}`;
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'reminder_at';
        input.value = reminderAt;
        this.appendChild(input);
        
        this.submit();
    });
    </script>
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