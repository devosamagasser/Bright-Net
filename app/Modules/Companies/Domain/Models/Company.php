<?php

namespace App\Modules\Companies\Domain\Models;

use App\Models\Supplier;
use App\Models\CompanyUser;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;

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
        'contact_email',
        'contact_phone',
        'website',
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

    public function users()
    {
        return $this->hasMany(CompanyUser::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function (Company $company) {
            // Automatically create a Supplier when a Company is created
            $company->users()->create([
                'name' => $company->name . ' Admin',
                'company_id' => $company->id,
                'email' => $company->contact_email,
                'password' => Hash::make($company->contact_email . '123'), 
            ]);
        });
    }
}
