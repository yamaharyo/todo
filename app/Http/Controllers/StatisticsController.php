<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $boards = $user->boards()->orderBy('position')->get();
        
        $selectedBoardId = $request->input('board_id');
        $searchQuery = $request->input('search');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        // Базовая статистика
        $baseQuery = Todo::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($selectedBoardId) {
            $baseQuery->where('board_id', $selectedBoardId);
        }

        if ($searchQuery) {
            $baseQuery->where(function($q) use ($searchQuery) {
                $q->where('title', 'like', "%{$searchQuery}%")
                  ->orWhere('description', 'like', "%{$searchQuery}%");
            });
        }

        $statistics = [
            'total' => $baseQuery->count(),
            'completed' => (clone $baseQuery)->where('completed', true)->count(),
            'incomplete' => (clone $baseQuery)->where('completed', false)->count(),
        ];

        // Статистика по дням
        $daysQuery = Todo::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($selectedBoardId) {
            $daysQuery->where('board_id', $selectedBoardId);
        }

        if ($searchQuery) {
            $daysQuery->where(function($q) use ($searchQuery) {
                $q->where('title', 'like', "%{$searchQuery}%")
                  ->orWhere('description', 'like', "%{$searchQuery}%");
            });
        }

        $statistics['by_day'] = [
            'completed' => (clone $daysQuery)
                ->where('completed', true)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'incomplete' => (clone $daysQuery)
                ->where('completed', false)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                ->groupBy('date')
                ->orderBy('date')
                ->get()
        ];

        // Статистика по доскам
        $boardsQuery = Todo::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($selectedBoardId) {
            $boardsQuery->where('board_id', $selectedBoardId);
        }

        if ($searchQuery) {
            $boardsQuery->where(function($q) use ($searchQuery) {
                $q->where('title', 'like', "%{$searchQuery}%")
                  ->orWhere('description', 'like', "%{$searchQuery}%");
            });
        }

        $statistics['by_board'] = [
            'completed' => (clone $boardsQuery)
                ->where('completed', true)
                ->select('board_id', DB::raw('COUNT(*) as count'))
                ->groupBy('board_id')
                ->get(),
            'incomplete' => (clone $boardsQuery)
                ->where('completed', false)
                ->select('board_id', DB::raw('COUNT(*) as count'))
                ->groupBy('board_id')
                ->get()
        ];

        return view('statistics.index', compact('boards', 'statistics', 'selectedBoardId', 'searchQuery', 'startDate', 'endDate'));
    }
} 