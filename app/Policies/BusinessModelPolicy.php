<?php

namespace App\Policies;

use App\Models\BusinessModel;
use App\Models\User;

class BusinessModelPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, BusinessModel $businessModel): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, BusinessModel $businessModel): bool
    {
        return true;
    }

    public function delete(User $user, BusinessModel $businessModel): bool
    {
        return true;
    }

    public function viewTrashed(User $user): bool
    {
        return true;
    }

    public function restore(User $user, BusinessModel $businessModel): bool
    {
        return true;
    }

    public function forceDelete(User $user, BusinessModel $businessModel): bool
    {
        return true;
    }
}