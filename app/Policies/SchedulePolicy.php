<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Schedule;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchedulePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the schedule can view any models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the schedule can view the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Schedule  $model
     * @return mixed
     */
    public function view(User $user, Schedule $model)
    {
        return true;
    }

    /**
     * Determine whether the schedule can create models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the schedule can update the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Schedule  $model
     * @return mixed
     */
    public function update(User $user, Schedule $model)
    {
        return true;
    }

    /**
     * Determine whether the schedule can delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Schedule  $model
     * @return mixed
     */
    public function delete(User $user, Schedule $model)
    {
        return true;
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Schedule  $model
     * @return mixed
     */
    public function deleteAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the schedule can restore the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Schedule  $model
     * @return mixed
     */
    public function restore(User $user, Schedule $model)
    {
        return false;
    }

    /**
     * Determine whether the schedule can permanently delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Schedule  $model
     * @return mixed
     */
    public function forceDelete(User $user, Schedule $model)
    {
        return false;
    }
}
