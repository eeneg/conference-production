<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected $fillable = ['title', 'agenda', 'date', 'attachments', 'status', 'video_conf_url'];

    public function poll() : HasMany {
        return $this->hasMany(Poll::class);
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

    public function agendaMarkdown(): Attribute
    {
        return Attribute::make(
            fn () => str($this->agenda)->markdown()
        )->shouldCache();
    }

}
