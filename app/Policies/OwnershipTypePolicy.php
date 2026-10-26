<?php

namespace App\Policies;

use App\Models\OwnershipType;
use App\Models\User;

class OwnershipTypePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, OwnershipType $ownershipType): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, OwnershipType $ownershipType): bool
    {
        return true;
    }

    public function delete(User $user, OwnershipType $ownershipType): bool
    {
        return true;
    }

    public function restore(User $user, OwnershipType $ownershipType): bool
    {
        return true;
    }

    public function forceDelete(User $user, OwnershipType $ownershipType): bool
    {
        return true;
    }
}
