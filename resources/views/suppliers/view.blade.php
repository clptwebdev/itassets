@extends('layouts.app')

@section('css')

@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Suppliers</h1>
    <div>
        <a href="{{ route('supplier.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Add New Supplier</a>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div>
</div>

@if(session('danger_message'))
<div class="alert alert-danger"> {{ session('danger_message')}} </div>
@endif

@if(session('success_message'))
<div class="alert alert-success"> {{ session('success_message')}} </div>
@endif

<section>
    <p class="mb-4">Below are different tiles, one for each location stored in the management system. Each tile has
        different options and locations can created, updated, and deleted.</p>

    <div class="row">
        <table class="table table-striped">
            <thead>
                <tr>
                <th><input type="checkbox"></th>
                <th>Name</th>
                <th>Location</th>
                <th>Tel</th>
                <th>Email</th>
                <th>Options</th>
                <tr>
            </thead>
            <tfoot>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>Name</td>
                    <td>Location</td>
                    <td>Tel</td>
                    <td>Email</td>
                    <td>Options</td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>Name</td>
                    <td>Location</td>
                    <td>Tel</td>
                    <td>Email</td>
                    <td>Options</td>
                </tr>
            </tbody>
        </table>
    </div>
    </section>
    
    @endsection
    
    @section('modals')
    
    @endsection
    
    @section('js')
    
    @endsection