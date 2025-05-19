@extends('layouts.app')

@section('title', 'Статистика')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold mb-4">Статистика выполненных задач</h1>
        
        <form action="{{ route('statistics.index') }}" method="GET" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="board_id" class="block text-sm font-medium mb-1">Доска</label>
                    <select name="board_id" id="board_id" class="w-full rounded-lg border-gray-300">
                        <option value="">Все доски</option>
                        @foreach($boards as $board)
                            <option value="{{ $board->id }}" {{ $selectedBoardId == $board->id ? 'selected' : '' }}>
                                {{ $board->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="search" class="block text-sm font-medium mb-1">Поиск</label>
                    <input type="text" name="search" id="search" value="{{ $searchQuery }}" 
                           class="w-full rounded-lg border-gray-300" placeholder="Поиск по задачам">
                </div>
                
                <div>
                    <label for="start_date" class="block text-sm font-medium mb-1">Начало периода</label>
                    <input type="date" name="start_date" id="start_date" 
                           value="{{ $startDate }}" class="w-full rounded-lg border-gray-300">
                </div>
                
                <div>
                    <label for="end_date" class="block text-sm font-medium mb-1">Конец периода</label>
                    <input type="date" name="end_date" id="end_date" 
                           value="{{ $endDate }}" class="w-full rounded-lg border-gray-300">
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
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
                    <p>Процент выполнения: {{ $statistics['total'] > 0 ? round(($statistics['completed'] / $statistics['total']) * 100) : 0 }}%</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Статистика по доскам</h2>
                <canvas id="boardChart"></canvas>
            </div>

            <div class="bg-white rounded-lg shadow p-6 md:col-span-2">
                <h2 class="text-xl font-semibold mb-4">Статистика по дням</h2>
                <canvas id="dayChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Данные для графика по доскам
    const boardData = {
        labels: {!! json_encode($statistics['by_board']->map(function($item) use ($boards) {
            return $boards->firstWhere('id', $item->board_id)->name;
        })) !!},
        datasets: [{
            data: {!! json_encode($statistics['by_board']->pluck('count')) !!},
            backgroundColor: {!! json_encode($statistics['by_board']->map(function($item) use ($boards) {
                return $boards->firstWhere('id', $item->board_id)->color;
            })) !!}
        }]
    };

    // Данные для графика по дням
    const dayData = {
        labels: {!! json_encode($statistics['by_day']->pluck('date')) !!},
        datasets: [{
            label: 'Выполненные задачи',
            data: {!! json_encode($statistics['by_day']->pluck('count')) !!},
            borderColor: 'rgb(59, 130, 246)',
            tension: 0.1
        }]
    };

    // Создание графика по доскам
    new Chart(document.getElementById('boardChart'), {
        type: 'pie',
        data: boardData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });

    // Создание графика по дням
    new Chart(document.getElementById('dayChart'), {
        type: 'line',
        data: dayData,
        options: {
            responsive: true,
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
});
</script>
@endsection 