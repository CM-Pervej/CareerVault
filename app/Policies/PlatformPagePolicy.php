<?php

namespace App\Policies;

use App\Models\PlatformPage;
use App\Models\User;

class PlatformPagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, PlatformPage $platformPage): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, PlatformPage $platformPage): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, PlatformPage $platformPage): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, PlatformPage $platformPage): bool
    {
        return $user->isSuperAdmin();
    }

    public function forceDelete(User $user, PlatformPage $platformPage): bool
    {
        return $user->isSuperAdmin();
    }
}