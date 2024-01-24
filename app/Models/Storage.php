<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use App\Models\File;

class Storage extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['storage_id', 'title', 'location', 'details'];

    public function files(){
        return $this->hasMany(File::class);
    }


}
