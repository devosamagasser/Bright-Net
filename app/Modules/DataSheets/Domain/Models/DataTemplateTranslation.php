<?php

namespace App\Modules\DataSheets\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class DataTemplateTranslation extends Model
{
    /**
     * @var string
     */
    protected $table = 'data_template_translations';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'locale',
    ];
}
