<?php

namespace App\Policies\LifeManager;

use App\Models\LifeManager\ToDoGroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;


class ToDoGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user) {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param ToDoGroup $toDoGroup
     * @return mixed
     */
    public function view(User $user, ToDoGroup $toDoGroup) {
        return util_standardPolicyResponse($user, $toDoGroup);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user) {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param ToDoGroup $toDoGroup
     * @return mixed
     */
    public function update(User $user, ToDoGroup $toDoGroup) {
        return util_standardPolicyResponse($user, $toDoGroup);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param ToDoGroup $toDoGroup
     * @return mixed
     */
    public function delete(User $user, ToDoGroup $toDoGroup) {
        return util_standardPolicyResponse($user, $toDoGroup);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param ToDoGroup $toDoGroup
     * @return mixed
     */
    public function restore(User $user, ToDoGroup $toDoGroup) {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param ToDoGroup $toDoGroup
     * @return mixed
     */
    public function forceDelete(User $user, ToDoGroup $toDoGroup) {
        return false;
    }
}
