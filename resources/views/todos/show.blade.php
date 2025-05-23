@extends('layouts.app')

@section('title', $todo->title)

@section('content')
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Просмотр задачи</h2>
            <a href="{{ route('todos.index') }}" class="btn">Назад к списку</a>
        </div>
        
        <div class="todo-item {{ $todo->completed ? 'completed' : '' }}">
            <div>
                <h3 class="todo-title">{{ $todo->title }}</h3>
                <p class="todo-description">{{ $todo->description ?: 'Нет описания' }}</p>
                <p>Статус: <strong>{{ $todo->completed ? 'Выполнено' : 'Не выполнено' }}</strong></p>
                <p>Создано: {{ $todo->created_at->format('d.m.Y') }} в {{ $todo->created_at->format('H:i') }}</p>
                <p>Обновлено: {{ $todo->updated_at->format('d.m.Y') }} в {{ $todo->updated_at->format('H:i') }}</p>
                @if($todo->reminder_at)
                    <p>Напоминание: {{ $todo->reminder_at->format('d.m.Y H:i') }}</p>
                @endif
            </div>
        </div>
        
        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <a href="{{ route('todos.edit', $todo) }}" class="btn">Редактировать</a>
            <form action="{{ route('todos.toggle', $todo) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn {{ $todo->completed ? 'btn-danger' : 'btn-success' }}">
                    {{ $todo->completed ? 'Отметить как невыполненную' : 'Отметить как выполненную' }}
                </button>
            </form>
            <form action="{{ route('todos.destroy', $todo) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
            </form>
        </div>

        <div style="margin-top: 20px;">
            <h3>Установить напоминание</h3>
            <form action="{{ route('todos.reminder', $todo) }}" method="POST">
                @csrf
                @method('PATCH')
                <div style="display: flex; gap: 10px; align-items: center;">
                    <input type="date" name="reminder_date" required min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                    <input type="time" name="reminder_time" required value="{{ date('H:i', strtotime('+1 minute')) }}">
                    <button type="submit" class="btn">Установить напоминание</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Обновляем время каждую минуту
        setInterval(function() {
            const now = new Date();
            const timeInput = document.querySelector('input[name="reminder_time"]');
            const dateInput = document.querySelector('input[name="reminder_date"]');
            
            // Устанавливаем текущую дату
            dateInput.value = now.toISOString().split('T')[0];
            
            // Устанавливаем время + 1 минута
            now.setMinutes(now.getMinutes() + 1);
            timeInput.value = now.toTimeString().slice(0, 5);
        }, 60000);
    </script>
@endsection 