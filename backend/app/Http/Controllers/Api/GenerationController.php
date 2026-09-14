<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Models\Generation;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerationController extends Controller
{
    /**
     * Check limit status for the requesting IP address.
     */
    public function checkLimit(Request $request): JsonResponse
    {
        if (Setting::isMaintenanceEnabled()) {
            return response()->json([
                'maintenance' => true,
                'limit_enabled' => true,
                'can_generate' => false,
                'max_limit' => 0,
                'current_count' => 0,
                'remaining' => 0,
                'period' => 'maintenance',
                'message' => Setting::getMaintenanceMessage(),
            ], 503);
        }

        $clientIp = $request->ip();
        $limitEnabled = Setting::isLimitEnabled();
        $maxLimit = Setting::getMaxGenerationsPerIp();
        $period = Setting::getLimitPeriod();

        $query = Generation::where('ip_address', $clientIp);
        if ($period === 'daily') {
            $query->whereDate('created_at', Carbon::today());
        }

        $currentCount = $query->count();
        $remaining = max(0, $maxLimit - $currentCount);
        $canGenerate = !$limitEnabled || ($currentCount < $maxLimit);

        return response()->json([
            'limit_enabled' => $limitEnabled,
            'can_generate' => $canGenerate,
            'max_limit' => $maxLimit,
            'current_count' => $currentCount,
            'remaining' => $limitEnabled ? $remaining : null,
            'period' => $period,
            'message' => !$canGenerate ? Setting::getLimitMessage() : null,
        ]);
    }

    /**
     * Store a new image generation and save original & generated images.
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

        $clientIp = $request->ip();

        // Enforce IP generation limit if enabled
        if (Setting::isLimitEnabled()) {
            $maxLimit = Setting::getMaxGenerationsPerIp();
            $period = Setting::getLimitPeriod();

            $query = Generation::where('ip_address', $clientIp);
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
                    'period' => $period,
                ], 429);
            }
        }

        $validated = $request->validate([
            'app_user_id' => 'required|exists:app_users,id',
            'treat_id' => 'required|string|max:100',
            'treat_name' => 'required|string|max:150',
            'style_id' => 'nullable|string|max:100',
            'custom_prompt' => 'nullable|string|max:2000',
            'original_image' => 'nullable|string',
            'generated_image' => 'required|string',
        ]);

        $originalPath = null;
        if (!empty($validated['original_image'])) {
            $originalPath = $this->saveBase64Image($validated['original_image'], 'originals');
        }

        $generatedPath = $this->saveBase64Image($validated['generated_image'], 'generations');

        $generation = Generation::create([
            'app_user_id' => $validated['app_user_id'],
            'treat_id' => $validated['treat_id'],
            'treat_name' => $validated['treat_name'],
            'style_id' => $validated['style_id'] ?? 'anime',
            'custom_prompt' => $validated['custom_prompt'] ?? null,
            'original_image_path' => $originalPath,
            'generated_image_path' => $generatedPath,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Generation saved successfully',
            'generation' => $generation->fresh(['user']),
        ], 201);
    }

    /**
     * Fetch single generation by ID.
     */
    public function show(int $id): JsonResponse
    {
        $generation = Generation::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'generation' => $generation,
        ]);
    }

    /**
     * Helper to decode and store a base64 or DataURL image.
     */
    private function saveBase64Image(string $imageData, string $directory): string
    {
        $extension = 'png';

        if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
            $extension = strtolower($type[1]);
            if ($extension === 'jpeg') {
                $extension = 'jpg';
            }
        }

        $decodedImage = base64_decode($imageData);
        if ($decodedImage === false) {
            throw new \InvalidArgumentException('Base64 image decoding failed');
        }

        $fileName = $directory . '/' . Str::uuid() . '_' . time() . '.' . $extension;
        Storage::disk('public')->put($fileName, $decodedImage);

        return $fileName;
    }
}
