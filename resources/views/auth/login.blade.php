@extends('layouts.app')

@section('title', 'Вход в систему')

@section('content')
    <div class="card">
        <h2>Вход в систему</h2>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
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
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Запомнить меня
                </label>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Войти</button>
            </div>
            
            <div class="form-group">
                <p>Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a></p>
            </div>
        </form>
    </div>
@endsection 