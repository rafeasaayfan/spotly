<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Website;
use Illuminate\Auth\Access\Response;

class WebsitePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Website $website): bool
    {
        if ($user->id === $website->owner_id) {
            return true;
        }

        if (in_array($user->role, ['admin', 'super_admin'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Website $website): bool
    {
        if ($user->id === $website->owner_id) {
            return true;
        }

        if (in_array($user->role, ['admin', 'super_admin'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Website $website): bool
    {
        if ($user->id === $website->owner_id) {
            return true;
        }

        if (in_array($user->role, ['super_admin'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Website $website): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Website $website): bool
    {
        return false;
    }

    /**
     * Determine if the user can activate/deactivate the website.
     */
    public function activate(User $user, Website $website)
    {
        if ($user->id === $website->owner_id) {
            return true;
        }

        if (in_array($user->role, ['admin', 'super_admin'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can make/change/delete the website ui.
    */
    public function ui(User $user, Website $website)
    {
        if ($user->id === $website->owner_id) {
            return true;
        }

        if (in_array($user->role, ['admin', 'super_admin'])) {
            return true;
        }

        return false;
    }
}
