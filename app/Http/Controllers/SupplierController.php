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

class SupplierController extends Controller
{
    
    public function index()
    {
        return view('suppliers.view');
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'url' => 'required|unique:suppliers',
            'email' => 'required|unique:locations|email:rfc,dns,spoof,filter',
            'telephone' => 'required|max:14',
        ]);
        //
        Supplier::create($request->only('name', 'address_1', 'address_2', 'city', 'county', 'postcode', 'email', 'telephone', 'fax', 'url', 'photo_id', 'notes'))->save();
        session()->flash('success_message', $request->name.' has been updated successfully');
        return redirect(route('suppliers.index'));
    }

    public function show(Supplier $supplier)
    {
        return view('suppliers.show', ['supplier' => $supplier]);
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => ['required', \Illuminate\Validation\Rule::unique('suppliers')->ignore($supplier->id), 'email:rfc,dns,spoof,filter'],
            'url' => ['required', \Illuminate\Validation\Rule::unique('suppliers')->ignore($supplier->id)],
            'telephone' => 'required|max:14',
        ]);
        //
        $supplier->fill($request->only('name', 'address_1', 'address_2', 'city', 'county', 'postcode', 'email', 'telephone', 'fax', 'url', 'photo_id', 'notes'))->save();
        session()->flash('success_message', $supplier->name.' has been updated successfully');
        return redirect(route('suppliers.index'));
    }

    public function destroy(Supplier $supplier)
    {
        $name = $supplier->name;
        $supplier->delete();
        session()->flash('danger_message', $name.' was deleted from the system');
        return redirect(route('supplier.index'));
    }

    public function export(Supplier $supplier)
    {

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        \Maatwebsite\Excel\Facades\Excel::store(new SupplierExport, "/public/csv/suppliers-ex-{$date}.csv");
        $url = asset("storage/csv/suppliers-ex-{$date}.csv");
        return redirect(route('suppliers.index'))
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput(); 
    }

    public function downloadPDF()
    {
        if (auth()->user()->cant('viewAny', Supplier::class)) {
            return redirect(route('errors.forbidden', ['area', 'suppliers', 'View PDF']));
        }

        $found = Supplier::all();
        $suppliers = array();

        foreach($found as $f){
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
            $array['asset'] = $f->asset->count(); 
            $array['accessory'] = $f->accessory->count() ?? 'N/A';
            $array['component'] = $f->component->count() ?? 'N/A';
            $array['consumable'] = $f->consumable->count() ?? 'N/A';
            $array['miscellaneous'] = $f->miscellanea->count() ?? 'N/A';
            $suppliers[] = $array;
        }

        $user = auth()->user();
        
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'suppliers-'.$date;

        dispatch(new SuppliersPdf($suppliers, $user, $path))->afterResponse();
        //Create Report
        
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report'=> $url, 'user_id'=> $user->id]);

        return redirect(route('suppliers.index'))
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();

    }

    public function downloadShowPDF(Supplier $supplier)
    {
        if (auth()->user()->cant('viewAny', Supplier::class)) {
            return redirect(route('errors.forbidden', ['suppliers', $supplier->id, 'View PDF']));
        }
        
        $user = auth()->user();
        
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = str_replace(' ', '-', $supplier->name).'-'.$date;

        dispatch(new supplierPdf($supplier, $user, $path))->afterResponse();

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report'=> $url, 'user_id'=> $user->id]);

        return redirect(route('suppliers.show', $supplier->id))
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

}
