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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
        ]);

        $ipAddress = $request->ip();

        $user = AppUser::firstOrCreate(
            ['phone' => trim($validated['phone'])],
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
