<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\User;

class Message extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['message'];

    public function users(){
        return $this->belongsTo(User::class);
    }
}
