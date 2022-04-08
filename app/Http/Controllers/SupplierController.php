<?php

namespace App\Http\Controllers;

use App\Exports\SupplierExport;
use App\Models\Supplier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SuppliersPdf;
use App\Jobs\SupplierPdf;
use App\Models\Report;

class SupplierController extends Controller {

    public function index()
    {
        if(auth()->user()->cant('view', Supplier::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to View Suppliers.');

        }

        return view('suppliers.view', ['suppliers' => Supplier::paginate()]);
    }

    public function create()
    {
        if(auth()->user()->cant('create', Supplier::class))
        {
            return ErrorController::forbidden(to_route('suppliers.index'), 'Unauthorised to Create Suppliers.');

        }

        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        if(auth()->user()->cant('create', Supplier::class))
        {
            return ErrorController::forbidden(to_route('suppliers.index'), 'Unauthorised to Create Suppliers.');

        }
        $validated = $request->validate([
            'name' => 'required|max:255',
            'url' => 'sometimes|nullable|unique:suppliers',
            'email' => 'sometimes|nullable|unique:locations|email:rfc,dns,spoof,filter',
            'telephone' => 'sometimes|nullable|max:14',
        ]);
        //
        Supplier::create($request->only('name', 'address_1', 'address_2', 'city', 'county', 'postcode', 'email', 'telephone', 'fax', 'url', 'photo_id', 'notes'))->save();
        session()->flash('success_message', $request->name . ' has been updated successfully');

        return to_route('suppliers.index');
    }

    public function show(Supplier $supplier)
    {
        if(auth()->user()->cant('view', Supplier::class))
        {
            return ErrorController::forbidden(to_route('suppliers.index'), 'Unauthorised to Show Suppliers.');

        }

        return view('suppliers.show', ['supplier' => $supplier]);
    }

    public function edit(Supplier $supplier)
    {
        if(auth()->user()->cant('update', Supplier::class))
        {
            return ErrorController::forbidden(to_route('suppliers.index'), 'Unauthorised to Edit Suppliers.');

        }

        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        if(auth()->user()->cant('update', Supplier::class))
        {
            return ErrorController::forbidden(to_route('suppliers.index'), 'Unauthorised to Update Suppliers.');

        }
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => ['sometimes', 'nullable', \Illuminate\Validation\Rule::unique('suppliers')->ignore($supplier->id), 'email:rfc,dns,spoof,filter'],
            'url' => ['sometimes', 'nullable', \Illuminate\Validation\Rule::unique('suppliers')->ignore($supplier->id)],
            'telephone' => 'sometimes|nullable|max:14',
        ]);
        //
        $supplier->fill($request->only('name', 'address_1', 'address_2', 'city', 'county', 'postcode', 'email', 'telephone', 'fax', 'url', 'photo_id', 'notes'))->save();
        session()->flash('success_message', $supplier->name . ' has been updated successfully');

        return to_route('suppliers.index');
    }

    public function destroy(Supplier $supplier)
    {
        if(auth()->user()->cant('forceDelete', Supplier::class))
        {
            return ErrorController::forbidden(to_route('suppliers.index'), 'Unauthorised to Delete Suppliers.');

        }
        $name = $supplier->name;
        $supplier->delete();
        session()->flash('danger_message', $name . ' was deleted from the system');

        return to_route('suppliers.index');
    }

    public function export(Supplier $supplier)
    {
        if(auth()->user()->cant('viewAny', $supplier))
        {
            return ErrorController::forbidden(to_route('suppliers.index'), 'Unauthorised to Export Suppliers.');

        }

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        \Maatwebsite\Excel\Facades\Excel::store(new SupplierExport, "/public/csv/suppliers-ex-{$date}.xlsx");
        $url = asset("storage/csv/suppliers-ex-{$date}.xlsx");

        return to_route('suppliers.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();
    }

    public function downloadPDF()
    {
        if(auth()->user()->cant('viewAny', Supplier::class))
        {
            return ErrorController::forbidden(to_route('suppliers.index'), 'Unauthorised to Download Suppliers.');

        }

        $found = Supplier::all();
        $suppliers = array();

        foreach($found as $f)
        {
            $array = array();
            $array['name'] = $f->name;
            $array['url'] = $f->url ?? 'N/A';
            $array['email'] = $f->email ?? 'N/A';
            $array['telephone'] = $f->telehone ?? 'N/A';
            $array['line1'] = $f->address_1 ?? 'N/A';
            $array['line2'] = $f->address_2 ?? 'N/A';
            $array['city'] = $f->city ?? 'N/A';
            $array['county'] = $f->county ?? 'N/A';
            $array['postcode'] = $f->postcode ?? 'N/A';
            $array['asset'] = $f->assets()->count();
            $array['accessory'] = $f->accessory->count() ?? 'N/A';
            $array['component'] = $f->component->count() ?? 'N/A';
            $array['consumable'] = $f->consumable->count() ?? 'N/A';
            $array['miscellaneous'] = $f->miscellanea->count() ?? 'N/A';
            $suppliers[] = $array;
        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'suppliers-' . $date;

        dispatch(new SuppliersPdf($suppliers, $user, $path))->afterResponse();
        //Create Report

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('suppliers.index')
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();

    }

    public function downloadShowPDF(Supplier $supplier)
    {
        if(auth()->user()->cant('viewAny', Supplier::class))
        {
            return ErrorController::forbidden(to_route('suppliers.index'), 'Unauthorised to Download Suppliers.');

        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = str_replace(' ', '-', $supplier->name) . '-' . $date;
        dispatch(new supplierPdf($supplier, $user, $path))->afterResponse();

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('suppliers.show', $supplier->id)
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    public function search(Request $request)
    {
        $suppliers = Supplier::where('name', 'LIKE', '%' . $request->search . "%")->take(3)->get()->unique('name');
        $output = "<ul id='supplierSelect' class='list-group'>";
        foreach($suppliers as $supplier)
        {
            $output .= " <li class='list-group-item d-flex justify-content-between align-items-center pointer' data-id='" . $supplier->id . "' data-name='" . $supplier->name . "'>
                            {$supplier->name}
                            <span class='badge badge-primary badge-pill'>1</span>
                        </li>";
        }
        $output .= "</ul>";

        return Response($output);
    }

    public function preview(Request $request)
    {
        if($supplier = Supplier::find($request->id))
        {
            if($supplier->photo()->exists() && $src = asset($supplier->photo->path))
            {

            } else
            {
                $src = asset('images/svg/suppliers.svg');
            }
            $output = " <div class='model_title text-center h4 mb-3'>{$supplier->name}</div>
                        <div class='model_image p-4 d-flex justify-content-center'>
                            <img id='profileImage' src='{$src}' height='150px'
                                alt='Select Profile Picture'>
                        </div>";
            if($supplier->address_1 != '')
            {
                $output .= "<div class='model_no py-2 px-4 text-center'>
                            Address: {$supplier->address_1}, {$supplier->city}, {$supplier->postcode}
                        </div>";
            }

            $output .= "<div class='model_no py-2 px-4 text-center'>
                            Website: {$supplier->url}
                        </div>
                        <div class='model_no py-2 px-4 text-center'>
                            Email: {$supplier->email}
                        </div>
                        <div class='model_no py-2 px-4 text-center'>
                            {$supplier->notes}
                        </div>";

            return $output;
        }
    }

}
