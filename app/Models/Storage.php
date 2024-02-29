<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use App\Models\File;
use App\Modesl\Attachment;

class Storage extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['storage_id', 'title', 'location', 'details'];

    public function attachment(){
        return $this->hasMany(Attachment::class);
    }

    public function files(){
        return $this->hasMany(File::class);
    }
}
