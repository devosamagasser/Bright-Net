<?php
namespace App\Modules\Departments\Domain\Models;


use Illuminate\Database\Eloquent\Model;

class DepartmentTranslation extends Model
{
    /**
     * Attributes allowed for mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'locale',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => 'string',
        'locale' => 'string',
    ];

}
