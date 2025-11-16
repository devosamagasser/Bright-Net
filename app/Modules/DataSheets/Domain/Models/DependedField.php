<?php
namespace App\Modules\DataSheets\Domain\Models;


use Illuminate\Database\Eloquent\Model;

class DependedField extends Model
{
    protected $fillable = [
        'data_field_id',
        'depends_on_field_id',
        'values',
    ];
    protected $casts = [
        'values' => 'array',
    ];
    public function dataField()
    {
        return $this->belongsTo(DataField::class, 'data_field_id');
    }
    public function dependsOnField()
    {
        return $this->belongsTo(DataField::class, 'depends_on_field_id');
    }
}
