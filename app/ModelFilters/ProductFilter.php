<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class ProductFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function supplier($value)
    {
        $this->where('supplier_id', $value);
    }

    public function solution($value)
    {
        $this->where('solution_id', $value);
    }

    public function brand($value)
    {
        $this->where('brand_id', $value);
    }

    public function supplierBrand($value)
    {
        $this->where('supplier_brand_id', $value);
    }

    public function department($value)
    {
        $this->where('department_id', $value);
    }

    public function supplierDepartment($value)
    {
        $this->where('supplier_department_id', $value);
    }

    public function subcategory($value)
    {
        $this->where('subcategory_id', $value);
    }

    public function family($value)
    {
        $this->where('family_id', $value);
    }



}
