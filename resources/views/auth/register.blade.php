@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
    <div class="card">
        <h2>Регистрация</h2>
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-group">
                <label for="name">Имя</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <p class="text-error">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                @error('email')
                    <p class="text-error">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" name="password" id="password" required>
                @error('password')
                    <p class="text-error">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Подтверждение пароля</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Зарегистрироваться</button>
            </div>
            
            <div class="form-group">
                <p>Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></p>
            </div>
        </form>
    </div>
@endsection 