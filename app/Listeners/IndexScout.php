<?php

namespace App\Listeners;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Laravel\Scout\Searchable;

class IndexScout
{
    /**
     * Handle the event.
     */
    public function handle(): void
    {
        Artisan::call('scout:delete-all-indexes');

        collect(File::allFiles(app()->basePath().'/app/Models'))
            ->filter(fn ($file) => substr($file, -4) === '.php')
            ->map(fn ($file) => substr($file->getRelativePathName(), 0, -4))
            ->map(fn ($file) => str_replace('/', '\\', $file))
            ->map(fn ($file) => app(app()->getNamespace().'Models\\'.$file))
            ->filter(fn ($model) => $model instanceof Model)
            ->filter(fn ($model) => in_array(Searchable::class, class_uses($model)))
            ->each(fn (Model $model) => Artisan::call('scout:index', ['name' => $model::class]))
            ->each(fn (Model $model) => Artisan::call('scout:import', ['model' => $model::class]));

        Artisan::call('scout:sync-index-settings');
    }
}
