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

    public const ExportTask = 'task:export';

    public const CreateUser = 'user:create';
    public const UpdateUser = 'user:update';
    public const ReplaceUser = 'user:replace';
    public const DeleteUser = 'user:delete';

    public static function getAbilities(User $user) {
        if ($user->is_manager) {
            return [
                self::CreateTask,
                self::UpdateTask,
                self::ReplaceTask,
                self::DeleteTask,
                self::CreateUser,
                self::UpdateUser,
                self::ReplaceUser,
                self::DeleteUser,
                self::ExportTask
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