<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['asset_tag', 'asset_model', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'supplier_id', 'order_no', 'warranty', 'location_id', 'user_id', 'audit_date'];

    //dates

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);

    }

    public function model(){
        return $this->belongsTo(AssetModel::class, 'asset_model', 'id');
    }

    public function fields()
    {
        return $this->belongsToMany(Field::class)->withPivot('value');
    }

    public function status(){
        return $this->hasOne(Status::class);
    }

    public function category(){
        return $this->morphToMany(Category::class, 'cattable');
    }

    public function scopeLocationFilter($query, $locations){
        return $query->whereIn('location_id', $locations);
    }

    public function scopeStatusFilter($query, $status){
        return $query->whereIn('status_id', $status);
    }

    public function scopeCategoryFilter($query, $category){
        $pivot = $this->category()->getTable();

        $query->whereHas('category', function ($q) use ($category, $pivot) {
            $q->whereIn("{$pivot}.category_id", $category);
        });
    }

    public function scopePurchaseFilter($query, $start, $end){
        $query->whereBetween('purchased_date', [$start, $end]);
    }

    public function scopeAuditFilter($query, $date){
        switch($date){
            case 1:
                $query->whereAuditDate('audit_date', '<', \Carbon\Carbon::now()->toDateString());
                break;
            case 2:
                $date = \Carbon\Carbon::now()->addDays(30);
                $query->whereBetween('audit_date', [\Carbon\Carbon::now(), $date]);
                break;
            case 3:
                $date = \Carbon\Carbon::now()->addMonths(3);
                $query->whereBetween('audit_date', [\Carbon\Carbon::now(), $date]);
                break;
            case 4:
                $date = \Carbon\Carbon::now()->addMonths(6);
                $query->whereBetween('audit_date', [\Carbon\Carbon::now(), $date]);
                break;
        }
    }

    public function scopeCostFilter($query, $amount){
        $amount = str_replace('£', '', $amount);
        $amount = explode(' - ', $amount);
        $query->whereBetween('purchased_cost', [intval($amount[0]), intval($amount[1])]);
    }

    public function logs(){
        return $this->morphMany(Log::class, 'loggable');
    }

}
