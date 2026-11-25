<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Club;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $role = (string) $request->query('role', '');

        $users = User::approved()
            ->with('club')
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when(in_array($role, ['student', 'club_member', 'bde'], true), function ($q) use ($role) {
                $q->where('role', $role);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users', [
            'users' => $users,
            'clubs' => Club::active()->orderBy('name')->get(),
            'search' => $search,
            'role' => $role,
        ]);
    }

    public function updateRole(UpdateRoleRequest $request, User $user)
    {
        // On ne modifie jamais son propre rôle pour ne pas se retirer l'administration.
        abort_if($user->id === Auth::id(), 403, 'Vous ne pouvez pas modifier votre propre rôle.');

        $data = $request->validated();

        $role = Role::from($data['role']);
        $clubId = $role === Role::ClubMember ? (int) $data['club_id'] : null;
        if ($role === Role::ClubMember) {
            Club::findOrFail($clubId);
        }
        $user->assignRole($role, $clubId);

        return back()->with('status', 'Le rôle de ' . $user->full_name . ' a été mis à jour.');
    }
}
