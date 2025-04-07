<!DOCTYPE html>
<html lang="ru" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список задач - @yield('title', 'TODO App')</title>
    <style>
        :root {
            /* Темная тема */
            --dark-bg-color: #121212;
            --dark-card-color: #1e1e1e;
            --dark-text-color: #f1f1f1;
            --dark-border-color: #333;
            --dark-primary-color: #7b68ee;
            --dark-secondary-color: #9370db;
            --dark-hover-color: #8a2be2;
            
            /* Светлая тема */
            --light-bg-color: #f5f5f5;
            --light-card-color: #ffffff;
            --light-text-color: #333333;
            --light-border-color: #e0e0e0;
            --light-primary-color: #8a6bbf;
            --light-secondary-color: #d8c8b8;
            --light-hover-color: #9d7cca;
            
            /* Общие для обеих тем */
            --error-color: #e74c3c;
            --success-color: #2ecc71;
        }
        
        [data-theme="dark"] {
            --bg-color: var(--dark-bg-color);
            --card-color: var(--dark-card-color);
            --text-color: var(--dark-text-color);
            --border-color: var(--dark-border-color);
            --primary-color: var(--dark-primary-color);
            --secondary-color: var(--dark-secondary-color);
            --hover-color: var(--dark-hover-color);
        }
        
        [data-theme="light"] {
            --bg-color: var(--light-bg-color);
            --card-color: var(--light-card-color);
            --text-color: var(--light-text-color);
            --border-color: var(--light-border-color);
            --primary-color: var(--light-primary-color);
            --secondary-color: var(--light-secondary-color);
            --hover-color: var(--light-hover-color);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: background-color 0.3s, color 0.3s;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--bg-color);
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        header {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem;
            margin-bottom: 2rem;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: nowrap;
        }
        
        header h1 {
            font-size: 2rem;
            white-space: nowrap;
        }
        
        .user-nav {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: nowrap;
        }
        
        .user-nav form {
            margin: 0;
            display: flex;
            align-items: center;
        }
        
        .user-info {
            margin-right: 15px;
            white-space: nowrap;
        }
        
        .text-error {
            color: var(--error-color);
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        .alert {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .alert-error {
            background-color: var(--error-color);
            color: white;
        }
        
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: var(--hover-color);
        }
        
        .btn-small {
            padding: 4px 8px;
            font-size: 0.9rem;
            white-space: nowrap;
        }
        
        .btn-danger {
            background-color: var(--error-color);
        }
        
        .btn-success {
            background-color: var(--success-color);
        }
        
        form {
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        
        input, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 1rem;
            font-family: inherit;
            background-color: var(--card-color);
            color: var(--text-color);
        }
        
        textarea {
            height: 150px;
            resize: vertical;
        }
        
        .todo-list {
            list-style-type: none;
        }
        
        .todo-item {
            background-color: var(--card-color);
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .todo-item.completed {
            opacity: 0.7;
        }
        
        .todo-item.completed h3 {
            text-decoration: line-through;
        }
        
        .todo-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .todo-actions form {
            margin-bottom: 0;
        }
        
        .todo-actions a,
        .todo-actions button {
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 10px;
            line-height: 1;
            font-size: 0.9rem;
        }
        
        .todo-title {
            margin-bottom: 5px;
        }
        
        .todo-description {
            color: var(--text-color);
            opacity: 0.8;
        }
        
        .card {
            background-color: var(--card-color);
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .checkmark {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: inline-block;
            border: 2px solid var(--primary-color);
            position: relative;
            cursor: pointer;
        }
        
        .completed .checkmark:after {
            content: "";
            position: absolute;
            width: 12px;
            height: 12px;
            background: var(--primary-color);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        
        /* Переключатель темы */
        .theme-toggle {
            background: transparent;
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            padding: 5px 10px;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-right: 10px;
            white-space: nowrap;
        }
        
        .theme-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .theme-toggle svg {
            margin-right: 5px;
            width: 16px;
            height: 16px;
        }
        
        /* Адаптивность для мобильных устройств */
        @media (max-width: 768px) {
            .todo-item {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .todo-actions {
                margin-top: 15px;
                width: 100%;
                justify-content: flex-end;
            }
            
            .btn {
                padding: 6px 12px;
                font-size: 0.9rem;
            }
            
            header h1 {
                font-size: 1.5rem;
            }
            
            .header-content {
                font-size: 0.9rem;
            }
            
            .user-nav {
                gap: 8px;
            }
            
            .theme-toggle {
                padding: 4px 8px;
                font-size: 0.8rem;
            }
            
            .theme-toggle svg {
                width: 14px;
                height: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-content">
                <h1>Список задач</h1>
                <div class="user-nav">
                    <button id="theme-toggle" class="theme-toggle">
                        <svg id="theme-icon-dark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                        <svg id="theme-icon-light" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                        <span id="theme-text">Светлая тема</span>
                    </button>
                    @auth
                        <span class="user-info">{{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-small">Выйти</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-small">Войти</a>
                        <a href="{{ route('register') }}" class="btn btn-small">Регистрация</a>
                    @endauth
                </div>
            </div>
        </header>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <main>
            @yield('content')
        </main>
    </div>
    
    <script>
        // Автоматически скрывать сообщения об успехе через 3 секунды
        document.addEventListener('DOMContentLoaded', function() {
            let alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.5s';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 500);
                }, 3000);
            });
            
            // Обработка переключения темы
            const themeToggle = document.getElementById('theme-toggle');
            const themeText = document.getElementById('theme-text');
            const themeIconDark = document.getElementById('theme-icon-dark');
            const themeIconLight = document.getElementById('theme-icon-light');
            const html = document.documentElement;
            
            // Загрузить сохраненную тему из localStorage или использовать темную по умолчанию
            const savedTheme = localStorage.getItem('theme') || 'dark';
            applyTheme(savedTheme);
            
            themeToggle.addEventListener('click', function() {
                const currentTheme = html.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                applyTheme(newTheme);
                localStorage.setItem('theme', newTheme);
            });
            
            function applyTheme(theme) {
                html.setAttribute('data-theme', theme);
                
                if (theme === 'dark') {
                    themeText.textContent = 'Светлая тема';
                    themeIconDark.style.display = 'none';
                    themeIconLight.style.display = 'inline-block';
                } else {
                    themeText.textContent = 'Тёмная тема';
                    themeIconDark.style.display = 'inline-block';
                    themeIconLight.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html> 