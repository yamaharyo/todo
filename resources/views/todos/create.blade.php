@extends('layouts.app')

@section('title', 'Создать задачу')

@section('content')
    <div class="card">
        <h2>Создать новую задачу</h2>
        
        <form action="{{ route('todos.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="title">Название задачи</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required>
            </div>
            
            <div class="form-group">
                <label for="description">Описание (необязательно)</label>
                <textarea name="description" id="description">{{ old('description') }}</textarea>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn">Создать задачу</button>
                <a href="{{ route('todos.index') }}" class="btn" style="background-color: #888;">Отмена</a>
            </div>
        </form>
    </div>
@endsection 