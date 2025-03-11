<?php

namespace App\Http\Filters\V1;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TaskFilter extends QueryFilter
{
    protected $sortable = [
        'title',
        'status',
        'dueDate' => 'due_date',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];

    protected function createdAt($value) 
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->query->whereBetween('created_at', $dates);
        }

        return $this->query->whereDate('created_at', $value);
    }

    protected function dueDate($value) 
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->query->whereBetween('due_date', $dates);
        }

        return $this->query->whereDate('due_date', $value);
    }
    
    public function include($value) {
        return $this->query->with($value);
    }
    
    protected function status(string $value): Builder
    {
        return $this->query->whereIn('status', explode(',', $value));
    }

    protected function title(string $value): Builder
    {
        $likeStr = str_replace('*', '%', $value);
        return $this->query->where('title', 'like', "%{$likeStr}%");
    }

    protected function updatedAt($value) 
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->query->whereBetween('updated_at', $dates);
        }

        return $this->query->whereDate('updated_at', $value);
    }
}