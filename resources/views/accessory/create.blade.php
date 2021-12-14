@extends('layouts.app')

@section('title', 'Add New Accessory')

@section('css')

@endsection

@section('content')
    <x-form.layout :action="route('accessories.store')">
        <x-wrappers.nav title="Add New Accessory">
            <x-buttons.return :route="route('accessories.index')"> Accessories</x-buttons.return>
            <x-buttons.submit>Save</x-buttons.submit>
        </x-wrappers.nav>
        <section>
            <p class="mb-4">Adding a new Accessory to the Apollo Asset Management System. Enter in the following
                required information and click the 'Save' button. Or click the 'Back' button
                to return the accessories page.
            </p>
            <div class="row row-eq-height">
                <div class="col-12 col-md-8 col-lg-9">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <x-form.errors/>
                            <div class="form-group">
                                <x-form.input name="name" formAttributes="required"/>
                            </div>
                            <div class="form-group">
                                <x-form.input name="model"/>
                            </div>
                            <div class="form-group">
                                <x-form.input name="serial_no" formAttributes="required"/>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <x-form.input name="order_no"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <x-form.input name="purchased_cost" formAttributes="required"/>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" value="1" name="donated"
                                               id="donated">
                                        <label class="form-check-label" for="donated">Donated</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <x-form.date name="purchased_date" formAttributes="required"/>
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <x-form.select name="manufacturer_id" :models="$manufacturers"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <x-form.select name="supplier_id" :models="$suppliers"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <x-form.input name="warranty"/>
                                </div>
                            </div>
                            <div id="categories" class="form-control h-auto p-4 mb-3 rounded">
                                <x-form.checkbox :models="$categories" name="category"/>
                            </div>
                            <div class="form-group">
                                <x-form.textarea name="notes" formAttributes="rows='10'"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <x-admin.image-upload/>
                            <hr>
                            <div class="form-group col-md-12">
                                <x-form.select name="location_id" :models="$locations"/>
                            </div>
                            <div class="form-group col-md-12">
                                <x-form.input name="room"/>
                            </div>

                            <div class="form-group col-md-12">
                                <x-form.select name="depreciation_id" :models="$depreciations"/>
                            </div>
                            <div class="form-group col-md-12">
                                <x-form.select name="status_id" :models="$statuses"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </x-form.layout>
@endsection

@section('modals')
    <x-modals.image-modal/>
@endsection

