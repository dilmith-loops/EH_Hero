<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display a listing of admin users.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $admins = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin user.
     */
    public function create(): View
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created admin user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin user account created successfully!');
    }

    /**
     * Show the form for editing an admin user.
     */
    public function edit(User $admin): View
    {
        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified admin user in storage.
     */
    public function update(Request $request, User $admin): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $admin->name = trim($validated['name']);
        $admin->email = strtolower(trim($validated['email']));

        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return redirect()->route('admin.admins.index')->with('success', "Admin user '{$admin->name}' updated successfully!");
    }

    /**
     * Remove the specified admin user from storage.
     */
    public function destroy(User $admin): RedirectResponse
    {
        if ($admin->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own admin account while signed in.');
        }

        if (User::count() <= 1) {
            return back()->with('error', 'Cannot delete the only remaining administrator account.');
        }

        $name = $admin->name;
        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', "Admin user '{$name}' has been deleted.");
    }
}
