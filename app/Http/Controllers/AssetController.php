<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Fieldset;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Supplier;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Exports\AssetExport;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class AssetController extends Controller
{

    public function index()
    {
        return view('assets.view', [
            "assets"=>Asset::all(),
        ]);
    }

    public function create()
    {
        return view('assets.create', [
            "locations"=>Location::all(),
            "manufacturers"=>Manufacturer::all(),
            'models'=>AssetModel::all(),
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
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
        return view('assets.edit', [
            "asset"=>$asset,
            "locations"=>Location::all(),
            "manufacturers"=>Manufacturer::all(),
            'models'=>AssetModel::all(),
            'suppliers' => Supplier::all(),
        ]);
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
        $name=$asset->asset_tag;
        $asset->delete();
        session()->flash('danger_message', "#". $name . ' was deleted from the system');
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
        return \Maatwebsite\Excel\Facades\Excel::download(new AssetExport, 'invoices.xlsx');

    }

}
