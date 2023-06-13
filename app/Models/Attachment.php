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

    protected $fillable = ['conference_id', 'files'];

    public function conference() : BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }
}
