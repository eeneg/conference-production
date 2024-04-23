<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class FileComment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['comment', 'user_id'];

    public function file(){
        return $this->belongsTo(File::class);
    }
}
