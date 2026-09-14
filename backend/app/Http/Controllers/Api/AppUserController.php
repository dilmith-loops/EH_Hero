<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppUserController extends Controller
{
    /**
     * Register or find existing app user by phone number.
     */
    public function store(Request $request): JsonResponse
    {
        if (\App\Models\Setting::isMaintenanceEnabled()) {
            return response()->json([
                'success' => false,
                'error' => 'maintenance_mode',
                'message' => \App\Models\Setting::getMaintenanceMessage(),
            ], 503);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
        ]);

        $ipAddress = $request->ip();

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
