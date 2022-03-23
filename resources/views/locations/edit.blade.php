@extends('layouts.app')

@section('title', 'Edit '.$location->name)

@section('css')

@endsection

@section('content')
    <form action="{{ route('location.update', $location->id) }}" method="POST">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Update Location</h1>

            <div>
                @can('viewAll' ,\App\Models\Location::class)

                    <a href="{{ route('location.index') }}"
                       class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                            class="fas fa-chevron-left fa-sm text-white-50"></i> Back to Locations</a>
                @endcan
                <a href="{{ route('documentation.index')."#collapseFiveLocations"}}"
                   class="d-none d-sm-inline-block btn btn-sm  bg-yellow shadow-sm"><i
                        class="fas fa-question fa-sm text-dark-50"></i> need Help?</a>
                <button type="submit" class="d-inline-block btn btn-sm btn-green shadow-sm"><i
                        class="far fa-save fa-sm text-white-50"></i> Save
                </button>
            </div>
        </div>

        <section>
            <p class="mb-4">Below are different tiles, one for each location stored in the management system. Each tile
                            has different options and locations can created, updated, and deleted.</p>
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
                            {{ method_field('PATCH') }}
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text"
                                       class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>"
                                       name="name" id="name" placeholder=""
                                       value="{{ old('name') ?? $location->name }}">
                            </div>
                            <div class="form-group">
                                <label for="address2">Street Address</label>
                                <input type="text"
                                       class="form-control mb-3 <?php if ($errors->has('address_1') || $errors->has('address_2')) {?>border-danger<?php }?>"
                                       name="address_1" id="address_1" placeholder="Street Name"
                                       value="{{ old('address_1') ?? $location->address_1}}" required>
                                <input type="text" class="form-control" name="address_2" id="address_2"
                                       placeholder="Location" value="{{ old('address_2') ?? $location->address_2 }}">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="city">City</label>
                                    <input type="text"
                                           class="form-control <?php if ($errors->has('city')) {?>border-danger<?php }?>"
                                           id="city" name="city" value="{{ old('city') ?? $location->city }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="city">County</label>
                                    <input type="text"
                                           class="form-control <?php if ($errors->has('county')) {?>border-danger<?php }?>"
                                           id="county" name="county" value="{{ old('county') ?? $location->county }}"
                                           required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="postcode">Zip</label>
                                    <input type="text"
                                           class="form-control <?php if ($errors->has('postcode')) {?>border-danger<?php }?>"
                                           id="postcode" name="postcode"
                                           value="{{ old('postcode') ?? $location->postcode }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="telephone">Telephone</label>
                                <input type="text" class="form-control" name="telephone" id="telephone"
                                       placeholder="Telelphone" value="{{ old('telephone') ?? $location->telephone }}">
                            </div>

                            <div class="form-group">
                                <label for="telephone">Email Address</label>
                                <input type="text" class="form-control" name="email" id="email" placeholder="Email"
                                       value="{{ old('email') ?? $location->email }}">
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3 col-xl-2">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="w-100">
                                <div class="formgroup mb-2 p-2">
                                    <h4 class="h6 mb-3">Location Image</h4>
                                    @if($location->photo()->exists())
                                        <img id="profileImage"
                                             src="{{ asset($location->photo->path) ?? asset('images/svg/location-image.svg') }}"
                                             width="100%" alt="Select Profile Picture" data-bs-toggle="modal"
                                             data-bs-target="#imgModal">
                                    @else
                                        <img id="profileImage" src="{{ asset('images/svg/location-image.svg') }}"
                                             width="100%" alt="Select Profile Picture" data-bs-toggle="modal"
                                             data-bs-target="#imgModal">
                                    @endif
                                    <input type="hidden" id="photo_id" name="photo_id"
                                           value="{{ $location->photo_id }}">
                                </div>
                            </div>
                            <hr>
                            <label for="icon">Select School Icon Colour:</label>
                            <input class="form-control" type="color" id="icon" name="icon"
                                   value="{{ old('icon') ?? $location->icon }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-header bg-primary-blue text-white">Information</div>
                <div class="card-body"><p>There are currently {{$locations->count()}} Locations on the System</p></div>
            </div>
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
