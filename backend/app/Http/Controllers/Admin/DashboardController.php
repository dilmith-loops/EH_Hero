<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Models\Generation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers = AppUser::count();
        $totalGenerations = Generation::count();
        $todayGenerations = Generation::whereDate('created_at', Carbon::today())->count();
        $todayUsers = AppUser::whereDate('created_at', Carbon::today())->count();

        $treatStats = Generation::select('treat_id', 'treat_name', DB::raw('count(*) as count'))
            ->groupBy('treat_id', 'treat_name')
            ->orderByDesc('count')
            ->get();

        $recentGenerations = Generation::with('user')->latest()->take(8)->get();
        $recentUsers = AppUser::withCount('generations')->latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalGenerations',
            'todayGenerations',
            'todayUsers',
            'treatStats',
            'recentGenerations',
            'recentUsers'
        ));
    }
}
