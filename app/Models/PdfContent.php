<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdfContent extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['attachment_id', 'content'];

    public function attachment() : BelongsTo
    {
        return $this->belongsTo(Attachment::class);
    }
}
