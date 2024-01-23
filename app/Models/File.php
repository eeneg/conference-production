<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Searchable;


class File extends Model
{
    use HasFactory, HasUuids, Searchable;

    protected $fillable = ['file_name', 'path', 'details'];
}
