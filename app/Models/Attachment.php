<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Scout\Attributes\SearchUsingFullText;
use App\Models\PdfContent;
use Laravel\Scout\Searchable;

class Attachment extends Model
{
    use HasFactory, HasUuids, Searchable;

    protected $fillable = ['conference_id', 'files', 'category', 'file_name', 'path', 'details', 'storage_location', 'category_order', 'file_order'];

    public function conference() : BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public function pdfContent() : HasOne
    {
        return $this->hasOne(PdfContent::class);
    }

    #[SearchUsingFullText(['details', 'storage_location'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->getKey(),
            'file_name' => $this->file_name,
            'details' => $this->details,
            'storage_location' => $this->storage_location,
            'content' => $this->pdfContent->content ?? ''
        ];
    }
}
