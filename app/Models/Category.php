<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Category extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['title', 'details', 'type'];

    public function files(){
        return $this->hasMany(File::class);
    }
}
