<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Conference extends Model
{
    use HasFactory, HasUuids;

    protected $casts = ['attachments' => 'array'];

    protected $fillable = ['title', 'agenda', 'date', 'attachments'];

}
