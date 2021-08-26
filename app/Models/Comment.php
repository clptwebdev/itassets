<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

    use HasFactory;

    protected $fillable = ['title', "comment", "type", "user_id"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function component()
    {
        return $this->morphedByMany(Component::class, 'commentables');
    }

    public function accessory()
    {
        return $this->morphedByMany(Accessory::class, 'commentables');
    }

    public function consumable()
    {
        return $this->morphedByMany(Consumable::class, 'commentables');
    }

    public function asset()
    {
        return $this->morphedByMany(Asset::class, 'commentables');
    }

}