<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('suppliers.view');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
        return redirect(route('supplier.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        return redirect(route('supplier.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        $name = $supplier->name;
        $supplier->delete();
        session()->flash('danger_message', $name.' was deleted from the system');
        return redirect(route('supplier.index'));
    }
}
