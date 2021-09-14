<?php

namespace App\Http\Controllers;

use App\Exports\SupplierExport;
use App\Models\Supplier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Storage;

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
            return redirect(route('errors.forbidden', ['area', 'Manufacturers', 'View PDF']));
        }

        $suppliers = Supplier::all();

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('suppliers.pdf', compact('suppliers'));
        $pdf->setPaper('a4', 'landscape');
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        Storage::put("public/reports/suppliers-{$date}.pdf", $pdf->output());
        $url = asset("storage/reports/suppliers-{$date}.pdf");
        return redirect(route('suppliers.index'))
            ->with('success_message', "Your Report has been created successfully. Click Here to <a href='{$url}'>Download PDF</a>")
            ->withInput();

    }

    public function downloadShowPDF(Supplier $supplier)
    {
        if (auth()->user()->cant('viewAny', Supplier::class)) {
            return redirect(route('errors.forbidden', ['suppliers', $supplier->id, 'View PDF']));
        }
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('suppliers.showPdf', compact('supplier'));

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        //return $pdf->download("{$location->name}-{$date}.pdf");
        Storage::put("public/reports/{$supplier->name}-{$date}.pdf", $pdf->output());
        $url = asset("storage/reports/{$supplier->name}-{$date}.pdf");
        return redirect(route('suppliers.show', $supplier->id))
            ->with('success_message', "Your Report has been created successfully. Click Here to <a href='{$url}'>Download PDF</a>")
            ->withInput();
    }
}
