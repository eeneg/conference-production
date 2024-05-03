<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ManualAttendance extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name'];

    public function conference(){
        return $this->belongsTo(Conference::class);
    }
}
