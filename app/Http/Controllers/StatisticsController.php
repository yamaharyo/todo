<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        $query = Todo::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($selectedBoardId) {
            $query->where('board_id', $selectedBoardId);
        }

        if ($searchQuery) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('title', 'like', "%{$searchQuery}%")
                  ->orWhere('description', 'like', "%{$searchQuery}%");
            });
        }

        $statistics = [
            'total' => $query->count(),
            'completed' => $query->where('completed', true)->count(),
            'by_day' => $query->where('completed', true)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->get(),
            'by_board' => $query->where('completed', true)
                ->selectRaw('board_id, COUNT(*) as count')
                ->groupBy('board_id')
                ->get()
        ];

        return view('statistics.index', compact('boards', 'statistics', 'selectedBoardId', 'searchQuery', 'startDate', 'endDate'));
    }
} 