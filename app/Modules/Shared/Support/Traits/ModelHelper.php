<?php

namespace App\Modules\Shared\Support\Traits;

use Illuminate\Database\Eloquent\Model;

trait ModelHelper
{
    function whenRelationLoaded(string $relation, callable $callback, mixed $default = null)
    {
        return ($this->relationLoaded($relation)) ? $callback() : $default;
    }
}
