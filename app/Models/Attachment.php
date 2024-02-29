<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\File;
use Laravel\Scout\Searchable;
use App\Models\PdfContent;
use App\Models\Storage;

class Attachment extends Model
{
    use HasFactory, HasUuids, Searchable;

    protected $fillable = ['conference_id', 'files', 'category', 'file_name', 'path', 'details', 'category_order', 'file_order', 'storage_id'];

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

    public function conference() : BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public function pdfContent() : MorphOne
    {
        return $this->morphOne(PdfContent::class, 'contentable');
    }

    public function storage(){
        return $this->belongsTo(Storage::class, 'storage_id');
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

    public function mime(): Attribute
    {
        return Attribute::make(
            fn () => File::mimeType(storage_path('app/public/' . $this->path))
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

    public function getContent()
    {
        return File::get(storage_path('app/public/' . $this->path));
    }
}
