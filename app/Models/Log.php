<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \App\Models\User;
use \App\Models\Asset;
use \App\Models\Component;

class Log extends Model {

    use HasFactory;

    protected $fillable = ['user_id', 'loggable_type', 'loggable_id', 'data', 'created_at', 'read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function component()
    {
        return $this->belongsTo(Component::class);
    }

    public function miscellanea()
    {
        return $this->belongsTo(Miscellanea::class);
    }

    public function scopeLogFilter($query, $search)
    {
        return $query->where('logs.data', 'LIKE', "%{$search}%");
    }

    public function scopeLogTypeFilter($query, $type)
    {
        return $query->where('loggable_type', '=', $type);

    }

}
