<?php

namespace App\Modules\Families\Domain\Models;

use App\Models\Supplier;
use Spatie\MediaLibrary\HasMedia;
use App\Models\SupplierDepartment;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\DataSheets\Domain\Models\DataTemplate;
use App\Modules\Families\Domain\Models\FamilyFieldValue;
use App\Modules\Subcategories\Domain\Models\Subcategory;

class Family extends Model implements HasMedia
{
    use InteractsWithMedia, Translatable;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'subcategory_id',
        'supplier_id',
        'data_template_id',
        'supplier_department_id',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'subcategory_id' => 'integer',
        'supplier_id' => 'integer',
        'data_template_id' => 'integer',
    ];

    /**
     * @var array<int, string>
     */
    public $translatedAttributes = [
        'description',
    ];

    /**
     * @var array<int, string>
     */
    protected $with = [
        'translations',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image');
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(SupplierDepartment::class, 'supplier_department_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function dataTemplate(): BelongsTo
    {
        return $this->belongsTo(DataTemplate::class);
    }

    public function fieldValues(): HasMany
    {
        return $this->hasMany(FamilyFieldValue::class);
    }
}
