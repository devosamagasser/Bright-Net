<?php
namespace App\Modules\Families\Domain\Models;


use Astrotomic\Translatable\Translatable;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Family extends Model implements HasMedia
{

    use InteractsWithMedia, Translatable;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }


}
