<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Fieldset;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Supplier;
use App\Models\Status;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Exports\AssetExport;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PDF;


class AssetController extends Controller
{

    public function index()
    {
        if(auth()->user()->role_id == 1){
            $assets = Asset::all();
            $locations = Location::all();
        }else{
            $assets = auth()->user()->location_assets;
            $locations = auth()->user()->locations;
        }
        return view('assets.view', [
            "assets"=> $assets,
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            "locations"=>$locations,
        ]);
    }

    public function create()
    {
        $this->authorize('create', Asset::class);
        return view('assets.create', [
            "locations"=>auth()->user()->locations,
            "manufacturers"=>Manufacturer::all(),
            'models'=>AssetModel::all(),
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Asset::class);
        $validate_fieldet = [];
        //Validate and Collect the Additional Fieldsets
        if($request->asset_model != 0){
            $assetModel = AssetModel::find($request->asset_model);
            if($assetModel->fieldset_id != 0 && $fieldset = Fieldset::findOrFail($assetModel->fieldset_id)){
                $fields = $fieldset->fields;
                $array = [];
                foreach($fields as $field){
                    $name = str_replace(' ', '_', strtolower($field->name));
                    $val_string = '';
                    if($field->required == 1){
                        $val_string .= "required";
                    }

                    if($field->type == 'Text'){
                        $val_string .= "|";
                        switch($field->format){
                            case("alpha"):
                                $val_string .= "alpha";
                                break;
                            case('alpha_num'):
                                $val_string .= "alpha_num";
                                break;
                            case('num'):
                                $val_string .= "numeric";
                                break;
                            case('date'):
                                $val_string .= "date";
                                break;
                            case('url'):
                                $val_string .= "url";
                                break;
                            default:
                                $val_string .= "alpha_num";
                                break;
                        }
                    }

                    $validate_fieldet[$name] = $val_string;

                    if(is_array($request->$name)){
                        $values = implode(',', $request->$name);
                    }else{
                        $values = $request->$name;
                    }
                    $array[$field->id] = ['value' => $values];                
                }
            }
        }

        if(!empty($validate_fieldet)){ $v = array_merge($validate_fieldet, [
            'asset_tag' => 'required',
            'serial_no' => 'required',
            'purchased_date' => 'required|date',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'warranty' => 'required|numeric',
        ]);}else{
            $v = [
            'asset_tag' => 'required',
            'serial_no' => 'required',
            'purchased_date' => 'required|date',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'warranty' => 'required|numeric',
            ];
        }

        $validated = $request->validate($v);

        $asset = Asset::create(array_merge($request->only(
            'asset_tag', 'asset_model', 'serial_no', 'location_id', 'purchased_date', 'purchased_cost', 'supplier_id', 'order_no', 'warranty', 'status_id', 'audit_date'
        ), ['user_id' => auth()->user()->id]));
        $asset->fields()->attach($array);
        $asset->category()->attach($request->category);
                
        session()->flash('success_message', $request->name.' has been created successfully');
        return redirect(route('assets.index'));

    }

    public function show(Asset $asset)
    {
        return view('assets.show', [
            "asset"=>$asset,
        ]);
    }


    public function edit(Asset $asset)
    {
        if (auth()->user()->cant('edit', $asset)) {
            return redirect(route('errors.forbidden', ['asset', $asset->id, 'edit']));
        }else{
            return view('assets.edit', [
                "asset"=>$asset,
                "locations"=>auth()->user()->locations,
                "manufacturers"=>Manufacturer::all(),
                'models'=>AssetModel::all(),
                'suppliers' => Supplier::all(),
                'statuses' => Status::all(),
            ]);
        }

        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Asset $asset)
    {
        if (auth()->user()->cant('update', $asset)) {
            return redirect(route('errors.forbidden', ['asset', $asset->id, 'update']));
        }

        $validate_fieldet = [];
        //Validate and Collect the Additional Fieldsets
        $assetModel = AssetModel::findOrFail($request->asset_model);
        if($assetModel->fieldset_id != 0 && $fieldset = Fieldset::findOrFail($assetModel->fieldset_id)){
            $fields = $fieldset->fields;
            $array = [];
            foreach($fields as $field){
                $name = str_replace(' ', '_', strtolower($field->name));
                $val_string = '';
                if($field->required == 1){
                    $val_string .= "required";
                }

                if($field->type == 'Text'){
                    $val_string .= "|";
                    switch($field->format){
                        case("alpha"):
                            $val_string .= "alpha";
                            break;
                        case('alpha_num'):
                            $val_string .= "alpha_num";
                            break;
                        case('num'):
                            $val_string .= "numeric";
                            break;
                        case('date'):
                            $val_string .= "date";
                            break;
                        case('url'):
                            $val_string .= "url";
                            break;
                        default:
                            $val_string .= "alpha_num";
                            break;
                    }
                }

                $validate_fieldet[$name] = $val_string;

                if(is_array($request->$name)){
                    $values = implode(',', $request->$name);
                }else{
                    $values = $request->$name;
                }
                $array[$field->id] = ['value' => $values];                
            }
        }
        
        if(!empty($validate_fieldet)){ $v = array_merge($validate_fieldet, [
            'asset_tag' => 'required',
            'serial_no' => 'required',
            'purchased_date' => 'required|date',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'warranty' => 'required|numeric',
        ]);}else{
            $v = [
            'asset_tag' => 'required',
            'serial_no' => 'required',
            'purchased_date' => 'required|date',
            'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'warranty' => 'required|numeric',
            ];
        }

        $validated = $request->validate($v);
        $asset->fill(array_merge($request->only(
            'asset_tag', 'asset_model', 'serial_no', 'location_id', 'purchased_date', 'purchased_cost', 'supplier_id', 'order_no', 'warranty', 'status_id', 'audit_date'
        ), ['user_id' => auth()->user()->id]))->save();
        $asset->fields()->sync($array);
        
        session()->flash('success_message', $request->name.' has been updated successfully');
        return redirect(route('assets.index'));
    }


    public function destroy(Asset $asset)
    {
        if (auth()->user()->cant('delete', $asset)) {
            return redirect(route('errors.forbidden', ['asset', $asset->id, 'edit']));
        }
        $name=$asset->asset_tag;
        $asset->delete();
        session()->flash('danger_message', "#". $name . ' was sent to the Recycle Bin');
        return redirect("/assets");
    }

    public function restore($id){
        $asset = Asset::withTrashed()->where('id', $id)->first();
        if (auth()->user()->cant('delete', $asset)) {
            return redirect(route('errors.forbidden', ['asset', $asset->id, 'edit']));
        }
        $name=$asset->asset_tag;
        $asset->restore();
        session()->flash('success_message', "#". $name . ' has been restored.');
        return redirect("/assets");
    }

    public function forceDelete($id){
        $asset = Asset::withTrashed()->where('id', $id)->first();
        if (auth()->user()->cant('delete', $asset)) {
            return redirect(route('errors.forbidden', ['asset', $asset->id, 'edit']));
        }
        $name=$asset->asset_tag;
        $asset->forceDelete();
        session()->flash('danger_message', "#". $name . ' was deleted permanently');
        return redirect("/assets");
    }

    public function model(AssetModel $model){
        if($model->fieldset_id != 0){
            $fieldset = Fieldset::findOrFail($model->fieldset_id);
            return view('assets.fields', compact('model', 'fieldset'));
        }else{
            return false;
        }
    }

    public function export(Asset $asset)
    {
       return \Maatwebsite\Excel\Facades\Excel::download(new AssetExport, 'assets.csv');
    }

    public function filter(Request $request){
        
        if(auth()->user()->role_id != 1){
            $locations = auth()->user()->locations->pluck('id');
            $locs = auth()->user()->locations;
        }else{
            $locations = \App\Models\Location::all()->pluck('id');
            $locs = \App\Models\Location::all();
        }
        $assets = Asset::locationFilter($locations);
        if(!empty($request->locations)){
            $assets->locationFilter($request->locations);
        }
        if(!empty($request->status)){
            $assets->statusFilter($request->status);
        }
        if(!empty($request->category)){
            $assets->categoryFilter($request->category);
        }
        if($request->start != '' && $request->end != ''){
            $assets->purchaseFilter($request->start, $request->end);
        }

        if($request->audit != 0){
            $assets->auditFilter($request->audit);
        }

        if($request->warranty != 0){
            $assets->warrantyFilter($request->warranty);
        }

        $assets->costFilter($request->amount);

        return view('assets.view', [
            "assets"=>$assets->get(),
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            "locations"=> $locs,
            "filter" => 'Filter',
            "amount" => $request->amount,
        ]);
    }

    public function status(Status $status){
        $array = [];
        $array[] = $status->id;
        $locations = auth()->user()->locations->pluck('id');
        $assets = Asset::locationFilter($locations);
        $assets->statusFilter($array);
        return view('assets.view', [
            "assets"=> $assets->get(),
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            "locations"=>auth()->user()->locations,
        ]);
    }

    public function location(Location $location){
        $locations = auth()->user()->locations->pluck('id');
        $assets = Asset::locationFilter($locations);
        $assets->locationFilter([$location->id]);
        return view('assets.view', [
            "assets"=> $assets->get(),
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            "locations"=>auth()->user()->locations,
        ]);
    }

    public function downloadPDF(Request $request){
        $assets = Asset::withTrashed()->whereIn('id', json_decode($request->assets))->get();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('assets.pdf', compact('assets'));
        $pdf->setPaper('a4', 'landscape');
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        return $pdf->download("assets-{$date}.pdf");
    }

    public function downloadShowPDF(Asset $asset){
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('assets.showPdf', compact('asset'));
    
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        return $pdf->download("asset-{$asset->asset_tag}-{$date}.pdf");
    }

    public function recycleBin()
    {
        if(auth()->user()->role_id == 1){
            $assets = Asset::onlyTrashed()->get();
            $locations = Location::all();
        }else{
            $assets = auth()->user()->location_assets()->onlyTrashed();
            $locations = auth()->user()->locations;
        }
        return view('assets.bin', [
            "assets"=> $assets,
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            "locations"=>$locations,
        ]);
    }

}
