<?php

namespace App\Modules\Companies\Domain\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory, InteractsWithMedia;

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'type',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => CompanyType::class,
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }

    public function supplier(): HasOne
    {
        return $this->hasOne(Supplier::class);
    }
}
