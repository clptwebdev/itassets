<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Archive;
use App\Models\Asset;
use App\Models\Accessory;
use App\Models\Location;
use App\Models\AssetModel;

class ArchiveController extends Controller
{
    
    public function index(){
        if(auth()->user()->role_id == 1){
            $archives = Archive::all();
            $locations = Location::all();
        }else{
            $locations = auth()->user()->locations;
            $location_ids = $locations->pluck('id');
            $archives = Archive::whereIn('location_id', $location_ids)->get();
        }

        $title = "Archived/Disposed";
        return view('archives.view', compact('archives', 'locations', 'title'));
    }

    public function assets(){
        if (auth()->user()->cant('viewAll', Asset::class)) {
            return redirect(route('errors.forbidden', ['area', 'Assets', 'view']));
        }

        if(auth()->user()->role_id == 1){
            $archives = Archive::whereModelType('asset')->get();
            $locations = Location::all();
        }else{
            $locations = auth()->user()->locations;
            $location_ids = $locations->pluck('id');
            $archives = Archive::whereIn('location_id', $location_ids)->whereModelType('asset')->get();
        }

        $title = "Archived Assets";

        return view('archives.view', compact('archives', 'locations', 'title'));
    }

    public function accessories(){
        if (auth()->user()->cant('viewAll', Accessory::class)) {
            return redirect(route('errors.forbidden', ['area', 'Assets', 'view']));
        }

        if(auth()->user()->role_id == 1){
            $archives = Archive::whereModelType('accessory')->get();
            $locations = Location::all();
        }else{
            $locations = auth()->user()->locations;
            $location_ids = $locations->pluck('id');
            $archives = Archive::whereIn('location_id', $location_ids)->whereModelType('accessory')->get();
        }

        $title = "Archived Accessories";

        return view('archives.view', compact('archives', 'locations', 'title')); 
    }

    public function show(Archive $archive){
        if (auth()->user()->cant('view', $archive)) {
            return redirect(route('errors.forbidden', ['archives', $archive->id, 'view']));
        }

        return view('archives.show', compact('archive'));
    }

    public function destroy(Archive $archive){
        if (auth()->user()->cant('delete', $archive)) {
            return redirect(route('errors.forbidden', ['archives', $archive->id, 'delete']));
        }

        $archive->delete();
        session()->flash('danger_message', "The Archived Item was removed from the system successfully");
        return back();
    }

    public function downloadPDF(Request $request)
    {
        if (auth()->user()->cant('viewAll', Asset::class)) {
            return redirect(route('errors.forbidden', ['area', 'Asset', 'View PDF']));
        }
        $assets = array();
        $found = Asset::select('name','id','asset_tag','serial_no','purchased_date','purchased_cost','warranty','audit_date', 'location_id', 'asset_model')->withTrashed()->whereIn('id', json_decode($request->assets))->with('supplier','location','model')->get();
        foreach($found as $f){
            $array = array();
            $array['name'] = $f->name ?? 'No Name';
            $array['model'] = $f->model->name ?? 'N/A';
            $array['location'] = $f->location->name ?? 'Unallocated';
            $array['icon'] = $f->location->icon ?? '#666';
            $array['asset_tag'] = $f->asset_tag ?? 'N/A';
            if($f->model()->exists()){
                $array['manufacturer'] = $f->model->manufacturer->name ?? 'N/A';
            }else{
                $array['manufacturer'] = 'N/A';
            }
            $array['purchased_date'] = \Carbon\Carbon::parse($f->purchased_date)->format('d/m/Y') ?? 'N/A';
            $array['purchased_cost'] = '£'.$f->purchased_cost;
            $array['supplier'] = $f->supplier->name ?? 'N/A';
            $array['warranty'] = $f->warranty ?? 'N/A';
            $array['audit'] = \Carbon\Carbon::parse($f->audit_date)->format('d/m/Y') ?? 'N/A';
            $assets[] = $array;
        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'assets-'.$date;

        AssetsPdf::dispatch( $assets, $user, $path )->afterResponse();

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report'=> $url, 'user_id'=> $user->id]);
        return redirect(route('assets.index'))
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();

    }

    public function downloadShowPDF(Asset $asset)
    {
        if (auth()->user()->cant('view', $asset)) {
            return redirect(route('errors.forbidden', ['asset', $asset->id, 'View PDF']));
        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = "asset-{$asset->asset_tag}-{$date}";
        AssetPdf::dispatch( $asset,$user,$path )->afterResponse();
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report'=> $url, 'user_id'=> $user->id]);

        return redirect(route('assets.show', $asset->id))
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    public function restoreArchive(Archive $archive)
    {
        if($archive->model_type === 'asset'){
            $model = new Asset;
            $asset_model = AssetModel::where('name', '=', $archive->asset_model)->first();
            $model->asset_model = $asset_model->id ?? 0;
        }else{
            $model = new Accessory;
        }

        $model->name = $archive->name;
        $model->asset_tag = $archive->asset_tag;
        $model->serial_no = $archive->serial_no;
        $model->purchased_date = $archive->purchased_date;
        $model->purchased_cost = $archive->purchased_cost;
        $model->donated = 0;
        $model->status_id = 0;
        $model->supplier_id = $archive->supplier_id;
        $model->order_no = $archive->model_no;
        $model->warranty = $archive->warranty;
        $model->location_id = $archive->location_id;
        $model->room = $archive->room;
        $date = \Carbon\Carbon::parse('01 September');
        $date->isPast() ? $date->addYear() : $date;
        $model->audit_date = $date;

        

        if($model->save()){
            /* Foreach the Comments stored in the JSON Object within the Archive */
            if($archive->comments != null){
                $comments = json_decode($archive->comments);
                foreach($comments as $comment){
                    $model->comment()->create((array) $comment);
                }
            }
            $model->comment()->create(['title' => 'Archived Asset has been restored', 'comment' => 'The Archived Asset has been restored by '.auth()->user()->name, 'user_id' => auth()->user()->id]);
            $archive->delete();
        }

        return back()->with('success_message', $message ?? 'The Asset has been successfully restored. You will now be able to view it in Assets');
    }

}
