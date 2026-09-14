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

        $maintenanceMode = Setting::isMaintenanceEnabled();
        $maintenanceMessage = Setting::getMaintenanceMessage();

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
            'maintenanceMode',
            'maintenanceMessage',
            'totalDistinctIps',
            'topIps'
        ));
    }

    /**
     * Update system and IP generation limit settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'maintenance_mode' => 'nullable|boolean',
            'maintenance_message' => 'nullable|string|max:500',
            'ip_limit_enabled' => 'nullable|boolean',
            'ip_limit_max' => 'required|integer|min:1|max:1000',
            'ip_limit_period' => 'required|in:lifetime,daily',
            'ip_limit_message' => 'nullable|string|max:500',
        ]);

        Setting::set('maintenance_mode', $request->boolean('maintenance_mode'));
        if (isset($validated['maintenance_message'])) {
            Setting::set('maintenance_message', trim($validated['maintenance_message']));
        }

        Setting::set('ip_limit_enabled', $request->boolean('ip_limit_enabled'));
        Setting::set('ip_limit_max', (int) $validated['ip_limit_max']);
        Setting::set('ip_limit_period', $validated['ip_limit_period']);

        if (!empty($validated['ip_limit_message'])) {
            Setting::set('ip_limit_message', trim($validated['ip_limit_message']));
        }

        $statusMsg = $request->boolean('maintenance_mode')
            ? 'Settings saved! Maintenance Mode is currently ACTIVE.'
            : 'Settings saved successfully! Platform is LIVE.';

        return redirect()->route('admin.settings.index')->with('success', $statusMsg);
    }

    /**
     * Quick toggle maintenance mode on/off.
     */
    public function toggleMaintenance(Request $request): RedirectResponse
    {
        $currentState = Setting::isMaintenanceEnabled();
        $newState = !$currentState;

        Setting::set('maintenance_mode', $newState);

        $msg = $newState
            ? 'Maintenance mode turned ON. Public participants will see the maintenance screen.'
            : 'Maintenance mode turned OFF. Wonder Hero platform is now LIVE!';

        return redirect()->back()->with('success', $msg);
    }
}
