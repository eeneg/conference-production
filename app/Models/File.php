<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Searchable;
use App\Models\Storage;
use App\Models\PdfContent;
use App\Models\Category;
use App\Models\FileCategory;


class File extends Model
{
    use HasFactory, HasUuids, Searchable;

    protected $fillable = ['title', 'file_name', 'storage_id', 'category_id', 'path', 'details', 'date'];

    public function storage(){
        return $this->belongsTo(Storage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function pdfContent() : MorphOne
    {
        return $this->morphOne(PdfContent::class, 'contentable');
    }

    #[SearchUsingFullText(['details, content'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->getKey(),
            'storage_id' => $this->storage_id,
            'category_id' => $this->category_id,
            'file_name' => $this->file_name,
            'details' => $this->details,
            'date' => $this->date,
            'content' => $this->pdfContent->content ?? ''
        ];
    }
}
