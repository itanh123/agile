<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('role')
            ->when($request->filled('q'), fn ($q) => $q->where('email', 'like', '%' . $request->q . '%')->orWhere('full_name', 'like', '%' . $request->q . '%'))
            ->when($request->filled('role'), fn ($q) => $q->where('role_id', $request->role))
            ->latest()
            ->paginate(12)
            ->appends($request->query());

        $roles = \App\Models\Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role_id' => 'nullable|exists:roles,id',
            'password' => 'required|string|min:6',
        ]);
        $data['name'] = $data['full_name'];
        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    public function show(User $user)
    {
        $user->load(['bookings.pet', 'bookings.services']);

        $bookingsCount = $user->bookings->count();
        $pendingBookings = $user->bookings->where('status', 'pending')->count();
        $completedBookings = $user->bookings->where('status', 'completed')->count();

        return view('admin.users.show', compact('user', 'bookingsCount', 'pendingBookings', 'completedBookings'));
    }

    public function edit(User $user)
    {
        if ($user->role?->slug === 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'Cannot edit Admin accounts.');
        }

        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role?->slug === 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'Cannot update Admin accounts.');
        }

        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role_id' => 'nullable|exists:roles,id',
            'password' => 'nullable|string|min:6',
        ]);
        $data['name'] = $data['full_name'];
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        if ($user->role?->slug === 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'Cannot delete Admin accounts.');
        }

        $user->delete();

        return back()->with('success', 'User deleted.');
    }
}
