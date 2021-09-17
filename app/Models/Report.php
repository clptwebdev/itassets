<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'report', 'user_id', 'created_at'];


    public function user(){
        return $this->belongsTo(User::class);
    }

}