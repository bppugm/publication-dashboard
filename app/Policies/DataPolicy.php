<?php

namespace App\Policies;

use App\Models\Data;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DataPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->is_superadmin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Data  $data
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Data $data)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Data  $data
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Data $data)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Data  $data
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Data $data)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Data  $data
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Data $data)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Data  $data
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Data $data)
    {
        //
    }
}
