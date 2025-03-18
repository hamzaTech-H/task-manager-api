<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Http\Filters\V1\QueryFilter;
use App\Http\Filters\V1\TaskFilter;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;
    

    protected $fillable = ['title', 'status', 'description', 'due_date', 'user_id'];

    protected function casts(): array
    {
        return [
            'status' => TaskStatus::class,
            'due_date' => 'datetime',   
        ];
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }


    public function scopeFilter(Builder $query, QueryFilter $filters) 
    {        
        return $filters->apply($query);        
    }
}
