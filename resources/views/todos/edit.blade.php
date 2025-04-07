@extends('layouts.app')

@section('title', 'Редактировать задачу')

@section('content')
    <div class="card">
        <h2>Редактировать задачу</h2>
        
        <form action="{{ route('todos.update', $todo->id) }}" method="POST">
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
                <label for="board_id">Доска</label>
                <select name="board_id" id="board_id" class="form-select">
                    <option value="">Без доски</option>
                    @foreach($boards as $board)
                        <option value="{{ $board->id }}" {{ old('board_id', $todo->board_id) == $board->id ? 'selected' : '' }}>
                            {{ $board->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label class="checkbox-container">
                    <input type="checkbox" name="completed" {{ $todo->completed ? 'checked' : '' }}>
                    <span class="checkmark"></span>
                    Завершена
                </label>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn">Сохранить изменения</button>
                <a href="{{ route('boards.index') }}" class="btn" style="background-color: #888;">Отмена</a>
            </div>
        </form>
    </div>
@endsection

@section('styles')
<style>
    .form-select {
        width: 100%;
        padding: 8px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        font-size: 1rem;
        font-family: inherit;
        background-color: var(--card-color);
        color: var(--text-color);
    }
</style>
@endsection 