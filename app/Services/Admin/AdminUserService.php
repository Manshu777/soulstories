<?php

namespace App\Services\Admin;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdminUserService
{
    public function list(array $filters): LengthAwarePaginator
    {
        return User::query()
            ->with('roles:id,name')
            ->when(($filters['q'] ?? null), fn ($q, $term) => $q->where(function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('username', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            }))
            ->when(($filters['status'] ?? null), fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();
    }

    public function setStatus(User $user, string $status, ?User $actor = null): void
    {
        $user->update(['status' => $status]);
        $this->log($actor?->id, "user.{$status}", $user);
    }

    public function assignRole(User $user, string $role, ?User $actor = null): void
    {
        $user->syncRoles([$role]);
        $this->log($actor?->id, 'user.role_assigned', $user, ['role' => $role]);
    }

    private function log(?int $actorId, string $action, User $target, array $meta = []): void
    {
        ActivityLog::create([
            'user_id' => $actorId,
            'action' => $action,
            'target_type' => User::class,
            'target_id' => $target->id,
            'meta' => $meta,
        ]);
    }
}
