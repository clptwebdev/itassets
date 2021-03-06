<?php

namespace App\Http\Controllers;

use App\Models\AssetModel;
use App\Models\Depreciation;
use App\Models\Fieldset;
use App\Models\Manufacturer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Storage;
use App\Jobs\AssetModelsPdf;
use App\Jobs\AssetModelPdf;
use App\Models\Report;

class AssetModelController extends Controller {

    public function index()
    {
        if(auth()->user()->cant('viewAny', AssetModel::class))
        {
            return ErrorController::forbidden(route('dashboard'), 'Unauthorised to View Asset-Models.');

        }

        return view('asset-models.view');
    }

    public function create()
    {
        if(auth()->user()->cant('create', AssetModel::class))
        {
            return ErrorController::forbidden(route('asset-models.index'), 'Unauthorised to Create Asset-Models.');

        }
        $mans = Manufacturer::all();
        $fieldsets = Fieldset::all();
        $depreciation = Depreciation::all();

        return view('asset-models.create', compact('fieldsets', 'mans', 'depreciation'));
    }

    public function store(Request $request)
    {
        if(auth()->user()->cant('create', AssetModel::class))
        {
            return ErrorController::forbidden(route('asset-models.index'), 'Unauthorised to Create Asset-Models.');

        }
        $validated = $request->validate([
            'name' => 'required|max:255',
            'model_no' => 'required',
        ]);

        AssetModel::create($request->only('name', 'manufacturer_id', 'model_no', 'depreciation_id', 'eol', 'fieldset_id', 'notes', 'photo_id'));
        session()->flash('success_message', $request->name . ' has been created successfully');

        return to_route('asset-models.index');
    }

    public function show(AssetModel $assetModel)
    {
        if(auth()->user()->cant('view', $assetModel))
        {
            return ErrorController::forbidden(route('asset-models.index'), 'Unauthorised to View Asset-Models.');

        }

        return view('asset-models.show', compact('assetModel'));
    }

    public function edit(AssetModel $assetModel)
    {
        if(auth()->user()->cant('update', $assetModel))
        {
            return ErrorController::forbidden(route('asset-models.index'), 'Unauthorised to Edit Asset-Models.');

        }
        $depreciation = Depreciation::all();
        $mans = Manufacturer::all();
        $fieldsets = Fieldset::all();

        return view('asset-models.edit', compact('fieldsets', 'mans', 'assetModel', 'depreciation'));
    }

    public function update(Request $request, AssetModel $assetModel)
    {
        if(auth()->user()->cant('update', $assetModel))
        {
            return ErrorController::forbidden(route('asset-models.index'), 'Unauthorised to Update Asset-Models.');

        }
        $validated = $request->validate([
            'name' => 'required|max:255',
            'model_no' => 'required',
        ]);

        $assetModel->fill($request->only('name', 'manufacturer_id', 'model_no', 'depreciation_id', 'eol', 'fieldset_id', 'notes', 'photo_id'))->save();
        session()->flash('success_message', $request->name . ' has been updated successfully');

        return to_route('asset-models.index');
    }

    public function destroy(AssetModel $assetModel)
    {
        if(auth()->user()->cant('delete', $assetModel))
        {
            return ErrorController::forbidden(route('asset-models.index'), 'Unauthorised to Delete Asset-Models.');

        }
        $name = $assetModel->name;
        $assetModel->delete();
        session()->flash('danger_message', $name . ' was deleted from the system');

        return to_route('asset-models.index');
    }

    public function downloadPDF()
    {
        if(auth()->user()->cant('viewAny', AssetModel::class))
        {
            return ErrorController::forbidden(route('asset-models.index'), 'Unauthorised to Download Asset-Models.');

        }
        $models = array();
        $found = AssetModel::all();

        foreach($found as $f)
        {
            $array = array();
            $array['name'] = $f->name;
            $array['manufacturer'] = $f->manufacturer->name ?? 'N/A';
            $array['model_no'] = $f->model_no ?? 'N/A';
            $array['depreciation'] = $f->depreciation->name ?? 'No Depreciation';
            $array['assets'] = $f->assets->count();
            $array['notes'] = $f->notes ?? 'N/A';
            $models[] = $array;
        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'asset-models-' . $date;

        AssetModelsPdf::dispatch($models, $user, $path)->afterResponse();

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('asset-models.index')
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();


    }

    public function downloadShowPDF(AssetModel $assetModel)
    {
        if(auth()->user()->cant('view', $assetModel))
        {
            return ErrorController::forbidden(route('asset-models.index'), 'Unauthorised to Download Asset-Models.');

        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = str_replace(' ', '-', $assetModel->name) . "-{$date}";
        AssetModelPdf::dispatch($assetModel, $user, $path)->afterResponse();
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('asset-models.show', $assetModel)
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    public function search(Request $request)
    {
        $models = AssetModel::where('name', 'LIKE', '%' . $request->search . "%")->take(3)->get()->unique('name');
        $output = "<ul id='modelSelect' class='list-group'>";
        foreach($models as $model)
        {
            $output .= " <li class='list-group-item d-flex justify-content-between align-items-center pointer' data-id='" . $model->id . "' data-name='" . $model->name . "'>
                            {$model->name}
                            <span class='badge badge-primary badge-pill'>1</span>
                        </li>";
        }
        $output .= "</ul>";

        return Response($output);
    }

    public function preview(Request $request)
    {
        if($model = AssetModel::find($request->id))
        {
            if($model->photo()->exists() && $src = asset($model->photo->path))
            {

            } else
            {
                $src = asset('images/svg/device-image.svg');
            }
            $output = " <div class='model_title text-center h4 mb-3'>Asset Model</div>
                        <div class='model_image p-4'>
                            <img id='profileImage' src='{$src}' width='100%'
                                alt='Select Profile Picture' onclick='getPhotoPage(1)'>
                        </div>
                        <div class='model_no py-2 px-4'>
                            Manufacturer: {$model->manufacturer->name}
                        </div>
                        <div class='model_no py-2 px-4'>
                            Model No: {$model->model_no}
                        </div>
                        <div class='model_no py-2 px-4'>
                            Depreciation: {$model->depreciation->name} ({$model->depreciation->years} months)
                        </div>
                        <div class='model_no py-2 px-4'>
                            Additional Fieldsets: {$model->fieldset->name}
                        </div>";

            return $output;
        }
    }

    public function ajaxCreate(Request $request)
    {
        if($model = AssetModel::create($request->only('name', 'manufacturer_id', 'model_no', 'depreciation_id', 'eol', 'fieldset_id', 'notes')))
        {
            return $model->id;
        } else
        {
            return false;
        }
    }

}
