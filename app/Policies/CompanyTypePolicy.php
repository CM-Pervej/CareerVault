<?php

namespace App\Policies;

use App\Models\CompanyType;
use App\Models\User;

class CompanyTypePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, CompanyType $companyType): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, CompanyType $companyType): bool
    {
        return true;
    }

    public function delete(User $user, CompanyType $companyType): bool
    {
        return true;
    }

    public function restore(User $user, CompanyType $companyType): bool
    {
        return true;
    }

    public function forceDelete(User $user, CompanyType $companyType): bool
    {
        return true;
    }
}