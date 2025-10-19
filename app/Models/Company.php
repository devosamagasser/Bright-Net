<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Company extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory, InteractsWithMedia;

    public const TYPE_SUPPLIER = 'supplier';
    public const TYPE_CONTRACTOR = 'contractor';
    public const TYPE_CONSULTANT = 'consultant';

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => 'string',
    ];
}
