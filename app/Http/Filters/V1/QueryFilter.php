<?php

namespace App\Http\Filters\V1;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter 
{
    protected $query;
    protected $sortable;

    public function __construct(protected Request $request)
    {
        
    }

    public function apply(Builder $query) 
    {
        $this->query = $query;
        foreach ($this->request->all() as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }
    }

    public function sort($value){
        $sortAttributes = explode(',', $value);

        foreach($sortAttributes as $sortAttribute) { 
            $direction = str_starts_with($sortAttribute, '-') ? 'desc' : 'asc';
            $sortAttribute = ltrim($sortAttribute, '-');

            if(!in_array($sortAttribute, $this->sortable) && !array_key_exists($sortAttribute ,$this->sortable)) {
                continue;
            }

            $columnName = $this->sortable[$sortAttribute] ?? $sortAttribute;
            $this->query->orderBy($columnName, $direction);
        }
    }
    
}