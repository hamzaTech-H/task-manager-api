<?php

namespace App\Http\Filters\V1;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserFilter extends QueryFilter
{
    protected function createdAt($value) 
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->query->whereBetween('created_at', $dates);
        }

        return $this->query->whereDate('created_at', $value);
    }
    
    public function include($value) {
        return $this->query->with($value);
    }

    public function id($value) {
        return $this->query->whereIn('id', explode(',', $value));
    }

    protected function status(string $value): Builder
    {
        return $this->query->whereIn('status', explode(',', $value));
    }

    public function email($value) {
        $likeStr = str_replace('*', '%', $value);
        return $this->query->where('email', 'like', $likeStr);
    }

    protected function name(string $value): Builder
    {
        $likeStr = str_replace('*', '%', $value);
        return $this->query->where('name', 'like', "%{$likeStr}%");
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