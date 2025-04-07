@extends('layouts.app')

@section('title', 'Редактировать доску')

@section('content')
    <div class="card">
        <h2>Редактировать доску</h2>
        
        <form action="{{ route('boards.update', $board) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name">Название доски</label>
                <input type="text" name="name" id="name" value="{{ old('name', $board->name) }}" required>
            </div>
            
            <div class="form-group">
                <label for="color">Цвет доски</label>
                <div class="color-picker-container">
                    <input type="color" name="color" id="color" value="{{ old('color', $board->color) }}">
                    <span class="color-preview"></span>
                </div>
                <p class="form-hint">Выберите цвет для заголовка доски</p>
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
    .color-picker-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    input[type="color"] {
        width: 50px;
        height: 40px;
        border: none;
        border-radius: 4px;
        padding: 0;
        cursor: pointer;
    }
    
    .color-preview {
        width: 40px;
        height: 40px;
        border-radius: 4px;
        border: 1px solid var(--border-color);
        background-color: var(--primary-color);
    }
    
    .form-hint {
        font-size: 0.9rem;
        color: var(--text-color);
        opacity: 0.7;
        margin-top: 5px;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorInput = document.getElementById('color');
        const colorPreview = document.querySelector('.color-preview');
        
        // Установить начальный цвет
        colorPreview.style.backgroundColor = colorInput.value;
        
        // Обновить цвет при изменении
        colorInput.addEventListener('input', function() {
            colorPreview.style.backgroundColor = colorInput.value;
        });
    });
</script>
@endsection 