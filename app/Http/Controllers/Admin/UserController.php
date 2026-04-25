<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\AdminUserService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(private readonly AdminUserService $userService)
    {
    }

    public function index(Request $request)
    {
        $users = $this->userService->list($request->only(['q', 'status']));
        $roles = Role::query()->pluck('name');

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate(['role' => ['required', 'string', 'exists:roles,name']]);
        $this->userService->assignRole($user, $data['role'], $request->user());

        return back()->with('success', 'User role updated.');
    }

    public function updateStatus(Request $request, User $user)
    {
        $data = $request->validate(['status' => ['required', 'in:active,inactive,blocked']]);
        $this->userService->setStatus($user, $data['status'], $request->user());

        return back()->with('success', 'User status updated.');
    }
}
