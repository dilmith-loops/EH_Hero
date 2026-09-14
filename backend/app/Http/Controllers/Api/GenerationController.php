<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Models\Generation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerationController extends Controller
{
    /**
     * Store a new image generation and save original & generated images.
     */
    public function store(Request $request): JsonResponse
    {
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
