<?php

namespace App\Policies;

use App\Models\Platform;
use App\Models\User;

class PlatformPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Platform $platform): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Platform $platform): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Platform $platform): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Platform $platform): bool
    {
        return $user->isSuperAdmin();
    }

    public function forceDelete(User $user, Platform $platform): bool
    {
        return $user->isSuperAdmin();
    }
}