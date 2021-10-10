<?php

namespace App\Policies\LifeManager;

use App\Models\LifeManager\ToDo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ToDoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param ToDo $toDo
     * @return Response
     */
    public function view(User $user, ToDo $toDo): Response {
        return util_standardPolicyResponse($user, $toDo);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool {
        return true;
    }

    /**
     * @param User $user
     * @param ToDo $toDo
     * @return Response
     */
    public function edit(User $user, ToDo $toDo): Response {
        return util_standardPolicyResponse($user, $toDo);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param ToDo $toDo
     * @return Response
     */
    public function update(User $user, ToDo $toDo): Response {
        return util_standardPolicyResponse($user, $toDo);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param ToDo $toDo
     * @return Response
     */
    public function delete(User $user, ToDo $toDo): Response {
        return util_standardPolicyResponse($user, $toDo);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param ToDo $toDo
     * @return bool
     */
    public function restore(User $user, ToDo $toDo): bool {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param ToDo $toDo
     * @return bool
     */
    public function forceDelete(User $user, ToDo $toDo): bool {
        return false;
    }
}
