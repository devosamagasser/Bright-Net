<?php
namespace App\Modules\Shared\Support\Traits;


use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

trait HandleMedia
{
    public function syncMedia(Model $model, array $media, bool $isUpdate = false): void
    {
        foreach ($media as $collectionName => $items) {
            if ($items === []) {
                continue;
            }
            $uploadedFiles = $items['files'] ?? [];
            $localUrls     = $items['urls'] ?? [];

            if ($isUpdate) {
                $this->syncRemovedMedia($model, $localUrls, $collectionName);
            } else{
                $this->attachLocalMedia($model, $localUrls, $collectionName);
            }

            $this->attachUploadedFiles($model, $uploadedFiles, $collectionName);
        }
    }

    private function syncRemovedMedia(Model $model, array $urls, string $collection): void
    {
        $keepFileNames = collect($urls)
            ->map(fn ($url) => basename($url))
            ->unique();

        $toKeep = $model->media()
            ->where('collection_name', $collection)
            ->whereIn('file_name', $keepFileNames)
            ->get();

        $model->clearMediaCollectionExcept($collection, excludedMedia: $toKeep);
    }

    private function attachLocalMedia(Model $model, array $urls, string $collection): void
    {

        $existingMedia = $model->media()
            ->where('collection_name', $collection)
            ->get()
            ->keyBy('file_name');

        collect($urls)
            ->map(fn ($url) => $this->resolveLocalStoragePath($url))
            ->filter()
            ->each(function ($path) use ($model, $collection, $existingMedia) {
                if ($existingMedia->has(basename($path))) {
                    return;
                }

                $model->addMedia($path)
                    ->preservingOriginal()
                    ->toMediaCollection($collection);
            });
    }

    private function attachUploadedFiles(Model $model, array $files, string $collection): void
    {
        foreach ($files as $file) {
            $model->addMedia($file)
                ->toMediaCollection($collection);
        }
    }

    private function resolveLocalStoragePath(string $url): ?string
    {
        $appUrl = rtrim(config('app.url'), '/');

        if (!Str::startsWith($url, $appUrl . '/storage/')) {
            return null;
        }

        $relativePath = Str::after(
            parse_url($url, PHP_URL_PATH),
            '/storage/'
        );

        return Storage::disk('public')->exists($relativePath)
            ? Storage::disk('public')->path($relativePath)
            : null;
    }
}
