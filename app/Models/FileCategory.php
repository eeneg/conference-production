<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class FileCategory extends Pivot
{
    use HasFactory, HasUuids;

    protected $table = "file_categories";

    protected $fillable = ['category_id', 'file_id'];
}
