<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.pengguna.index', [
            'users' => User::with('roles')->latest()->get(),
            'listRole' => Role::pluck('name', 'name'),
        ]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $request->validate(['role' => ['required', 'in:admin,customer']]);

        $user->syncRoles([$request->role]);

        return back()->with('sukses', 'Peran pengguna diubah.');
    }

    public function toggleAktif(User $user): RedirectResponse
    {
        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('sukses', 'Status akun diubah.');
    }
}
