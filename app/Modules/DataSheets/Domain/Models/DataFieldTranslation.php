<?php

namespace App\Modules\DataSheets\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class DataFieldTranslation extends Model
{
    /**
     * @var string
     */
    protected $table = 'data_field_translations';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'label',
        'placeholder',
        'locale',
    ];
}
