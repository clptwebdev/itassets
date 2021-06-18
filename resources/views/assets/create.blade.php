@extends('layouts.app')

@section('css')

@endsection

@section('content')
    <form action="{{ route('assets.store')}}" method="POST">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Add New Asset</h1>

            <div>
                <a href="{{ route('assets.index')}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                        class="fas fa-chevron-left fa-sm text-white-50"></i> Back to Assets</a>
                <button type="submit" class="d-inline-block btn btn-sm btn-success shadow-sm"><i
                        class="far fa-save fa-sm text-white-50"></i> Save
                </button>
            </div>
        </div>

        <section>
            <p class="mb-4">Adding a new Asset to the asset management system. Enter in the following information and
                click
                the 'Save' button. Or click the 'Back' button
                to return the Assets page.
            </p>
            <div class="row row-eq-height container m-auto">
                <div class="col-12">
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

                            <div class="form-row">
                                <div class="col-12 col-sm-6 p-2 mb-3">
                                    <h3 class="h6 text-center mb-3">Device Information</h3>

                                    <div class="form-group">
                                        <label for="asset_tag">Asset Tag Number</label><span class="text-danger">*</span>
                                        <input type="text" class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>" name="asset_tag"
                                            id="asset_tag" placeholder="" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="serial_no">Serial Number</label><span class="text-danger">*</span>
                                        <input type="text" class="form-control <?php if ($errors->has('serial_no')) {?>border-danger<?php }?>" name="serial_no"
                                            id="serial_no" placeholder="" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="School Location">School Location</label><span class="text-danger">*</span>
                                        <select type="text"
                                            class="form-control mb-3 <?php if ($errors->has('location_id')) {?>border-danger<?php }?>"
                                            name="location_id" id="location_id" required>
                                            <option value="0" selected>Please select a Location</option>
                                            @foreach($locations as $location)
                                            <option value="{{$location->id}}">{{$location->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="audit_date">Audit Date</label>
                                        <input type="date" class="form-control <?php if ($errors->has('audit_date')) {?>border-danger<?php }?>"
                                            name="audit_date" id="audit_date">
                                    </div>

                                    <div class="form-group">
                                        <label for="asset_model">Asset Model Select</label><span class="text-danger">*</span>
                                        <select type="dropdown" class="form-control" name="asset_model" id="asset_model" required onchange="getFields(this);" autocomplete="off">
                                            <option value="0">Please Select a Model</option>
                                            @foreach($models as $model)
                                            <option value="{{ $model->id }}">{{ $model->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 bg-light p-2 mb-3">
                                    <h3 class="h6 text-center mb-3">Purchase Information</h3>
                                    <div class="form-group">
                                        <label for="order_no">Order No</label>
                                        <input type="text" class="form-control <?php if ($errors->has('order_no')) {?>border-danger<?php }?>"
                                            name="order_no" id="order_no">
                                    </div>
                                    <div class="form-group">
                                        <label for="purchased_date">Purchased Date</label>
                                        <input type="date"
                                            class="form-control <?php if ($errors->has('purchased_date')) {?>border-danger<?php }?>"
                                            name="purchased_date" id="purchased_date">
                                    </div>
                                    <div class="form-group">
                                        <label for="purchased_cost">Purchased Cost</label>
                                        <input type="text"
                                            class="form-control <?php if ($errors->has('purchased_cost')) {?>border-danger<?php }?>"
                                            name="purchased_cost" id="purchased_cost" placeholder="£">
                                    </div>
                                    <div class="form-group">
                                        <label for="purchased_cost">Supplier</label>
                                        <select name="supplier_id" class="form-control <?php if ($errors->has('supplier')) {?>border-danger<?php }?>">
                                            <option value="0">No Supplier</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="purchased_date">Warranty (Months)</label>
                                        <input type="number" class="form-control <?php if ($errors->has('warranty')) {?>border-danger<?php }?>"
                                            name="warranty" id="warranty">
                                    </div>
                                </div>
                            </div>

                            <div id="additional-fields" style="display: none;" class="border border-secondary p-2 mb-3">
                                Asset Additional Fields Here
                            </div>

                            <div class="form-row">
                                <label for="status">Current Status</label><span class="text-danger">*</span>
                                <select type="text"
                                    class="form-control mb-3 <?php if ($errors->has('status')) {?>border-danger<?php }?>"
                                    name="status" id="status" value="Stored" required>
                                    <option value="0">Stored</option>
                                </select>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection

    @section('modals')
       
    @endsection

    @section('js')
        <script>
            function getFields(obj){
                $.ajax({
                    url: `/assets/${obj.value}/model`,
                    success: function(data) {
                        document.getElementById("additional-fields").innerHTML = data;
                        document.getElementById("additional-fields").style.display = "block";
                    },
                    error: function(){
                        document.getElementById("additional-fields").innerHTML = "";
                        document.getElementById("additional-fields").style.display = "none";
                    },
                });
            }
        </script>
    @endsection
