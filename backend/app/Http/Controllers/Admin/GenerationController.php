<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Generation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GenerationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Generation::with('user')->latest();

        if ($request->filled('treat_id')) {
            $query->where('treat_id', $request->input('treat_id'));
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $generations = $query->paginate(16)->withQueryString();

        $treats = Generation::select('treat_id', 'treat_name')
            ->distinct()
            ->orderBy('treat_name')
            ->get();

        return view('admin.generations.index', compact('generations', 'treats'));
    }

    public function download(int $id): BinaryFileResponse
    {
        $generation = Generation::findOrFail($id);

        if (!Storage::disk('public')->exists($generation->generated_image_path)) {
            abort(404, 'Generated image file not found in storage');
        }

        $fullPath = Storage::disk('public')->path($generation->generated_image_path);
        $downloadName = 'wonder_anime_' . $generation->treat_id . '_' . $generation->id . '.png';

        return response()->download($fullPath, $downloadName);
    }

    public function destroy(int $id): RedirectResponse
    {
        $generation = Generation::findOrFail($id);

        // Delete stored files
        if ($generation->original_image_path && Storage::disk('public')->exists($generation->original_image_path)) {
            Storage::disk('public')->delete($generation->original_image_path);
        }

        if ($generation->generated_image_path && Storage::disk('public')->exists($generation->generated_image_path)) {
            Storage::disk('public')->delete($generation->generated_image_path);
        }

        $generation->delete();

        return back()->with('success', 'Generation deleted successfully.');
    }
}
