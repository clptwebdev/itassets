<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \App\Models\User;
use \App\Models\Asset;
use \App\Models\Component;

class Log extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'loggable_type', 'loggable_id', 'data', 'created_at'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function asset(){
        return $this->belongsTo(Asset::class);
    }

    public function component(){
        return $this->belongsTo(Component::class);
    }
}