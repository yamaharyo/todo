@extends('layouts.app')

@section('title', 'Редактировать задачу')

@section('content')
    <div class="card">
        <h2>Редактировать задачу</h2>
        
        <form action="{{ route('todos.update', $todo) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="title">Название задачи</label>
                <input type="text" name="title" id="title" value="{{ old('title', $todo->title) }}" required>
            </div>
            
            <div class="form-group">
                <label for="description">Описание (необязательно)</label>
                <textarea name="description" id="description">{{ old('description', $todo->description) }}</textarea>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="completed" {{ $todo->completed ? 'checked' : '' }}>
                    Задача выполнена
                </label>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn">Сохранить изменения</button>
                <a href="{{ route('todos.index') }}" class="btn" style="background-color: #888;">Отмена</a>
            </div>
        </form>
    </div>
@endsection 