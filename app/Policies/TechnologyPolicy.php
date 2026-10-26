<?php

namespace App\Policies;

use App\Models\Technology;
use App\Models\User;

class TechnologyPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Technology $technology): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Technology $technology): bool
    {
        return true;
    }

    public function delete(User $user, Technology $technology): bool
    {
        return true;
    }

    public function viewTrashed(User $user): bool
    {
        return true;
    }

    public function restore(User $user, Technology $technology): bool
    {
        return true;
    }

    public function forceDelete(User $user, Technology $technology): bool
    {
        return true;
    }
}