@extends('layouts.app')

@section('title', 'Список задач')

@section('content')
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Ваши задачи</h2>
            <a href="{{ route('todos.create') }}" class="btn">Создать задачу</a>
        </div>

        @if($todos->isEmpty())
            <p>У вас пока нет задач. Нажмите на кнопку "Создать задачу", чтобы добавить первую.</p>
        @else
            <ul class="todo-list">
                @foreach($todos as $todo)
                    <li class="todo-item {{ $todo->completed ? 'completed' : '' }}">
                        <div>
                            <h3 class="todo-title">{{ $todo->title }}</h3>
                            <p class="todo-description">{{ $todo->description }}</p>
                        </div>
                        <div class="todo-actions">
                            <form action="{{ route('todos.toggle', $todo) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-small {{ $todo->completed ? 'btn-danger' : 'btn-success' }}">
                                    {{ $todo->completed ? 'Отменить' : 'Выполнить' }}
                                </button>
                            </form>
                            <a href="{{ route('todos.edit', $todo) }}" class="btn btn-small">Редактировать</a>
                            <form action="{{ route('todos.destroy', $todo) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-small btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection 