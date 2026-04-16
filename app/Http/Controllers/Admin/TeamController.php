<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role', 'manager')
            ->where('manager_id', '!=', null)
            ->orWhereHas('subordinates');

        if ($request->filled('manager_id')) {
            $query->where('manager_id', $request->manager_id);
        }

        $leaders = User::with('role')
            ->whereHas('subordinates')
            ->orWhere('manager_id', auth()->id())
            ->get();

        $teams = [];
        foreach ($leaders as $leader) {
            $teams[] = [
                'leader' => $leader,
                'members' => $leader->subordinates()->with('role')->get(),
            ];
        }

        $allManagers = User::with('role')
            ->whereIn('role_id', Role::whereIn('slug', ['admin', 'leader', 'staff'])->pluck('id'))
            ->get();

        return view('admin.teams.index', compact('teams', 'allManagers'));
    }

    public function show(User $user)
    {
        if ($user->subordinates()->count() === 0) {
            return redirect()->route('admin.teams.index')->with('error', 'Người dùng này không có thành viên trong nhóm.');
        }

        $teamMembers = $user->subordinates()->with('role', 'directPermissions')->get();

        return view('admin.teams.show', compact('user', 'teamMembers'));
    }
}
