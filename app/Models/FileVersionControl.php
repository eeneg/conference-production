<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class FileVersionControl extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['control_id', 'file_id', 'version'];
}
