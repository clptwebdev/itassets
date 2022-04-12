@extends('layouts.app')

@section('title', 'Edit '.$manufacturer->name)


@section('content')
    <x-form.layout :action="route('manufacturers.update' , $manufacturer->id)" method="PATCH">
        <x-wrappers.nav title="Edit Manufacturer Details">
            @can('viewAny' , \App\Models\Manufacturer::class)
                <x-buttons.return :route="route('manufacturers.index')">Manufacturers</x-buttons.return>
            @endcan
            <x-buttons.help :route="route('documentation.index').'#collapseThirteenManufacturers'"/>
            <x-buttons.submit>Save</x-buttons.submit>
        </x-wrappers.nav>
        <section>
            <p class="mb-4">Below is the {{$manufacturer->name}} tile ,this is for the Manufacturer stored in the
                            management system.</p>
            <div class="row row-eq-height">
                <div class="col-12 col-md-8 col-lg-9 col-xl-10">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <x-handlers.alerts/>
                            <div class="form-group">
                                <x-form.input name="name" :value="$manufacturer->name"/>
                            </div>
                            <div class="form-group">
                                <x-form.input name="supportPhone" :value="$manufacturer->supportPhone"/>
                            </div>
                            <div class="form-group">
                                <x-form.input title="Manufacturer Website" name="supportUrl"
                                              :value="$manufacturer->supportUrl"/>
                            </div>

                            <div class="form-group">
                                <x-form.input title="Email Address" name="supportEmail"
                                              :value="$manufacturer->supportEmail"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-3 col-xl-2">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="w-100">
                                <div class="formgroup mb-2 p-2">
                                    <h4 class="h6 mb-3">Manufacturer</h4>
                                    <img id="profileImage" src="{{ asset('images/svg/manufacturer_image.svg') }}"
                                         width="100%" alt="Select Profile Picture" data-bs-toggle="modal"
                                         data-bs-target="#imgModal">
                                    <input type="hidden" id="photoId" name="photoId" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
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
