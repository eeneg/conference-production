<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ramsey\Uuid\Uuid;

class Role extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['title'];


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

}
