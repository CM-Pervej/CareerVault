<?php

namespace App\Policies;

use App\Models\CompanySize;
use App\Models\User;

class CompanySizePolicy
{
    /**
     * Determine whether the user can view any company sizes.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view a company size.
     */
    public function view(User $user, CompanySize $companySize): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create company sizes.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update a company size.
     */
    public function update(User $user, CompanySize $companySize): bool
    {
        return true;
    }

    /**
     * Determine whether the user can move a company size to trash.
     */
    public function delete(User $user, CompanySize $companySize): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore a company size.
     */
    public function restore(User $user, CompanySize $companySize): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete a company size.
     */
    public function forceDelete(User $user, CompanySize $companySize): bool
    {
        return true;
    }
}
