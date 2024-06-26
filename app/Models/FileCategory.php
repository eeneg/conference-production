<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FileCategory extends Pivot
{
    use HasFactory, HasUuids;

    protected $table = 'file_categories';

    protected $fillable = ['file_id', 'category_id'];
}
