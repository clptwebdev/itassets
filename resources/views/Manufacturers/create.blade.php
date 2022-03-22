@extends('layouts.app')

@section('title', 'Create a Manufacturer')



@section('content')
    <x-form.layout :action="route('manufacturers.store')">
        <x-wrappers.nav title="Create manufacturers">
            @can('viewAny' , \App\Models\Manufacturer::class)
                <x-buttons.return :route="route('manufacturers.index')"> Manufacturers</x-buttons.return>
            @endcan

            <x-buttons.submit>Save</x-buttons.submit>
        </x-wrappers.nav>
        <section>
            <p class="mb-4">Below are different tiles, one for each Manufacturer stored in the management system. Each
                            tile has different options and Manufacturers can created, updated, and deleted.</p>
            <div class="row row-eq-height">
                <div class="col-12 col-md-8 col-lg-9 col-xl-10">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <x-form.errors/>
                            <div class="form-group">
                                <x-form.input name="name" formAttributes="required"/>
                            </div>
                            <div class="form-group">
                                <x-form.input name="supportPhone" title="Telephone"/>
                            </div>
                            <div class="form-group">
                                <x-form.input name="supportUrl" title="Manufacturer Website"/>
                            </div>

                            <div class="form-group">
                                <x-form.input name="supportEmail" title="Email Address"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-3 col-xl-2">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="w-100">
                                <div class="formgroup mb-2 p-2">
                                    <h4 class="h6 mb-3">Manufacturer Logo</h4>
                                    <img id="profileImage" src="{{ asset('images/svg/manufacturer_image.svg') }}"
                                         width="100%" alt="Select Profile Picture" data-toggle="modal"
                                         data-target="#imgModal">
                                    <input type="hidden" id="photoId" name="photoId" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-header bg-primary-blue text-white">Information</div>
                <div class="card-body"><p>There are currently {{$manufacturerAmount}} Manufacturers on the System</p>
                </div>
            </div>
        </section>
    </x-form.layout>

@endsection

@section('modals')
    <x-modals.photo-upload/>
    <x-modals.photo-upload-form/>
@endsection

@section('js')
    <script src="{{asset('js/photo.js')}}"></script>
@endsection
