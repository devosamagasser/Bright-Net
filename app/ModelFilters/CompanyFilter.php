<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class CompanyFilter extends ModelFilter
{
    public $relations = [];

    public function type(string $type): self
    {
        return $this->where('type', $type);
    }

    public function name(string $name): self
    {
        return $this->where('name', 'LIKE', "%{$name}%");
    }
}
