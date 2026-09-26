<?php

namespace App\Policies;

use App\Models\PlatformGroup;
use App\Models\User;

class PlatformGroupPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, PlatformGroup $platformGroup): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, PlatformGroup $platformGroup): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, PlatformGroup $platformGroup): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, PlatformGroup $platformGroup): bool
    {
        return $user->isSuperAdmin();
    }

    public function forceDelete(User $user, PlatformGroup $platformGroup): bool
    {
        return $user->isSuperAdmin();
    }
}