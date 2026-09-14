<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Generation;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Show the IP generation limit settings page.
     */
    public function index(): View
    {
        $limitEnabled = Setting::isLimitEnabled();
        $limitMax = Setting::getMaxGenerationsPerIp();
        $limitPeriod = Setting::getLimitPeriod();
        $limitMessage = Setting::getLimitMessage();

        $totalDistinctIps = Generation::whereNotNull('ip_address')->distinct('ip_address')->count('ip_address');

        $topIps = Generation::select('ip_address', DB::raw('count(*) as count'), DB::raw('max(created_at) as latest_activity'))
            ->whereNotNull('ip_address')
            ->groupBy('ip_address')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        return view('admin.settings.index', compact(
            'limitEnabled',
            'limitMax',
            'limitPeriod',
            'limitMessage',
            'totalDistinctIps',
            'topIps'
        ));
    }

    /**
     * Update IP generation limit settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ip_limit_enabled' => 'nullable|boolean',
            'ip_limit_max' => 'required|integer|min:1|max:1000',
            'ip_limit_period' => 'required|in:lifetime,daily',
            'ip_limit_message' => 'nullable|string|max:500',
        ]);

        Setting::set('ip_limit_enabled', $request->boolean('ip_limit_enabled'));
        Setting::set('ip_limit_max', (int) $validated['ip_limit_max']);
        Setting::set('ip_limit_period', $validated['ip_limit_period']);

        if (!empty($validated['ip_limit_message'])) {
            Setting::set('ip_limit_message', trim($validated['ip_limit_message']));
        }

        return redirect()->route('admin.settings.index')->with('success', 'Generation limit settings saved successfully!');
    }
}
