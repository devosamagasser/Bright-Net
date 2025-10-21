<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class BrandFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function region($regionId)
    {
        return $this->where('region_id', $regionId);
    }

    public function name($name)
    {
        return $this->where('name', 'LIKE', "%$name%");
    }

    public function solution($solutionId)
    {
        return $this->whereHas('solutions', function ($query) use ($solutionId) {
            $query->where('solutions.id', $solutionId);
        });
    }

}
