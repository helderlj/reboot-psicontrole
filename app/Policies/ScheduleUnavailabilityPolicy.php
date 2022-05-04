<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ScheduleUnavailability;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScheduleUnavailabilityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the scheduleUnavailability can view any models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the scheduleUnavailability can view the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ScheduleUnavailability  $model
     * @return mixed
     */
    public function view(User $user, ScheduleUnavailability $model)
    {
        return true;
    }

    /**
     * Determine whether the scheduleUnavailability can create models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the scheduleUnavailability can update the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ScheduleUnavailability  $model
     * @return mixed
     */
    public function update(User $user, ScheduleUnavailability $model)
    {
        return true;
    }

    /**
     * Determine whether the scheduleUnavailability can delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ScheduleUnavailability  $model
     * @return mixed
     */
    public function delete(User $user, ScheduleUnavailability $model)
    {
        return true;
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ScheduleUnavailability  $model
     * @return mixed
     */
    public function deleteAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the scheduleUnavailability can restore the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ScheduleUnavailability  $model
     * @return mixed
     */
    public function restore(User $user, ScheduleUnavailability $model)
    {
        return false;
    }

    /**
     * Determine whether the scheduleUnavailability can permanently delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\ScheduleUnavailability  $model
     * @return mixed
     */
    public function forceDelete(User $user, ScheduleUnavailability $model)
    {
        return false;
    }
}
