<?php

namespace App\Http\Controllers;

use App\Exports\assetErrorsExport;
use App\Imports\AssetImport;
use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Fieldset;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Supplier;
use App\Models\Status;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Exports\AssetExport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Excel;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use phpDocumentor\Reflection\Types\String_;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PDF;

class AssetController extends Controller {

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
            "assets" => auth()->user()->location_assets,
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            "locations" => auth()->user()->locations,
        ]);
    }

    public function create()
    {
        $this->authorize('create', Asset::class);

        return view('assets.create', [
            "locations" => auth()->user()->locations,
            "manufacturers" => Manufacturer::all(),
            'models' => AssetModel::all(),
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Asset::class);
        $validate_fieldet = [];
        //Validate and Collect the Additional Fieldsets
        if($request->asset_model != 0)
        {
            $assetModel = AssetModel::find($request->asset_model);
            if($assetModel->fieldset_id != 0 && $fieldset = Fieldset::findOrFail($assetModel->fieldset_id))
            {
                $fields = $fieldset->fields;
                $array = [];
                foreach($fields as $field)
                {
                    $name = str_replace(' ', '_', strtolower($field->name));
                    $val_string = '';
                    if($field->required == 1)
                    {
                        $val_string .= "required";
                    }

                    if($field->type == 'Text')
                    {
                        $val_string .= "|";
                        switch($field->format)
                        {
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

                    if(is_array($request->$name))
                    {
                        $values = implode(',', $request->$name);
                    } else
                    {
                        $values = $request->$name;
                    }
                    $array[$field->id] = ['value' => $values];
                }
            }
        }

        if(! empty($validate_fieldet))
        {
            $v = array_merge($validate_fieldet, [
                'asset_tag' => 'required',
                'serial_no' => 'required',
                'purchased_date' => 'required|date',
                'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'warranty' => 'required|numeric',
            ]);
        } else
        {
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

        session()->flash('success_message', $request->name . ' has been created successfully');

        return redirect(route('assets.index'));

    }

    public function show(Asset $asset)
    {
        return view('assets.show', [
            "asset" => $asset,
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
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
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
        if($assetModel->fieldset_id != 0 && $fieldset = Fieldset::findOrFail($assetModel->fieldset_id))
        {
            $fields = $fieldset->fields;
            $array = [];
            foreach($fields as $field)
            {
                $name = str_replace(' ', '_', strtolower($field->name));
                $val_string = '';
                if($field->required == 1)
                {
                    $val_string .= "required";
                }

                if($field->type == 'Text')
                {
                    $val_string .= "|";
                    switch($field->format)
                    {
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

                if(is_array($request->$name))
                {
                    $values = implode(',', $request->$name);
                } else
                {
                    $values = $request->$name;
                }
                $array[$field->id] = ['value' => $values];
            }
        }

        if(! empty($validate_fieldet))
        {
            $v = array_merge($validate_fieldet, [
                'asset_tag' => 'required',
                'serial_no' => 'required',
                'purchased_date' => 'required|date',
                'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'warranty' => 'required|numeric',
            ]);
        } else
        {
            $v = [
                'asset_tag' => 'required',
                'serial_no' => 'required',
                'purchased_date' => 'required|date',
                'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'warranty' => 'required|numeric',
            ];
        }

        $this->authorize('update', $asset);

        $validated = $request->validate($v);
        $asset->fill(array_merge($request->only(
            'asset_tag', 'asset_model', 'serial_no', 'location_id', 'purchased_date', 'purchased_cost', 'supplier_id', 'order_no', 'warranty', 'status_id', 'audit_date'
        ), ['user_id' => auth()->user()->id]))->save();
        $asset->fields()->sync($array);

        session()->flash('success_message', $request->name . ' has been updated successfully');

        return redirect(route('assets.index'));
    }

    public function destroy(Asset $asset)
    {

        if (auth()->user()->cant('delete', $asset)) {
            return redirect(route('errors.forbidden', ['asset', $asset->id, 'edit']));
        }
        $name=$asset->asset_tag;

        $asset->delete();
        session()->flash('danger_message', "#" . $name . ' was deleted from the system');

        return redirect("/assets");
    }

    public function model(AssetModel $model)
    {
        if($model->fieldset_id != 0)
        {
            $fieldset = Fieldset::findOrFail($model->fieldset_id);

            return view('assets.fields', compact('model', 'fieldset'));
        } else
        {
            return false;
        }
    }


    public function export(Asset $asset)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new AssetExport(), 'assets.csv');

    }

    public function import(Request $request)
    {
        $extensions = array("csv");

        $result = array($request->file('csv')->getClientOriginalExtension());

        if(in_array($result[0], $extensions))
        {
            $path = $request->file("csv")->getRealPath();
            $import = new AssetImport;
            $import->import($path, null, \Maatwebsite\Excel\Excel::CSV);
            $row = [];
            $attributes = [];
            $errors = [];
            $values = [];
            $results = $import->failures();
            $importErrors = [];
            foreach($results->all() as $result)
            {
                $row[] = $result->row();
                $attributes[] = $result->attribute();
                $errors[] = $result->errors();
                $values[] = $result->values();
                $importErrors[] = [

                    "row" => $result->row(),
                    "attributes" => $result->attribute(),
                    "errors" => $result->errors(),
                    "value" => $result->values(),
                ];

            }

            if(! empty($importErrors))
            {
                $errorArray = [];
                $valueArray = [];
                $errorValues = [];

                foreach($importErrors as $error)
                {
                    if(array_key_exists($error['row'], $errorArray))
                    {
                        $errorArray[$error['row']] = $errorArray[$error['row']] . ',' . $error['attributes'];
                    } else
                    {
                        $errorArray[$error['row']] = $error['attributes'];
                    }
                    $valueArray[$error['row']] = $error['value'];

                    if(array_key_exists($error['row'], $errorValues))
                    {
                        $array = $errorValues[$error['row']];
                    } else
                    {
                        $array = [];
                    }

                    foreach($error['errors'] as $e)
                    {
                        $array[$error['attributes']] = $e;
                    }
                    $errorValues[$error['row']] = $array;

                }

                return view('assets.import-errors', [
                    "errorArray" => $errorArray,
                    "valueArray" => $valueArray,
                    "errorValues" => $errorValues,
                    "models" => AssetModel::all(),
                    "statuses" => Status::all(),
                    "suppliers" => Supplier::all(),
                    "locations" => Location::all(),
                ]);

            } else
            {

                return redirect('/assets')->with('success_message', 'All Assets were added correctly!');

            }
        } else
        {
            return redirect('/assets')->with('danger_message', 'Sorry! This File type is not allowed Please try a ".CSV!"');

        }
    }

    public function importErrors(Request $request)
    {
        $export = $request['asset_tag'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);
       return \Maatwebsite\Excel\Facades\Excel::download(new assetErrorsExport($export), 'AssetImportErrors.csv');
    }

    public function ajaxMany(Request $request)
    {
        if($request->ajax())
        {
            $validation = Validator::make($request->all(), [
                'order_no.*' => 'nullable',
                'serial_no.*' => 'required',
                'warranty.*' => 'int',
                'purchased_date.*' => 'nullable|date',
                'purchased_cost.*' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'asset_tag.*' => 'required', Rule::unique('assets'),
                'status_id.*' => 'string|nullable',
                'audit_date.*' => 'string|nullable',
                'supplier_id.*' => 'string',
                'location_id.*' => 'string',
                'asset_model.*' => 'nullable',
            ]);

            if($validation->fails())
            {
                return $validation->errors();
            } else
            {
                for($i = 0; $i < count($request->asset_tag); $i++)
                {
                    $asset = new Asset;

                    $asset->asset_tag = $request->asset_tag[$i];
                    $asset->user_id = auth()->user()->id;
                    $asset->serial_no = $request->serial_no[$i];
                    $asset->status_id = $request->status_id[$i];

                    $asset->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $request->purchased_date[$i]))->format("Y-m-d");
                    $asset->purchased_cost = $request->purchased_cost[$i];

                    $asset->supplier_id = $request->supplier_id[$i];
                    $asset->order_no = $request->order_no[$i];
                    $asset->warranty = $request->warranty[$i];

                    $asset->location_id = $request->location_id[$i];
                    $asset->asset_model = $request->asset_model[$i];

                    $asset->save();
                }
                session()->flash('success_message', 'You can successfully added the Assets');

                return 'Success';
            }
        }
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
        if(! empty($request->locations))
        {
            $assets->locationFilter($request->locations);
        }
        if(! empty($request->status))
        {
            $assets->statusFilter($request->status);
        }
        if(! empty($request->category))
        {
            $assets->categoryFilter($request->category);
        }
        if($request->start != '' && $request->end != '')
        {
            $assets->purchaseFilter($request->start, $request->end);
        }

        if($request->audit != 0)
        {
            $assets->auditFilter($request->audit);
        }

        if($request->warranty != 0)
        {
            $assets->warrantyFilter($request->warranty);
        }

        $assets->costFilter($request->amount);

        return view('assets.view', [
            "assets" => $assets->get(),
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            "locations"=> $locs,
            "filter" => 'Filter',
            "amount" => $request->amount,
        ]);
    }

    public function status(Status $status)
    {
        $array = [];
        $array[] = $status->id;
        $locations = auth()->user()->locations->pluck('id');
        $assets = Asset::locationFilter($locations);
        $assets->statusFilter($array);

        return view('assets.view', [
            "assets" => $assets->get(),
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            "locations" => auth()->user()->locations,
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
        $assets = Asset::findMany(json_decode($request->assets));
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

}
