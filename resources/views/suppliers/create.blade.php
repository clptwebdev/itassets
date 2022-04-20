@extends('layouts.app')

@section('title', 'Add a New Supplier')

@section('css')

@endsection

@section('content')
    <form action="{{ route('suppliers.store') }}" method="POST">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Add New Supplier</h1>

            <div>
                @can('viewAny' , \App\Models\Supplier::class)
                    <a href="{{ route('suppliers.index') }}"
                       class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                            class="fas fa-chevron-left fa-sm text-white-50"></i> Back to Suppliers</a>
                @endcan
                <button type="submit" class="d-inline-block btn btn-sm btn-green shadow-sm"><i
                        class="far fa-save fa-sm text-white-50"></i> Save
                </button>
            </div>
        </div>

        <section>
            <p class="mb-4">Adding a new supplier to the asset management system. Enter the following information and
                            click the 'Save' button. Or click the 'Back' button
                            to return the suppliers page. </p>
            <div class="row row-eq-height">
                <div class="col-12 col-md-8 col-lg-9">
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
                                <x-form.input name="name" formAttributes="required"/>
                            </div>
                            <div class="form-group">
                                <x-form.input name="address_1"/>
                            </div>
                            <div class="form-group">
                                <x-form.input name="address_2"/>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <x-form.input name="city"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <x-form.input name="county"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <x-form.input name="postcode"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <x-form.textarea name="notes" formAttributes=" cols=' 30' rows='10' "/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="w-100">
                                <div class="formgroup mb-2 p-2">
                                    <h4 class="h6 mb-3">Supplier Image</h4>
                                    <img id="profileImage" src="{{ asset('images/svg/suppliers.svg') }}" width="100%"
                                         alt="Select Profile Picture">
                                    <input type="hidden" id="photo_id" name="photo_id" value="0">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <x-form.input name="url"/>
                            </div>
                            <div class="form-group">
                                <x-form.input name="telephone"/>
                            </div>

                            <div class="form-group">
                                <x-form.input name="fax"/>
                            </div>

                            <div class="form-group">
                                <x-form.input name="email"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <x-admin.suppliers.details/>

        </section>
    </form>
@endsection

@section('modals')
    <x-modals.photo-upload/>
    <x-modals.photo-upload-form/>
@endsection

@section('js')
    <script src="{{asset('js/photo.js')}}"></script>

@endsection
