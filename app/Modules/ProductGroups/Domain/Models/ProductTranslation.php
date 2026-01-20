<?php
namespace App\Modules\Products\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

}
