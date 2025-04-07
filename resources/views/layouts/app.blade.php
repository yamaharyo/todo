<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список задач - @yield('title', 'TODO App')</title>
    <style>
        :root {
            --primary-color: #4a6da7;
            --secondary-color: #537cc8;
            --text-color: #333;
            --bg-color: #f9f9f9;
            --error-color: #e74c3c;
            --success-color: #2ecc71;
            --border-color: #ddd;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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
        }
        
        header h1 {
            font-size: 2rem;
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
            background-color: var(--secondary-color);
        }
        
        .btn-small {
            padding: 4px 8px;
            font-size: 0.9rem;
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
        }
        
        textarea {
            height: 150px;
            resize: vertical;
        }
        
        .todo-list {
            list-style-type: none;
        }
        
        .todo-item {
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .todo-item.completed {
            opacity: 0.7;
        }
        
        .todo-item.completed h3 {
            text-decoration: line-through;
        }
        
        .todo-actions {
            display: flex;
            gap: 5px;
        }
        
        .todo-title {
            margin-bottom: 5px;
        }
        
        .todo-description {
            color: #666;
        }
        
        .card {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Список задач</h1>
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
        });
    </script>
</body>
</html> 