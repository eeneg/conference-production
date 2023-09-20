<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    use HasFactory, HasUuids;

    protected $casts = ['files' => 'array'];

    protected $fillable = ['conference_id', 'files', 'category', 'file_name', 'path', 'details', 'storage_location', 'category_order', 'file_order'];

    public function conference() : BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }
}
