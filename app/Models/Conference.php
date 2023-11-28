<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Searchable;

class Conference extends Model
{
    use HasFactory, HasUuids, Searchable;

    protected $fillable = ['title', 'agenda', 'date', 'attachments', 'status'];

    public static function fileHandle($files, $id){

        $attachments = [];

        foreach($files as $e){
            $category = $e['category'];
            $category_order = $e['category_order'];

            foreach($e['files'] as $key => $file){
                array_push($attachments,
                    [
                        'category'          => $category,
                        'category_order'    => $category_order,
                        'file_name'         => $file['file']->getClientOriginalName(),
                        'path'              => str_replace(' ', '_',$id . '/' . $category . '/' . $file['file']->getClientOriginalName()),
                        'details'           => $file['file_details'],
                        'storage_location'  => $file['storage_location'],
                        'file_order'        => $file['file_order'],
                        'storage_location'  => $file['storage_location'],
                    ]
                );
                if(is_file($file['file'])){
                    Storage::putFileAs('public/' . $id . '/' . str_replace(' ', '_', $category), $file['file'], str_replace(' ','_',$file['file']->getClientOriginalName()));
                }
            }
        };

        return $attachments;

    }

    public function minute() : HasOne
    {
        return $this->hasOne(Minutes::class);
    }

    public function attachment() : HasMany
    {
        return $this->hasMany(Attachment::class);
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
