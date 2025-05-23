@extends('layouts.app')

@section('title', 'Доски задач')

@section('content')
    <div class="page-header">
        <h1>Мои доски</h1>
        <a href="{{ route('boards.create') }}" class="btn">Создать новую доску</a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if($boards->count() > 0)
        <div class="boards-container" id="boards-container">
            @foreach($boards as $board)
                <div class="board-container" data-board-id="{{ $board->id }}" style="border-left: 5px solid {{ $board->color }}">
                    <div class="board-header">
                        <h2>{{ $board->name }}</h2>
                        <div class="board-actions">
                            <a href="{{ route('boards.edit', $board->id) }}" class="edit-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                </svg>
                            </a>
                            
                            <form action="{{ route('boards.destroy', $board->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 6h18"></path>
                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="board-tasks">
                        @if($board->todos->count() > 0)
                            @foreach($board->todos as $todo)
                                <div class="todo-item {{ $todo->completed ? 'completed' : '' }}" id="todo-{{ $todo->id }}">
                                    <div class="todo-actions">
                                        <form action="{{ route('todos.toggle', $todo->id) }}" method="POST" class="toggle-form">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="toggle-button">
                                                <span class="checkmark {{ $todo->completed ? 'checked' : '' }}"></span>
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <div class="todo-content">
                                        <h3>{{ $todo->title }}</h3>
                                        @if($todo->description)
                                            <p>{{ $todo->description }}</p>
                                        @endif
                                    </div>
                                    
                                    <div class="todo-actions right">
                                        <a href="{{ route('todos.edit', $todo->id) }}" class="edit-button">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                            </svg>
                                        </a>
                                        
                                        <form action="{{ route('todos.destroy', $todo->id) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-button">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 6h18"></path>
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="no-tasks">На этой доске нет задач</p>
                        @endif

                        <div class="add-task-section">
                            <a href="{{ route('todos.create') }}?board_id={{ $board->id }}" class="btn btn-add">+ Добавить задачу</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-content">
                <div class="empty-state-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="3" y1="9" x2="21" y2="9"></line>
                        <line x1="9" y1="21" x2="9" y2="9"></line>
                    </svg>
                </div>
                <h2>У вас еще нет досок</h2>
                <p>Создайте вашу первую доску для организации задач</p>
                <a href="{{ route('boards.create') }}" class="btn btn-lg">Создать доску</a>
            </div>
        </div>
    @endif
@endsection

@section('styles')
<style>
    .boards-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .board-container {
        background-color: var(--card-color);
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        padding: 15px;
        margin-bottom: 20px;
        width: 300px;
        min-height: 300px;
        display: flex;
        flex-direction: column;
    }
    
    .board-header {
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: var(--text-color);
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }
    
    .board-header h2 {
        margin: 0;
        color: var(--text-color);
        font-size: 1.4rem;
    }
    
    .board-actions {
        display: flex;
        gap: 5px;
    }
    
    .board-actions form {
        margin: 0;
    }
    
    .todos-container {
        flex-grow: 1;
        padding: 10px;
        overflow-y: auto;
        max-height: 500px;
    }
    
    .todo-item {
        margin-bottom: 10px;
        padding: 10px;
        background-color: var(--bg-color);
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        cursor: grab;
    }
    
    .todo-item:active {
        cursor: grabbing;
    }
    
    .todo-item h4 {
        margin: 0 0 5px 0;
        font-size: 1rem;
    }
    
    .add-todo {
        margin-top: 15px;
        text-align: center;
    }
    
    .inline {
        display: inline;
    }
    
    .drag-over {
        background-color: rgba(123, 104, 238, 0.1);
    }
    
    @media (max-width: 768px) {
        .boards-container {
            flex-direction: column;
            align-items: center;
        }
        
        .board {
            width: 100%;
        }
    }
    
    /* Стили для перетаскивания */
    .todo-item {
        cursor: grab;
    }
    
    .todo-item.dragging {
        cursor: grabbing;
    }
    
    .drag-over {
        background-color: var(--hover-color);
        border: 2px dashed var(--border-color);
    }
    
    /* Стили для уведомлений */
    .notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 4px;
        color: white;
        z-index: 1000;
        animation: fadeIn 0.3s, fadeOut 0.3s 2.7s;
    }
    
    .notification.success {
        background-color: #4caf50;
    }
    
    .notification.error {
        background-color: #f44336;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(30px); }
    }

    .empty-state {
        margin: 50px auto;
        text-align: center;
        max-width: 500px;
    }

    .empty-state-content {
        background-color: var(--card-color);
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .empty-state-icon {
        color: var(--text-muted);
        margin-bottom: 20px;
    }

    .empty-state h2 {
        margin-bottom: 10px;
        color: var(--text-color);
    }

    .empty-state p {
        color: var(--text-muted);
        margin-bottom: 25px;
    }

    .btn-lg {
        padding: 12px 24px;
        font-size: 1.1rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    /* Стили для перетаскивания досок */
    .board-container {
        transition: transform 0.2s;
    }
    
    .board-container.dragging {
        opacity: 0.7;
        transform: scale(0.98);
        z-index: 100;
    }
    
    .board-header {
        cursor: grab;
    }
    
    .board-header:active {
        cursor: grabbing;
    }

    .add-task-section {
        margin-top: 15px;
        padding: 0 10px 10px 10px;
    }
    
    .btn-add {
        width: 100%;
        text-align: center;
        background-color: transparent;
        border: 2px dashed var(--border-color);
        color: var(--text-muted);
        padding: 10px;
    }

    .todo-content {
        flex: 1;
        word-wrap: break-word;
        overflow-wrap: break-word;
        max-width: 100%;
    }
    
    .todo-content h3 {
        margin-bottom: 5px;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    
    .todo-content p {
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .toggle-button {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s;
    }
    
    .toggle-button:hover {
        transform: scale(1.1);
    }
    
    .edit-button, .delete-button {
        background: none;
        border: none;
        padding: 5px;
        margin: 0 2px;
        cursor: pointer;
        color: var(--text-muted);
        transition: color 0.2s, transform 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .edit-button:hover, .delete-button:hover {
        color: var(--text-color);
        transform: scale(1.1);
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Получаем все задачи и доски
    const todoItems = document.querySelectorAll('.todo-item');
    const boardContainers = document.querySelectorAll('.board-container');
    
    // Настраиваем каждую задачу для возможности перетаскивания
    todoItems.forEach(todo => {
        todo.setAttribute('draggable', 'true');
        
        todo.addEventListener('dragstart', function(e) {
            e.dataTransfer.setData('text/plain', e.target.id);
            e.target.classList.add('dragging');
            setTimeout(() => {
                e.target.style.opacity = '0.4';
            }, 0);
        });
        
        todo.addEventListener('dragend', function(e) {
            e.target.classList.remove('dragging');
            e.target.style.opacity = '1';
        });
    });
    
    // Обработчики событий для контейнеров с досками
    boardContainers.forEach(board => {
        // Предотвращаем стандартное поведение, чтобы разрешить drop
        board.addEventListener('dragover', function(e) {
            e.preventDefault();
        });
        
        // Подсветка при наведении
        board.addEventListener('dragenter', function(e) {
            e.preventDefault();
            this.classList.add('drag-over');
        });
        
        // Убираем подсветку при покидании
        board.addEventListener('dragleave', function() {
            this.classList.remove('drag-over');
        });
        
        // Обработка сброса задачи на доску
        board.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
            
            // Получаем ID задачи и ID доски
            const todoId = e.dataTransfer.getData('text/plain').replace('todo-', '');
            const boardId = this.getAttribute('data-board-id');
            
            // Находим элемент задачи
            const todoElement = document.getElementById(`todo-${todoId}`);
            
            // Находим контейнер для задач выбранной доски
            const taskContainer = this.querySelector('.board-tasks');
            
            // Перемещаем задачу в новую доску (визуально)
            if (todoElement && taskContainer) {
                // Удаляем сообщение "На этой доске нет задач", если оно есть
                const noTasksMessage = taskContainer.querySelector('.no-tasks');
                if (noTasksMessage) {
                    noTasksMessage.remove();
                }
                
                taskContainer.appendChild(todoElement);
                
                // Отправляем AJAX запрос для обновления привязки задачи к доске
                updateTodoBoard(todoId, boardId);
            }
        });
    });
    
    // Функция для отправки AJAX запроса на обновление привязки задачи к доске
    function updateTodoBoard(todoId, boardId) {
        // Получаем токен CSRF из мета-тега
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/todos/${todoId}/move-to-board`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                board_id: boardId
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Ошибка при обновлении доски');
            }
            return response.json();
        })
        .then(data => {
            console.log('Задача успешно перемещена', data);
            
            // Удаляем существующие уведомления перед созданием новых
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notification => notification.remove());
            
            // Опционально: показываем уведомление
            const notification = document.createElement('div');
            notification.className = 'notification success';
            notification.textContent = 'Задача перемещена на новую доску';
            document.body.appendChild(notification);
            
            // Скрываем уведомление через 3 секунды
            setTimeout(() => {
                notification.remove();
            }, 3000);
        })
        .catch(error => {
            console.error('Ошибка:', error);
            
            // Удаляем существующие уведомления перед созданием новых
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notification => notification.remove());
            
            // Показываем уведомление об ошибке
            const notification = document.createElement('div');
            notification.className = 'notification error';
            notification.textContent = 'Ошибка при перемещении задачи';
            document.body.appendChild(notification);
            
            // Скрываем уведомление через 3 секунды
            setTimeout(() => {
                notification.remove();
            }, 3000);
        });
    }
    
    // Функционал для сортировки досок
    const boardsContainer = document.getElementById('boards-container');
    let draggedBoard = null;
    
    // Если контейнер с досками существует
    if (boardsContainer) {
        const boardItems = boardsContainer.querySelectorAll('.board-container');
        
        // Настройка досок для перетаскивания
        boardItems.forEach(board => {
            // Добавляем обработчики для заголовка доски
            const boardHeader = board.querySelector('.board-header');
            
            if (boardHeader) {
                boardHeader.setAttribute('draggable', 'true');
                
                boardHeader.addEventListener('dragstart', function(e) {
                    draggedBoard = board;
                    setTimeout(() => {
                        board.classList.add('dragging');
                    }, 0);
                });
                
                boardHeader.addEventListener('dragend', function() {
                    board.classList.remove('dragging');
                    updateBoardsOrder();
                });
            }
            
            // Обработчики для всей доски
            board.addEventListener('dragover', function(e) {
                e.preventDefault();
                if (draggedBoard) {
                    const afterElement = getDragAfterElement(boardsContainer, e.clientY);
                    if (afterElement == null) {
                        boardsContainer.appendChild(draggedBoard);
                    } else {
                        boardsContainer.insertBefore(draggedBoard, afterElement);
                    }
                }
            });
        });
    }
    
    // Функция для определения, после какого элемента вставить
    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.board-container:not(.dragging)')];
        
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }
    
    // Функция для обновления порядка досок
    function updateBoardsOrder() {
        const boards = document.querySelectorAll('.board-container');
        const boardsData = [];
        
        boards.forEach((board, index) => {
            const boardId = board.getAttribute('data-board-id');
            boardsData.push({ id: boardId, position: index });
        });
        
        // Получаем CSRF-токен
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Отправляем данные на сервер
        fetch('/boards/order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ boards: boardsData })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Ошибка при обновлении порядка досок');
            }
            return response.json();
        })
        .then(data => {
            console.log('Порядок досок обновлен:', data);
        })
        .catch(error => {
            console.error('Ошибка:', error);
        });
    }
});
</script>
@endsection 