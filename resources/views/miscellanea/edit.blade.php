@extends('layouts.app')

@section('title', 'Edit Miscellanea')

@section('css')

@endsection

@section('content')
    <x-form.layout :action=" route('miscellaneous.update', $miscellanea->id)" method="PATCH">
        <x-wrappers.nav title="Edit Miscellanea">
            <x-buttons.return :route="route('miscellaneous.index')">Miscellanea</x-buttons.return>
            <x-buttons.help :route="route('documentation.index').'#collapseTenMiscellaneous'"></x-buttons.help>
            <x-buttons.submit>Save</x-buttons.submit>
        </x-wrappers.nav>
        <section>
            <p class="mb-4">Edit a existing miscellanea to the asset management system. Enter in the following information and click the 'Save' button. Or click the 'Back' button
                to return the miscellaneous page.
            </p>
            <div class="row row-eq-height">
                <div class="col-12 col-md-8 col-lg-9">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <x-form.errors/>
                            <div class="form-group">
                                <x-form.input name="name" formAttributes="required" :value="$miscellanea->name"/>
                            </div>
                            <div class="form-group">
                                <x-form.input name="serial_no" formAttributes="required" :value="$miscellanea->serial_no"/>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <x-form.input name="order_no" formAttributes="required" :value="$miscellanea->order_no"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <x-form.input name="purchased_cost" formAttributes="required" :value="$miscellanea->purchased_cost"/>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" value="1" name="donated"
                                               id="donated">
                                        <label class="form-check-label" for="donated">Donated</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <x-form.date name="purchased_date" formAttributes="required " :value="\Carbon\Carbon::parse($miscellanea->purchased_date)->format('Y-m-d')"/>
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <x-form.select name="supplier_id" formAttributes="required" :models="$suppliers" :selected="$miscellanea->supplier_id"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <x-form.input name="warranty" :value="$miscellanea->warranty"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <x-form.select name="manufacturer_id" :models="$manufacturers" :selected="$miscellanea->supplier->id"/>
                                </div>

                            </div>
                            <div class="form-control h-auto p-4 mb-3 rounded">
                                @php( $cat_array = [])
                                @foreach($miscellanea->category as $cc)
                                    @php( $cat_array[] = $cc->id)
                                @endforeach
                                <x-form.checkbox name="category" :models="$categories" formAttributes="" :checked="$cat_array" />
                            </div>
                            <div class="form-group">
                                <x-form.textarea name="notes" formAttributes="rows='10'" :value="$miscellanea->notes"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <x-admin.image-upload :model="$miscellanea"/>
                            <hr>
                            <div class="form-group col-md-12">
                                <x-form.select formAttributes="required" name="location_id" :models="$locations" :selected="$miscellanea->location_id"/>
                            </div>
                            <div class="form-group col-md-12">
                                <x-form.input name="room" :value="$miscellanea->room"/>
                            </div>
                            <div class="form-group col-md-12">
                                <x-form.select name="depreciation_id" :models="$depreciations" :selected="$miscellanea->depreciation_id"/>
                            </div>
                            <div class="form-group col-md-12">
                                <x-form.select name="status_id" formAttributes="required" :models="$statuses" :selected="$miscellanea->status->id"/>
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
