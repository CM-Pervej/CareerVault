<?php

namespace App\Policies;

use App\Models\TechCategory;
use App\Models\User;

class TechCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TechCategory $techCategory): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, TechCategory $techCategory): bool
    {
        return true;
    }

    public function delete(User $user, TechCategory $techCategory): bool
    {
        return true;
    }

    public function viewTrashed(User $user): bool
    {
        return true;
    }

    public function restore(User $user, TechCategory $techCategory): bool
    {
        return true;
    }

    public function forceDelete(User $user, TechCategory $techCategory): bool
    {
        return true;
    }
}
