<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = ['title', 'date', 'details', 'file_name', 'path'];
}
