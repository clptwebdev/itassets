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

class AssetModelController extends Controller
{
    public function index()
    {
        if (auth()->user()->cant('viewAny', AssetModel::class)) {
            return redirect(route('errors.forbidden', ['area', 'Asset Model', 'View All']));
        }
        return view('asset-models.view');
    }

    public function create()
    {
        if (auth()->user()->cant('create', AssetModel::class)) {
            return redirect(route('errors.forbidden', ['area', 'Asset', 'create']));
        }
        $mans = Manufacturer::all();
        $fieldsets = Fieldset::all();
        $depreciation = Depreciation::all();
        return view('asset-models.create', compact('fieldsets', 'mans', 'depreciation'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->cant('create', AssetModel::class)) {
            return redirect(route('errors.forbidden', ['area', 'Asset', 'create']));
        }
        $validated = $request->validate([
            'name'=>'required|max:255',
            'model_no'=>'required',
        ]);

        AssetModel::create($request->only('name', 'manufacturer_id', 'model_no', 'depreciation_id', 'eol', 'fieldset_id', 'notes', 'photo_id'));
        session()->flash('success_message', $request->name.' has been created successfully');
        return redirect(route('asset-models.index'));
    }

    public function show(AssetModel $assetModel)
    {
        if (auth()->user()->cant('view', $assetModel)) {
            return redirect(route('errors.forbidden', ['area', 'Asset', 'view']));
        }
        return view('asset-models.show', compact('assetModel'));
    }

    public function edit(AssetModel $assetModel)
    {
        if (auth()->user()->cant('update', $assetModel)) {
            return redirect(route('errors.forbidden', ['area', 'Asset', 'edit']));
        }
        $depreciation = Depreciation::all();
        $mans = Manufacturer::all();
        $fieldsets = Fieldset::all();
        return view('asset-models.edit', compact('fieldsets', 'mans', 'assetModel', 'depreciation'));
    }

    public function update(Request $request, AssetModel $assetModel)
    {
        if (auth()->user()->cant('update', $assetModel)) {
            return redirect(route('errors.forbidden', ['area', 'Asset', 'edit']));
        }
        $validated = $request->validate([
            'name'=>'required|max:255',
            'model_no'=>'required',
        ]);

        $assetModel->fill($request->only('name', 'manufacturer_id', 'model_no', 'depreciation_id', 'eol', 'fieldset_id', 'notes', 'photo_id'))->save();
        session()->flash('success_message', $request->name.' has been updated successfully');
        return redirect(route('asset-models.index'));
    }

    public function destroy(AssetModel $assetModel)
    {
        if (auth()->user()->cant('delete', $assetModel)) {
            return redirect(route('errors.forbidden', ['area', 'Asset', 'delete']));
        }
        $name = $assetModel->name;
        $assetModel->delete();
        session()->flash('danger_message', $name.' was deleted from the system');
        return redirect(route('asset-models.index'));
    }

    public function downloadPDF()
    {
        if (auth()->user()->cant('viewAny', AssetModel::class)) {
            return redirect(route('errors.forbidden', ['area', 'Asset Model', 'Download PDF']));
        }
        $models = AssetModel::all();
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('asset-models.pdf', compact('models'));
        $pdf->setPaper('a4', 'landscape');
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        Storage::put("public/reports/models-{$date}.pdf", $pdf->output());
        $url = asset("storage/reports/models-{$date}.pdf");
        return redirect(route('asset-models.index'))
            ->with('success_message', "Your Report has been created successfully. Click Here to <a href='{$url}'>Download PDF</a>")
            ->withInput();
    }

    public function downloadShowPDF(AssetModel $assetModel)
    {
        if (auth()->user()->cant('view', $assetModel)) {
            return redirect(route('errors.forbidden', ['assetModel', $assetModel->id, 'Download PDF']));
        }
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('asset-models.showPdf', compact('assetModel'));

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        Storage::put("public/reports/model-{$assetModel->id}-{$date}.pdf", $pdf->output());
        $url = asset("storage/reports/model-{$assetModel->id}-{$date}.pdf");
        return redirect(route('asset-models.show', $assetModel->id))
            ->with('success_message', "Your Report has been created successfully. Click Here to <a href='{$url}'>Download PDF</a>")
            ->withInput();
    }
}
