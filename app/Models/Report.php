<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Report extends Model {

    use HasFactory;

    protected $fillable = ['id', 'report', 'user_id', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function clean()
    {
        $table = \DB::table('reports')->get();
        foreach($table as $entry)
        {
            $entry->delete();
        }
        $files = Storage::files('/public/reports');
        Storage::delete($files);

        return true;
    }

}
