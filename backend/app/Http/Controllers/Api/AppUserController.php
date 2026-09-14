<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Models\Generation;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppUserController extends Controller
{
    /**
     * Register or find existing app user by phone number.
     */
    public function store(Request $request): JsonResponse
    {
        if (Setting::isMaintenanceEnabled()) {
            return response()->json([
                'success' => false,
                'error' => 'maintenance_mode',
                'message' => Setting::getMaintenanceMessage(),
            ], 503);
        }

        $ipAddress = $request->ip();

        // Check if device/IP has reached the generation limit before registration
        if (Setting::isLimitEnabled()) {
            $maxLimit = Setting::getMaxGenerationsPerIp();
            $period = Setting::getLimitPeriod();

            $query = Generation::where('ip_address', $ipAddress);
            if ($period === 'daily') {
                $query->whereDate('created_at', Carbon::today());
            }

            $currentCount = $query->count();
            if ($currentCount >= $maxLimit) {
                return response()->json([
                    'success' => false,
                    'error' => 'limit_reached',
                    'message' => Setting::getLimitMessage(),
                    'current_count' => $currentCount,
                    'max_limit' => $maxLimit,
                ], 429);
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
        ]);

        if (!empty($validated['phone'])) {
            $phone = trim($validated['phone']);
            $user = AppUser::firstOrCreate(
                ['phone' => $phone],
                [
                    'name' => trim($validated['name']),
                    'ip_address' => $ipAddress,
                ]
            );

            $updates = [];
            if ($user->name !== trim($validated['name'])) {
                $updates['name'] = trim($validated['name']);
            }
            if ($user->ip_address !== $ipAddress) {
                $updates['ip_address'] = $ipAddress;
            }
            if (!empty($updates)) {
                $user->update($updates);
            }
        } else {
            $user = AppUser::create([
                'name' => trim($validated['name']),
                'phone' => null,
                'ip_address' => $ipAddress,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User authenticated successfully',
            'user' => $user,
        ]);
    }

    /**
     * Get user details and their generations.
     */
    public function show(int $id): JsonResponse
    {
        $user = AppUser::with(['generations' => function ($query) {
            $query->latest();
        }])->findOrFail($id);

        return response()->json([
            'success' => true,
            'user' => $user,
        ]);
    }
}
