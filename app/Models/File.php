<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\File as FileFacade;
use App\Models\Storage;
use App\Models\PdfContent;
use App\Models\Category;
use App\Models\FileCategory;
use App\Models\Attachment;


class File extends Model
{
    use HasFactory, HasUuids, Searchable;

    protected $fillable = ['title', 'file_name', 'storage_id', 'category_id', 'path', 'details', 'date', 'latest', 'for_review'];

    protected $casts = ['tags' => 'array'];

    protected $appends = [
        'is_pdf',
        'is_image',
        'is_audio',
        'is_video',
        'is_media',
        'is_previewable',
        'is_text_previewable',
        'mime',
    ];

    public function storage(){
        return $this->belongsTo(Storage::class);
    }

    public function attachment(){
        return $this->hasOne(Attachment::class);
    }

    public function category() : BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'file_categories', 'file_id', 'category_id')->using(FileCategory::class);
    }

    public function pdfContent() : MorphOne
    {
        return $this->morphOne(PdfContent::class, 'contentable');
    }

    public function fileComment(){
        return $this->hasMany(FileComment::class);
    }

    #[SearchUsingFullText(['details, content, category'])]
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->getKey(),
            'title' => $this->title,
            'file_name' => $this->file_name,
            'details' => $this->details,
            'date' => $this->date,
            'content' => $this->pdfContent->content ?? '',
        ];
    }

    public function mime(): Attribute
    {
        return Attribute::make(
            fn () => FileFacade::mimeType(storage_path('app/' . $this->path))
        )->shouldCache();
    }

    public function isPdf(): Attribute
    {
        return Attribute::make(fn () => $this->mime === 'application/pdf')->shouldCache();
    }

    public function isImage(): Attribute
    {
        return Attribute::make(fn () => str($this->mime)->startsWith('image'))->shouldCache();
    }

    public function isAudio(): Attribute
    {
        return Attribute::make(fn () => str($this->mime)->startsWith('audio'))->shouldCache();
    }

    public function isVideo(): Attribute
    {
        return Attribute::make(fn () => str($this->mime)->startsWith('video'))->shouldCache();
    }

    public function isMedia(): Attribute
    {
        return Attribute::make(fn () => $this->is_audio || $this->is_video || $this->is_image)->shouldCache();
    }

    public function isText(): Attribute
    {
        return Attribute::make(fn () => str($this->mime)->startsWith('text'))->shouldCache();
    }

    public function isTextPreviewable(): Attribute
    {
        return Attribute::make(
            fn () => $this->is_text || in_array($this->mime, [
                'application/xml',
                'application/x-yaml',
                'application/javascript',
                'application/x-javascript',
                'application/json',
            ])
        )->shouldCache();
    }

    public function isPreviewable(): ?Attribute
    {
        return Attribute::make(
            fn () => in_array($this->mime, [
                'application/pdf',
                'audio/mpeg',
                'audio/ogg',
                'audio/wav',
                'audio/aac',
                'audio/flac',
                'video/mp4',
                'video/webm',
                'video/ogg',
                'video/x-msvideo',
                'video/quicktime',
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/bmp',
                'image/svg+xml',
                'application/xml',
                'application/x-yaml',
                'application/javascript',
                'application/x-javascript',
                'application/json',
                'text/plain',
                'text/x-shellscript',
                'text/rtf',
                'text/css',
                'text/javascript',
                'text/json',
                'text/xml',
                'text/html',
                'text/csv',
                'text/sgml',
                'text/yaml',
                'text/markdown',
                'text/vcard',
            ])
        )->shouldCache();
    }
}
