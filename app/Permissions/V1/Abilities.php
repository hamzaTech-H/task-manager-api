<?php

namespace App\Permissions\V1;

use App\Models\User;

final class Abilities {
    public const CreateTask = 'task:create';
    public const UpdateTask = 'task:update';
    public const ReplaceTask = 'task:replace';
    public const DeleteTask = 'task:delete';

    public const CreateOwnTask = 'task:own:create';
    public const UpdateOwnTask = 'task:own:update';
    public const DeleteOwnTask = 'task:own:delete';

    public static function getAbilities(User $user) {
        if ($user->is_manager) {
            return [
                self::CreateTask,
                self::UpdateTask,
                self::ReplaceTask,
                self::DeleteTask,
            ];
        } else {
            return [
                self::CreateOwnTask,
                self::UpdateOwnTask,
                self::DeleteOwnTask
            ];
        }
    }
    

}