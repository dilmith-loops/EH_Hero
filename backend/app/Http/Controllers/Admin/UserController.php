<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = AppUser::withCount('generations')->latest();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(int $id): View
    {
        $user = AppUser::with(['generations' => function ($q) {
            $q->latest();
        }])->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $user = AppUser::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function exportCsv(): StreamedResponse
    {
        $users = AppUser::withCount('generations')->orderBy('id', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="elephant_house_users_' . date('Y-m-d') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($users) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Participant Name', 'IP Address', 'Generations Count', 'Registered Date']);

            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->id,
                    $user->name,
                    $user->ip_address ?? 'N/A',
                    $user->generations_count,
                    $user->created_at->format('Y-m-d h:i:s A'),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
