<?php

namespace App\Enums;

enum TaskStatus: String
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case OnHold = 'on_hold';
    case Cancelled = 'cancelled';
}
