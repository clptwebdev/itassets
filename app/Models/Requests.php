<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'model_type', 'model_id', 'location_from', 'location_to', 'notes', 'user_id', 'super_id', 'date', 'created_at', 'status'];

}
