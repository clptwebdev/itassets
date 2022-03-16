<?php

namespace App\Http\Controllers;

use App\Exports\assetErrorsExport;
use App\Exports\AssetDisposalErrors;
use App\Exports\AssetTransferErrors;
use App\Imports\AssetImport;
use App\Imports\AssetDispose;
use App\Imports\AssetTransfer;
use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Depreciation;
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
use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\Storage;
use App\Jobs\AssetsPdf;
use App\Jobs\AssetPdf;
use App\Models\Report;

use App\Rules\checkAssetTag;

class AssetController extends Controller {

    public function index()
    {
        if(auth()->user()->cant('viewAll', Asset::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to View Assets.');

        }
        $assets = Asset::locationFilter(auth()->user()->locations->pluck('id'))
            ->leftJoin('locations', 'locations.id', '=', 'assets.location_id')
            ->leftJoin('asset_models', 'assets.asset_model', '=', 'asset_models.id')
            ->leftJoin('manufacturers', 'manufacturers.id', '=', 'asset_models.manufacturer_id')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'assets.supplier_id')
            ->orderBy(session('orderby') ?? 'purchased_date', session('direction') ?? 'asc')
            ->select('assets.*', 'asset_models.name as asset_model_name', 'locations.name as location_name', 'manufacturers.name as manufacturer_name', 'suppliers.name as supplier_name');
        $locations = Location::whereIn('id', auth()->user()->locations->pluck('id'))->select('id', 'name')->withCount('assets')->get();
        $categories = Category::with('assets')->select('id', 'name')->get();
        $statuses = Status::select('id', 'name', 'deployable')->withCount('assets')->get();
//            $locations = Location::whereIn('location_id', auth()->user()->locations)->select('id', 'name', 'deployable')->withCount('assets')->get();

        $this->clearFilter();
        session(['assets_filter' => false]);
        $limit = session('assets_limit') ?? 25;

        return view('assets.view', [
            "assets" => $assets->paginate(intval($limit))->fragment('table'),
            'suppliers' => Supplier::all(),
            'statuses' => $statuses,
            'categories' => $categories,
            "locations" => $locations,
        ]);
    }

    public function create()
    {
        if(auth()->user()->cant('create', Asset::class))
        {
            return ErrorController::forbidden(to_route('assets.index'), 'Unauthorised to Create Assets.');
        }
        $locations = auth()->user()->locations;

        return view('assets.create', [
            "locations" => $locations,
            "manufacturers" => Manufacturer::select('id', 'name')->get(),
            'models' => AssetModel::all(),
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            'mans' => Manufacturer::all(),
            'fieldsets' => Fieldset::all(),
            'depreciation' => Depreciation::all(),
        ]);
    }

    public function search()
    {
        return view("assets.show", [
            'asset' => Asset::latest()->AssetFilter(request()->only(['asset_tag']))->firstOrFail(),
            'locations' => Location::all(),
        ]);
    }

    public function newComment(Request $request)
    {
        $request->validate([
            "title" => "required|max:255",
            "comment" => "nullable",
        ]);

        $asset = Asset::find($request->asset_id);
        $asset->comment()->create(['title' => $request->title, 'comment' => $request->comment, 'user_id' => auth()->user()->id]);
        session()->flash('success_message', $request->title . ' has been created successfully');

        return to_route('assets.show', $asset->id);
    }

    public function store(Request $request)
    {
        if(auth()->user()->cant('create', Asset::class))
        {
            return ErrorController::forbidden(to_route('assets.index'), 'Unauthorised to create Assets.');
        }

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
                    } else
                    {
                        $val_string .= "nullable";
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
                            case('string'):
                                $val_string .= "string";
                                break;
                            default:
                                $val_string .= "string";
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
                    if($values != null)
                    {
                        $array[$field->id] = ['value' => $values];
                    }
                }
            }
        }

        if(! empty($validate_fieldet))
        {
            $v = array_merge($validate_fieldet, [
                'name' => 'required',
                'asset_tag' => ['sometimes', 'nullable', new checkAssetTag($request['location_id'])],
                'serial_no' => 'required',
                'purchased_date' => 'required|date',
                'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'warranty' => 'required|numeric',
            ]);
        } else
        {
            $v = [
                'name' => 'required',
                'asset_tag' => ['sometimes', 'nullable', new checkAssetTag($request['location_id'])],
                'serial_no' => 'required',
                'purchased_date' => 'required|date',
                'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'warranty' => 'required|numeric',
            ];
        }

        $validated = $request->validate($v);

        $asset = Asset::create(array_merge($request->only(
            'name', 'asset_tag', 'asset_model', 'serial_no', 'location_id', 'room', 'purchased_date', 'purchased_cost', 'donated', 'supplier_id', 'order_no', 'warranty', 'status_id', 'audit_date'
        ), ['user_id' => auth()->user()->id]));

        if(! empty($array))
        {
            $asset->fields()->attach($array);
        }
        if(! empty(explode(',', $request->category)))
        {
            $asset->category()->attach(explode(',', $request->category));
        }
        session()->flash('success_message', $request->name . ' has been created successfully');

        return to_route('assets.index');

    }

    public function show(Asset $asset)
    {
        if(auth()->user()->cant('view', $asset))
        {
            return ErrorController::forbidden(to_route('assets.index'), 'Unauthorised to Show Assets.');

        }
        $locations = auth()->user()->locations;

        return view('assets.show', [
            "asset" => $asset,
            "locations" => $locations,
        ]);
    }

    public function edit(Asset $asset)
    {

        if(auth()->user()->cant('update', $asset))
        {
            return ErrorController::forbidden(to_route('assets.index'), 'Unauthorised to Edit Assets.');

        }
        $locations = auth()->user()->locations;

        return view('assets.edit', [
            "asset" => $asset,
            "locations" => $locations,
            "manufacturers" => Manufacturer::all(),
            'models' => AssetModel::all(),
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            'mans' => Manufacturer::all(),
            'fieldsets' => Fieldset::all(),
            'depreciation' => Depreciation::all(),
        ]);

    }

    public function update(Request $request, Asset $asset)
    {
        if(auth()->user()->cant('update', $asset))
        {
            return ErrorController::forbidden(to_route('assets.index'), 'Unauthorised to Update Assets.');

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
                } else
                {
                    $val_string .= "nullable";
                }

                if($field->type == 'text')
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
                        case('string'):
                            $val_string .= "string";
                            break;
                        default:
                            $val_string .= "string";
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
                if($values != null)
                {
                    $array[$field->id] = ['value' => $values];
                }

            }
        }

        if(! empty($validate_fieldet))
        {
            $v = array_merge($validate_fieldet, [
                'name' => 'required',
                'asset_tag' => ['sometimes', 'nullable'],
                'serial_no' => 'required',
                'purchased_date' => 'required|date',
                'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'warranty' => 'required|numeric',
            ]);
        } else
        {
            $v = [
                'name' => 'required',
                'asset_tag' => ['sometimes', 'nullable'],
                'serial_no' => 'required',
                'purchased_date' => 'required|date',
                'purchased_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'warranty' => 'required|numeric',
            ];
        }

        $validated = $request->validate($v);

        if(isset($request->donated) && $request->donated == 1)
        {
            $donated = 1;
        } else
        {
            $donated = 0;
        }

        $asset->fill(array_merge($request->only(
            'name', 'asset_tag', 'asset_model', 'serial_no', 'room', 'purchased_date', 'purchased_cost', 'supplier_id', 'order_no', 'warranty', 'status_id', 'audit_date'
        ), ['user_id' => auth()->user()->id, 'donated' => $donated]))->save();

        if(! empty($array))
        {
            $asset->fields()->sync($array);
        }
        if(! empty($request->category))
        {
            $asset->category()->sync(explode(',', $request->category));
        }
        session()->flash('success_message', $request->name . ' has been updated successfully');

        return to_route('assets.index');
    }

    public function destroy(Asset $asset)
    {

        if(auth()->user()->cant('delete', $asset))
        {
            return ErrorController::forbidden(to_route('assets.index'), 'Unauthorised to Archive Assets.');
        }

        $name = $asset->asset_tag;

        $asset->delete();
        session()->flash('danger_message', "#" . $name . ' was sent to the Recycle Bin');

        return to_route('assets.index');
    }

    public function restore($id)
    {
        $asset = Asset::withTrashed()->where('id', $id)->first();
        if(auth()->user()->cant('delete', $asset))
        {
            return ErrorController::forbidden(to_route('assets.index'), 'Unauthorised to Restore Assets.');
        }
        $name = $asset->asset_tag;
        $asset->restore();
        session()->flash('success_message', "#" . $name . ' has been restored.');

        return to_route('assets.index');
    }

    public function forceDelete($id)
    {
        $asset = Asset::withTrashed()->where('id', $id)->first();
        if(auth()->user()->cant('delete', $asset))
        {
            return ErrorController::forbidden(to_route('assets.index'), 'Unauthorised to Delete Assets.');
        }
        $name = $asset->name;
        $asset->forceDelete();
        session()->flash('danger_message', "#" . $name . ' was deleted permanently');

        return to_route('assets.bin');
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

    public function export(Request $request)
    {
        if(auth()->user()->cant('viewAll', Asset::class))
        {
            return ErrorController::forbidden(to_route('assets.index'), 'Unauthorised to Export Assets.');

        }
        $assets = Asset::withTrashed()->whereIn('id', json_decode($request->assets))->with('supplier', 'location', 'model', 'status', 'user')->get();
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        \Maatwebsite\Excel\Facades\Excel::store(new AssetExport($assets), "/public/csv/assets-ex-{$date}.xlsx");
        $url = asset("storage/csv/assets-ex-{$date}.xlsx");

        return to_route('assets.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();

    }

    public function import(Request $request)
    {
        if(auth()->user()->cant('create', Asset::class))
        {
            return ErrorController::forbidden(to_route('assets.index'), 'Unauthorised to Import Assets.');

        }

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

                return to_route('assets.index')->with('success_message', 'All Assets were added correctly!');

            }

        } else
        {
            return to_route('assets.index')->with('danger_message', 'Sorry! This File type is not allowed Please try a ".CSV!"');

        }

    }

    public function bulkDisposal(Request $request)
    {
        /* Check whether the current user has permissions to bulk dispose Assets [SC] */
        /* Currently - Super Admin permissions requried [SC] */
        if(auth()->user()->cant('disposeAll', Asset::class))
        {
            return ErrorController::forbidden(to_route('assets.index'), 'Unauthorised to Bulk Dispose Assets.');

        }

        /* Accepted File Extensions */
        $extensions = array("csv");

        /* Files passed through the Request */
        $result = array($request->file('csv')->getClientOriginalExtension());

        /* If the function accepts the File Extension */
        if(in_array($result[0], $extensions))
        {
            $path = $request->file("csv")->getRealPath();
            $import = new AssetDispose;
            $import->import($path, null, \Maatwebsite\Excel\Excel::CSV);
            $row = [];
            $attributes = [];
            $errors = [];
            $values = [];
            $results = $import->failures();
            $importErrors = [];
            foreach($results->all() as $result)
            {
                /* Looping through each row to check for errors on the AssetDispose Class */
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

            /* If there are Errors return from AssetDispose */
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

                return view('assets.dispose-errors', [
                    "errorArray" => $errorArray,
                    "valueArray" => $valueArray,
                    "errorValues" => $errorValues,
                ]);

            } else
            {

                return to_route('assets.index')->with('success_message', 'All Assets were disposed correctly!');

            }

        } else
        {
            return to_route('assets.index')->with('danger_message', 'Sorry! This File type is not allowed Please try a ".CSV!"');

        }
    }

    public function bulkTransfers(Request $request)
    {
        if(auth()->user()->cant('transferAll', Asset::class))
        {
            return ErrorController::forbidden(to_route('assets.index'), 'Unauthorised to Bulk transfer Assets.');
        }

        /* Accepted File Extensions */
        $extensions = array("csv");

        /* Files passed through the Request */
        $result = array($request->file('csv')->getClientOriginalExtension());

        /* If the function accepts the File Extension */
        if(in_array($result[0], $extensions))
        {
            $path = $request->file("csv")->getRealPath();
            $import = new AssetTransfer;
            $import->import($path, null, \Maatwebsite\Excel\Excel::CSV);
            $row = [];
            $attributes = [];
            $errors = [];
            $values = [];
            $results = $import->failures();
            $importErrors = [];
            foreach($results->all() as $result)
            {
                /* Looping through each row to check for errors on the AssetDispose Class */
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

            /* If there are Errors return from AssetDispose */
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

                return view('assets.transfer-errors', [
                    "errorArray" => $errorArray,
                    "valueArray" => $valueArray,
                    "errorValues" => $errorValues,
                ]);

            } else
            {

                return to_route('assets.index')->with('success_message', 'All Assets were disposed correctly!');

            }

        } else
        {
            return to_route('assets.index')->with('danger_message', 'Sorry! This File type is not allowed Please try a ".CSV!"');

        }
    }

    public function exportDisposeErrors(Request $request)
    {
        if(auth()->user()->cant('viewAll', Asset::class))
        {
            return ErrorController::forbidden(to_route('assets.index'), 'Unauthorised to Export Assets.');

        }
        //Receives the JSON Object with the errors and passes them to the Export function for Maatwebsite
        $export = $request['assets'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');

        return \Maatwebsite\Excel\Facades\Excel::download(new AssetDisposalErrors($export), "dispose-errors-{$date}.csv");
    }

    public function exportTransferErrors(Request $request)
    {
        if(auth()->user()->cant('viewAll', Asset::class))
        {
            return ErrorController::forbidden(to_route('assets.index'), 'Unauthorised to Export Assets.');

        }
        //Receives the JSON Object with the errors and passes them to the Export function for Maatwebsite
        $export = $request['assets'];
        $code = (htmlspecialchars_decode($export));
        $export = json_decode($code);
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');

        return \Maatwebsite\Excel\Facades\Excel::download(new AssetTransferErrors($export), "dispose-errors-{$date}.csv");
    }

    public function importErrors(Request $request)
    {
        if(auth()->user()->cant('viewAll', Asset::class))
        {
            return ErrorController::forbidden(to_route('assets.index'), 'Unauthorised to Export Assets.');

        }
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
                'name.*' => 'required|string',
                'serial_no.*' => 'required',
                'warranty.*' => 'int',
                'purchased_date.*' => 'nullable|date',
                'purchased_cost.*' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'asset_tag.*' => 'sometimes|nullable',
                'status_id.*' => 'string|nullable',
                'audit_date.*' => 'date|nullable',
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
                    $asset->name = $request->name[$i];
                    $asset->user_id = auth()->user()->id;
                    $asset->serial_no = $request->serial_no[$i];
                    $asset->status_id = $request->status_id[$i];

                    $asset->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $request->purchased_date[$i]))->format("Y-m-d");
                    $asset->purchased_cost = $request->purchased_cost[$i];
                    $asset->donated = $request->donated[$i];

                    $asset->supplier_id = $request->supplier_id[$i];
                    $asset->order_no = $request->order_no[$i];
                    $asset->warranty = $request->warranty[$i];

                    $asset->location_id = $request->location_id[$i];
                    $asset->room = $request->room;
                    $asset->asset_model = $request->asset_model[$i];

                    $asset->save();
                }
                session()->flash('success_message', 'You can successfully added the Assets');

                return 'Success';
            }
        }
    }

    public function filter(Request $request)
    {
        if($request->isMethod('post'))
        {

            if(! empty($request->search))
            {
                session(['assets_search' => $request->search]);
            }

            if(! empty($request->limit))
            {
                session(['assets_limit' => $request->limit]);
            }

            if(! empty($request->orderby))
            {
                $array = explode(' ', $request->orderby);

                session(['assets_orderby' => $array[0]]);
                session(['assets_direction' => $array[1]]);

            }

            if(! empty($request->locations))
            {
                session(['assets_locations' => $request->locations]);
            }

            if(! empty($request->status))
            {
                session(['assets_status' => $request->status]);
            }

            if(! empty($request->category))
            {
                session(['assets_category' => $request->category]);
            }

            if($request->start != '' && $request->end != '')
            {
                session(['assets_start' => $request->start]);
                session(['assets_end' => $request->end]);
            }

            if($request->audit != 0)
            {
                session(['assets_audit' => $request->audit]);
            }

            if($request->warranty != 0)
            {
                session(['assets_warranty' => $request->warranty]);
            }

            session(['assets_amount' => $request->amount]);
        }

        $locations = auth()->user()->locations->pluck('id');
        $locs = auth()->user()->locations()->withCount('assets')->get();
        $assets = Asset::locationFilter($locations);

        if(session()->has('assets_locations'))
        {
            $assets->locationFilter(session('assets_locations'));
            session(['assets_filter' => true]);
        }
        if(session()->has('assets_status'))
        {
            $assets->statusFilter(session('assets_status'));
            session(['assets_filter' => true]);
        }
        if(session()->has('assets_category'))
        {
            $assets->categoryFilter(session('assets_category'));
            session(['assets_filter' => true]);
        }
        if(session()->has('assets_start') && session()->has('assets_end'))
        {
            $assets->purchaseFilter(session('assets_start'), session('assets_end'));
            session(['assets_filter' => true]);
        }
        if(session()->has('assets_audit') && session('assets_audit') != 0)
        {
            $assets->auditFilter(session('assets_audit'));
            session(['assets_filter' => true]);
        }
        if(session()->has('assets_warranty'))
        {
            $assets->warrantyFilter(session('assets_warranty'));
            session(['assets_filter' => true]);
        }
        if(session()->has('assets_amount'))
        {
            $assets->costFilter(session('assets_amount'));
            session(['assets_filter' => true]);
        }

        if(session()->has('assets_search'))
        {
            $assets->searchFilter(session('assets_search'));
            session(['assets_filter' => true]);
        }

        $assets->leftJoin('locations', 'assets.location_id', '=', 'locations.id')
            ->leftJoin('asset_models', 'assets.asset_model', '=', 'asset_models.id')
            ->leftJoin('manufacturers', 'manufacturers.id', '=', 'asset_models.manufacturer_id')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'assets.supplier_id')
            ->orderBy(session('assets_orderby') ?? 'purchased_date', session('assets_direction') ?? 'asc')
            ->select('assets.*', 'asset_models.name as asset_model_name', 'locations.name as location_name', 'manufacturers.name as manufacturer_name', 'suppliers.name as supplier_name');
        $limit = session('assets_limit') ?? 25;

        return view('assets.view', [
            "assets" => $assets->paginate(intval($limit))->withPath(asset('/asset/filter'))->fragment('table'),
            'suppliers' => Supplier::withCount('assets')->get(),
            'statuses' => Status::withCount('assets')->get(),
            'categories' => Category::withCount('assets')->get(),
            "locations" => $locs,
        ]);
    }

    public function clearFilter()
    {
        session()->forget(['assets_locations', 'assets_status', 'assets_category', 'assets_start', 'assets_end', 'assets_audit', 'assets_warranty', 'assets_amount', 'assets_search']);

        return to_route('assets.index');
    }

    public function status(Status $status)
    {
        $filter = 1;
        $this->clearFilter();
        $array = [];
        $array[] = $status->id;

        session(['status' => $array]);

        $locations = auth()->user()->locations->pluck('id');
        $assets = Asset::locationFilter($locations);

        $assets->statusFilter($array);

        $assets->leftJoin('locations', 'assets.location_id', '=', 'locations.id')
            ->leftJoin('asset_models', 'assets.asset_model', '=', 'asset_models.id')
            ->leftJoin('manufacturers', 'manufacturers.id', '=', 'asset_models.manufacturer_id')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'assets.supplier_id')
            ->orderBy(session('orderby') ?? 'purchased_date', session('direction') ?? 'asc')
            ->select('assets.*', 'asset_models.name as asset_model_name', 'locations.name as location_name', 'manufacturers.name as manufacturer_name', 'suppliers.name as supplier_name');
        $limit = session('limit') ?? 25;

        return view('assets.view', [
            "assets" => $assets->paginate(intval($limit))->withPath(asset('/asset/filter'))->fragment('table'),
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            "locations" => auth()->user()->locations,
            "filter" => $filter,
        ]);
    }

    public function location(Location $location)
    {

        $filter = 1;
        $this->clearFilter();

        $array = [];
        $array[] = $location->id;

        $locations = auth()->user()->locations->pluck('id');

        session(['locations' => $array]);
        $assets = Asset::locationFilter($locations->toArray());

        $assets->locationFilter([$location->id]);

        $assets->leftJoin('locations', 'assets.location_id', '=', 'locations.id')
            ->leftJoin('asset_models', 'assets.asset_model', '=', 'asset_models.id')
            ->leftJoin('manufacturers', 'manufacturers.id', '=', 'asset_models.manufacturer_id')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'assets.supplier_id')
            ->orderBy(session('orderby') ?? 'purchased_date', session('direction') ?? 'asc')
            ->select('assets.*', 'asset_models.name as asset_model_name', 'locations.name as location_name', 'manufacturers.name as manufacturer_name', 'suppliers.name as supplier_name');
        $limit = session('limit') ?? 25;

        return view('assets.view', [
            "assets" => $assets->paginate(intval($limit))->withPath(asset('/asset/filter'))->fragment('table'),
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            "locations" => auth()->user()->locations,
            "filter" => $filter,
        ]);
    }


    ////////////////////////////////////////////////////////
    ///////////////PDF Functions////////////////////////////
    ////////////////////////////////////////////////////////

    public function downloadPDF(Request $request)
    {
        if(auth()->user()->cant('viewAll', Asset::class))
        {
            return ErrorController::forbidden(to_route('assets.index'), 'Unauthorised to Download Assets.');

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
            if($f->model()->exists() && $f->model->depreciation()->exists())
            {
                $eol = \Carbon\Carbon::parse($f->purchased_date)->addYears($f->model->eol);
                if($eol->isPast())
                {
                    $dep = 0;
                } else
                {
                    $age = \Carbon\Carbon::now()->floatDiffInYears($f->purchased_date);
                    $percent = 100 / $f->model->depreciation->years;
                    $percentage = floor($age) * $percent;
                    $dep = $f->purchased_cost * ((100 - $percentage) / 100);
                }
            }
            $array['depreciation'] = $dep;
            $array['donated'] = $f->donated;
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
            return ErrorController::forbidden(to_route('assets.index'), 'Unauthorised to Download Assets.');

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

    public function recycleBin()
    {
        if(auth()->user()->cant('viewAll', Asset::class))
        {
            return ErrorController::forbidden(to_route('assets.index'), 'Unauthorised to Recycle Assets.');

        }

        $limit = session('assets_limit') ?? 25;

        $assets = auth()->user()->location_assets()->onlyTrashed()->paginate(intval($limit))->fragment('table');
        $locations = auth()->user()->locations;

        return view('assets.bin', [
            "assets" => $assets,
            'suppliers' => Supplier::all(),
            'statuses' => Status::all(),
            'categories' => Category::all(),
            "locations" => $locations,
        ]);
    }

    public function changeStatus(Asset $asset, Request $request)
    {
        if(auth()->user()->cant('update', $asset))
        {
            return ErrorController::forbidden(to_route('assets.show', $asset->id), 'Unauthorised to Change Statuses Asset.');
        }
        $asset->status_id = $request->status;
        $asset->save();
        session()->flash('success_message', $asset->model->name . ' has had its status changed successfully');

        return to_route('assets.show', $asset->id);
    }

}
