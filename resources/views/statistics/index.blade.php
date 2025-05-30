@extends('layouts.app')

@section('title', 'Статистика')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold mb-4">Статистика задач</h1>
        
        <form action="{{ route('statistics.index') }}" method="GET" class="mb-8">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label for="board_id" class="block text-sm font-medium mb-2">Доска</label>
                        <select name="board_id" id="board_id" class="form-select">
                            <option value="">Все доски</option>
                            @foreach($boards as $board)
                                <option value="{{ $board->id }}" {{ $selectedBoardId == $board->id ? 'selected' : '' }}>
                                    {{ $board->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="search" class="block text-sm font-medium mb-2">Поиск</label>
                        <input type="text" name="search" id="search" value="{{ $searchQuery }}" 
                               class="form-input" placeholder="Поиск по задачам">
                    </div>
                    
                    <div>
                        <label for="start_date" class="block text-sm font-medium mb-2">Начало периода</label>
                        <input type="date" name="start_date" id="start_date" 
                               value="{{ $startDate }}" class="form-input">
                    </div>
                    
                    <div>
                        <label for="end_date" class="block text-sm font-medium mb-2">Конец периода</label>
                        <input type="date" name="end_date" id="end_date" 
                               value="{{ $endDate }}" class="form-input">
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="action-button action-button-primary">
                    Применить фильтры
                </button>
            </div>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Общая статистика</h2>
                <div class="space-y-2">
                    <p>Всего задач: {{ $statistics['total'] }}</p>
                    <p>Выполнено задач: {{ $statistics['completed'] }}</p>
                    <p>Невыполнено задач: {{ $statistics['incomplete'] }}</p>
                    <p>Процент выполнения: {{ $statistics['total'] > 0 ? round(($statistics['completed'] / $statistics['total']) * 100) : 0 }}%</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Статистика по доскам</h2>
                <div style="height: 300px;">
                    <canvas id="boardChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 md:col-span-2">
                <h2 class="text-xl font-semibold mb-4">Статистика по дням</h2>
                <div style="height: 300px;">
                    <canvas id="dayChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }

    .action-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: all 0.2s;
        cursor: pointer;
        border: none;
        font-size: 0.875rem;
        line-height: 1.25rem;
        gap: 0.5rem;
        min-width: 150px;
    }

    .action-button-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .action-button-primary:hover {
        background-color: var(--hover-color);
    }

    .action-button:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(123, 104, 238, 0.2);
    }

    .action-button:active {
        transform: translateY(1px);
    }

    .form-select,
    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 0.375rem;
        background-color: var(--card-color);
        color: var(--text-color);
        font-size: 0.875rem;
        line-height: 1.25rem;
        transition: all 0.2s;
    }

    .form-select:focus,
    .form-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(123, 104, 238, 0.2);
    }

    .form-select:hover,
    .form-input:hover {
        border-color: var(--primary-color);
    }

    .form-select option {
        background-color: var(--card-color);
        color: var(--text-color);
    }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
// Ждем полной загрузки страницы
window.addEventListener('load', function() {
    console.log('Страница загружена');
    
    // Проверяем загрузку Chart.js
    if (typeof Chart === 'undefined') {
        console.error('Chart.js не загружен');
        return;
    }
    console.log('Chart.js загружен');

    // Проверяем наличие элементов canvas
    const boardChartCanvas = document.getElementById('boardChart');
    const dayChartCanvas = document.getElementById('dayChart');

    if (!boardChartCanvas || !dayChartCanvas) {
        console.error('Canvas elements not found');
        return;
    }
    console.log('Canvas элементы найдены');

    try {
        // Создаем тестовый график для проверки
        const testData = {
            labels: ['Тест 1', 'Тест 2', 'Тест 3'],
            datasets: [{
                label: 'Тестовые данные',
                data: [1, 2, 3],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        // Создаем тестовый график
        new Chart(boardChartCanvas, {
            type: 'bar',
            data: testData,
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        console.log('Тестовый график создан');

        // Если тестовый график работает, создаем основные графики
        const boardData = {
            labels: {!! json_encode($boards->pluck('name')) !!},
            datasets: [
                {
                    label: 'Выполненные задачи',
                    data: {!! json_encode($boards->map(function($board) use ($statistics) {
                        return $statistics['by_board']['completed']->firstWhere('board_id', $board->id)?->count ?? 0;
                    })) !!},
                    backgroundColor: {!! json_encode($boards->pluck('color')) !!}
                },
                {
                    label: 'Невыполненные задачи',
                    data: {!! json_encode($boards->map(function($board) use ($statistics) {
                        return $statistics['by_board']['incomplete']->firstWhere('board_id', $board->id)?->count ?? 0;
                    })) !!},
                    backgroundColor: {!! json_encode($boards->map(function($board) {
                        return $board->color . '80';
                    })) !!}
                }
            ]
        };

        const dayData = {
            labels: {!! json_encode($statistics['by_day']['completed']->map(function($item) {
                return Carbon\Carbon::parse($item->date)->format('d.m.Y');
            })) !!},
            datasets: [
                {
                    label: 'Выполненные задачи',
                    data: {!! json_encode($statistics['by_day']['completed']->pluck('count')) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                },
                {
                    label: 'Невыполненные задачи',
                    data: {!! json_encode($statistics['by_day']['incomplete']->pluck('count')) !!},
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderColor: 'rgb(239, 68, 68)',
                    borderWidth: 1
                }
            ]
        };

        // Создаем график по дням
        new Chart(dayChartCanvas, {
            type: 'bar',
            data: dayData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        console.log('Основные графики созданы');
    } catch (error) {
        console.error('Ошибка при создании графиков:', error);
    }
});
</script>
@endpush 