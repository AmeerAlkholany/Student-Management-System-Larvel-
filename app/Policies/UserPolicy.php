<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can list user accounts.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the account.
     */
    public function view(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->is($model);
    }

    /**
     * Determine whether the user can create accounts.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the account.
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->is($model);
    }

    /**
     * Determine whether the user can delete the account.
     *
     * Administrators may not delete their own account.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin() && ! $user->is($model);
    }
}
