@extends('layouts.app')

@section('css')

@endsection

@section('content')
    <form action="/manufacturers/create" method="POST">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Add New Manufacturer</h1>

            <div>
                <a href="/manufacturers" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                        class="fas fa-plus fa-sm text-white-50"></i> Back to Manufacturers</a>
                <button type="submit" class="d-inline-block btn btn-sm btn-success shadow-sm"><i
                        class="far fa-save fa-sm text-white-50"></i> Save</button>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                        class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
            </div>
        </div>

        <section>
            <p class="mb-4">Below are different tiles, one for each Manufacturer stored in the management system. Each tile has different options and Manufacturers can created, updated, and deleted.</p>
            <div class="row row-eq-height">
                <div class="col-12 col-md-8 col-lg-9 col-xl-10">
                    <div class="card shadow h-100">
                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @csrf

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>" name="name"
                                       id="name" placeholder="">
                            </div>
                            <div class="form-group">
                                <label for="supportPhone">Telephone</label>
                                <input type="text" class="form-control" name="supportPhone" id="supportPhone"
                                       placeholder="Telelphone">
                            </div>   <div class="form-group">
                                <label for="supportUrl">Manufacturer Website</label>
                                <input type="text" class="form-control" name="supportUrl" id="supportUrl" placeholder="www.dell.com">
                            </div>

                            <div class="form-group">
                                <label for="supportEmail">Email Address</label>
                                <input type="text" class="form-control" name="supportEmail" id="supportEmail" placeholder="Email">
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3 col-xl-2">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="w-100">
                                <div class="formgroup mb-2 p-2">
                                    <h4 class="h6 mb-3">Manufacturer Image</h4>
                                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                                            class="fas fa-plus fa-sm text-white-50"></i> Add Manufacturer image</a>
                                    <input type="hidden" id="PhotoId" name="PhotoId" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-header bg-primary-blue text-white">Information</div>
                <div class="card-body"><p>There are currently {{$manufacturerAmount}} Manufacturers on the System</p></div>

            </div>
        </section>
    </form>
@endsection

@section('modals')

@endsection

@section('js')

@endsection
