<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Searchable;

class Conference extends Model
{
    use HasFactory, HasUuids, Searchable;

    protected $fillable = ['title', 'agenda', 'date', 'attachments', 'status'];

    protected $with = ['attachment'];

    public static function fileHandle($files, $title, $conf_id){

        $ar = [];

        $attachments = collect($files)->map(function($file, $key) use ($title, $conf_id){

            $ar[$key] = $file[0];

            return [
                'category' => $file[0],
                'files' => array_key_exists(1, $file) ?
                collect($file[1])->map(function($e) use ($title, $file) {

                    Storage::putFileAs('public/' . $title . '/' . $file[0], $e, $e->getClientOriginalName());

                    if(is_file($e)){
                        return ['path' => $title . '/' . $file[0], 'fileName' => $e->getClientOriginalName()];
                    }

                })
                : []
            ];

        });

        return $attachments;

    }

    public function minute() : HasOne
    {
        return $this->hasOne(Minutes::class);
    }

    public function attachment() : HasOne
    {
        return $this->hasOne(Attachment::class);
    }

    public function scopePending(Builder $query) : void
    {
        $query->whereStatus('pending');
    }

    public function scopeCompleted(Builder $query) : void
    {
        $query->whereStatus('completed');
    }

    #[SearchUsingFullText(['title', 'agenda'])]
    public function toSearchableArray() : array
    {
        return [
            'id' => $this->getKey(),
            'title' => $this->title,
            'agenda' => $this->agenda,
        ];
    }

}
