<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'asset_tag', 'asset_model', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'donated', 'supplier_id', 'order_no', 'warranty', 'location_id', 'room', 'user_id', 'audit_date', 'notes'];

    //dates

    public function location()
    {
        return $this->belongsTo(Location::class)->with('photo');
    }
    public function comment()
    {
        return $this->morphToMany(Comment::class, "commentables");
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class)->with('photo');
    }

    public function user()
    {
        return $this->belongsTo(User::class);

    }

    public function model(){
        return $this->belongsTo(AssetModel::class, 'asset_model', 'id')->with('manufacturer')->with('depreciation');
    }

    public function fields()
    {
        return $this->belongsToMany(Field::class)->withPivot('value');
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }

    public function category(){
        return $this->morphToMany(Category::class, 'cattable');
    }

    public function scopeLocationFilter($query, $locations){
        return $query->whereIn('location_id', $locations);
    }

    public function scopeSearchFilter($query, $search){
        return $query->where('assets.name', 'LIKE', "%{$search}%")
                    ->orWhere('assets.asset_tag', 'LIKE', "%{$search}%")
                    ->orWhere('assets.serial_no', 'LIKE', "%{$search}%");
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
                $query->where('audit_date', '<', \Carbon\Carbon::now()->toDateString());
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
    public function scopeAssetFilter($query , array $filters){
            $query->when($filters['asset_tag'] ?? false , fn($query ,$asset_tag) =>
            $query->where('asset_tag','like','%' . $asset_tag. '%')
                ->orWhere('name','like','%' . $asset_tag. '%')
                ->orWhere('serial_no','like','%' . $asset_tag. '%')
                ->orWhere('order_no','like','%' . $asset_tag. '%'));

    }

    public function logs(){
        return $this->morphMany(Log::class, 'loggable');
    }
    public function depreciation_value()
    {

            $eol = Carbon::parse($this->purchased_date)->addYears($this->depreciation());
            if($eol->isPast()){
                return 0;
            }else{
                $age = Carbon::now()->floatDiffInYears($this->purchased_date);
                $percent = 100 / $this->model->depreciation->years;
                $percentage = floor($age)*$percent;
                $dep = $this->purchased_cost * ((100 - $percentage) / 100);
                return $dep;
            }

    }
    public function depreciation(){
        return $this->model->depreciation->years ?? 0;
    }
}
