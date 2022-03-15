<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\AssetPdf;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\Archive;
use App\Models\Asset;
use App\Models\Accessory;
use App\Models\Property;
use App\Models\Location;
use App\Models\AssetModel;

class ArchiveController extends Controller {

    public function index()
    {
        if(auth()->user()->cant('viewAll', Archive::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to View Archives.');

        }
        $locations = auth()->user()->locations;
        $location_ids = $locations->pluck('id');
        $archives = Archive::whereIn('location_id', $location_ids)->get();

        $title = "Archived/Disposed";

        return view('archives.view', compact('archives', 'locations', 'title'));
    }

    public function assets()
    {
        if(auth()->user()->cant('viewAll', Asset::class))
        {
            return ErrorController::forbidden(to_route('assets.index'), 'Unauthorised to View Assets.');

        }

        $locations = auth()->user()->locations;
        $location_ids = $locations->pluck('id');
        $archives = Archive::whereIn('location_id', $location_ids)->whereModelType('asset')->get();

        $title = "Archived Assets";

        return view('archives.view', compact('archives', 'locations', 'title'));
    }

    public function accessories()
    {
        if(auth()->user()->cant('viewAll', Accessory::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to View Accessories.');

        }

        $locations = auth()->user()->locations;
        $location_ids = $locations->pluck('id');
        $archives = Archive::whereIn('location_id', $location_ids)->whereModelType('accessory')->get();

        $title = "Archived Accessories";

        return view('archives.view', compact('archives', 'locations', 'title'));
    }

    public function show(Archive $archive)
    {
        if(auth()->user()->cant('view', $archive))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to Show Archives.');
        }

        return view('archives.show', compact('archive'));
    }

    public function destroy(Archive $archive)
    {
        if(auth()->user()->cant('delete', $archive))
        {
            return ErrorController::forbidden(to_route('archives.index'), 'Unauthorised to Delete Archives.');

        }

        $archive->delete();
        session()->flash('danger_message', "The Archived Item was removed from the system successfully");

        return back();
    }

    public function downloadPDF(Request $request)
    {
        if(auth()->user()->cant('viewAll', Asset::class))
        {
            return ErrorController::forbidden(to_route('archives.index'), 'Unauthorised to Download Archives.');

        }
        $assets = array();
        $found = Asset::select('name', 'id', 'asset_tag', 'serial_no', 'purchased_date', 'purchased_cost', 'warranty', 'audit_date', 'location_id', 'asset_model')->withTrashed()->whereIn('id', json_decode($request->assets))->with('supplier', 'location', 'model')->get();
        foreach($found as $f)
        {
            $array = array();
            $array['name'] = $f->name ?? 'No Name';
            $array['model'] = $f->model->name ?? 'N/A';
            $array['location'] = $f->location->name ?? 'Unallocated';
            $array['icon'] = $f->location->icon ?? '#666';
            $array['asset_tag'] = $f->asset_tag ?? 'N/A';
            if($f->model()->exists())
            {
                $array['manufacturer'] = $f->model->manufacturer->name ?? 'N/A';
            } else
            {
                $array['manufacturer'] = 'N/A';
            }
            $array['purchased_date'] = \Carbon\Carbon::parse($f->purchased_date)->format('d/m/Y') ?? 'N/A';
            $array['purchased_cost'] = '£' . $f->purchased_cost;
            $array['supplier'] = $f->supplier->name ?? 'N/A';
            $array['warranty'] = $f->warranty ?? 'N/A';
            $array['audit'] = \Carbon\Carbon::parse($f->audit_date)->format('d/m/Y') ?? 'N/A';
            $assets[] = $array;
        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'assets-' . $date;

        AssetsPdf::dispatch($assets, $user, $path)->afterResponse();

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('assets.index')
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();

    }

    public function downloadShowPDF(Asset $asset)
    {
        if(auth()->user()->cant('view', $asset))
        {
            return ErrorController::forbidden(to_route('archives.index'), 'Unauthorised to Download Archives.');

        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = "asset-{$asset->asset_tag}-{$date}";
        AssetPdf::dispatch($asset, $user, $path)->afterResponse();
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('assets.show', $asset->id)
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    public function restoreArchive(Archive $archive)
    {
        $options = json_decode($archive->options);

        switch($archive->model_type){
            case 'asset':
                $model = new Asset;
                $asset_model = AssetModel::where('name', '=', $archive->asset_model)->first();
                $date = \Carbon\Carbon::parse('01 September');
                $date->isPast() ? $date->addYear() : $date;
                $model->fill([
                    'asset_model' => $asset_model->id,
                    'name' => $archive->name,
                    'asset_tag' => $archive->asset_tag,
                    'serial_no' =>$archive->serial_no,
                    'purchased_date' => $archive->purchased_date,
                    'purchased_cost' => $archive->purchased_cost,
                    'donated' => 0,
                    'status_id' => 0,
                    'supplier_id' => $archive->supplier_id,
                    'order_no' => $archive->model_no,
                    'warranty' => $archive->warranty,
                    'location_id' => $archive->location_id,
                    'room' => $archive->room,
                    'audit_date' => $date,
                ]);
                break;
            case 'accessory':
                $model = new Accessory;

                //Options
                
                $options->depreciation ? $depreciation = $options->depreciation : $depreciation = 0;  
                $options->type ? $type = $options->type : $type = 1;  

                $model->fill([
                    'name' => $archive->name,
                    'asset_tag' => $archive->asset_tag,
                    'serial_no' =>$archive->serial_no,
                    'purchased_date' => $archive->purchased_date,
                    'purchased_cost' => $archive->purchased_cost,
                    'donated' => 0,
                    'status_id' => 0,
                    'supplier_id' => $archive->supplier_id,
                    'order_no' => $archive->model_no,
                    'warranty' => $archive->warranty,
                    'location_id' => $archive->location_id,
                    'room' => $archive->room,
                    'audit_date' => $date,
                ]);
                break; 
            case 'property':
                $model = new Property;

                //Options
                
                $options->depreciation_id ? $depreciation_id = $options->depreciation_id : $depreciation = 0;  
                $options->type ? $type = $options->type : $type = 1;  

                $model->fill([
                    'name' => $archive->name,
                    'purchased_date' => $archive->purchased_date,
                    'purchased_cost' => $archive->purchased_cost,
                    'depreciation' => $depreciation,
                    'type' => $type,
                    'location_id' => $archive->location_id,
                ]);
                break;
            case 'auc':
                $model = new AUC;
                $model->fill([
                    'name' => $archive->name,
                    'purchased_date' => $archive->purchased_date,
                    'purchased_cost' => $archive->purchased_cost,
                    'depreciation' => $depreciation,
                    'type' => $type,
                    'location_id' => $archive->location_id,
                ]);
                break;
        }

        if($model->save())
        {
            /* Foreach the Comments stored in the JSON Object within the Archive */
            if($archive->comments != null)
            {
                $comments = json_decode($archive->comments);
                foreach($comments as $comment)
                {
                    $model->comment()->create((array)$comment);
                }
            }
            $model->comment()->create(['title' => 'Archived Asset has been restored', 'comment' => 'The Archived Asset has been restored by ' . auth()->user()->name, 'user_id' => auth()->user()->id]);
            $archive->delete();
        }

        return back()->with('success_message', "The ".ucfirst($archive->model_type)." - ".$archive->model_name." has been successfully restored.");
    }

}
