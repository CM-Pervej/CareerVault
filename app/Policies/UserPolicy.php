<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, User $target): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, User $target): bool
    {
        if(!$user->isAdmin()){
            return false;
        }

        if($user->isSuperAdmin()){
            return true;
        }

        return $target->role === 'user';
    }

    public function delete(User $user, User $target): bool
    {
        if(!$user->isAdmin()){
            return false;
        }

        if($user->id === $target->id){
            return false;
        }

        if($user->isSuperAdmin()){
            return true;
        }

        return $target->role === 'user';
    }

    public function restore(User $user, User $target): bool
    {
        return $user->isSuperAdmin();
    }

    public function forceDelete(User $user, User $target): bool
    {
        return $user->isSuperAdmin();
    }
}