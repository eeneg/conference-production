<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['title', 'type', 'agree_count', 'disagree_count', 'undecided_count', 'result', 'details', 'concluded', 'active'];

    public function conference() : BelongsTo{
        return $this->belongsTo(Conference::class);
    }
}
