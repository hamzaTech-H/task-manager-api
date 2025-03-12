<?php

namespace App\Policies\V1;

use App\Models\Task;
use App\Models\User;
use App\Permissions\V1\Abilities;

class TaskPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function delete(User $user, Task $task) {
        if ($user->tokenCan(Abilities::DeleteTask)) {
            return true;
        } else if ($user->tokenCan(Abilities::DeleteOwnTask)) {
            return $user->id === $task->user_id;
        }

        return false;
    }

    public function replace(User $user, Task $task) {
        return $user->tokenCan(Abilities::ReplaceTask);
    }

    public function store(User $user) {
        return $user->tokenCan(Abilities::CreateTask) ||
               $user->tokenCan(Abilities::CreateOwnTask);
    }

    public function update(User $user, Task $task) {
        if ($user->tokenCan(Abilities::UpdateTask)) {
            return true;
        } else if ($user->tokenCan(Abilities::UpdateOwnTask)) {
            return $user->id === $task->user_id;
        }

        return false;
    }
 }