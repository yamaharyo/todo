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
                <p>Создано: {{ $todo->created_at->format('d.m.Y H:i') }}</p>
                <p>Обновлено: {{ $todo->updated_at->format('d.m.Y H:i') }}</p>
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
    </div>
@endsection 