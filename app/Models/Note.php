<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Note extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'notes';

    protected $fillable = ['conference_id', 'note'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
