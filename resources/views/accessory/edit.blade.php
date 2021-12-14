@extends('layouts.app')

@section('title', 'Edit Accessory')

@section('css')

@endsection

@section('content')
    <x-form.layout :action="route('accessories.update', $accessory->id)" method="PATCH">

        <x-wrappers.nav title="Edit Accessory">
            <x-buttons.return :route="route('accessories.index')">Accessories</x-buttons.return>
            <x-buttons.help :route=" route('documentation.index').'#collapseEightAccessory'"/>
            <x-buttons.submit>Save</x-buttons.submit>
        </x-wrappers.nav>
        <section>
            <p class="mb-4">Edit {{ $accessory->name}} and change any of the following information. Click the 'Save'
                button. Or click the 'Back' button
                to return the Accessories page.
            </p>
            <div class="row row-eq-height">
                <div class="col-12 col-md-8 col-lg-9">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <x-form.errors/>

                            <div class="form-group">
                                <x-form.input name="name" formAttributes="required" :value="$accessory->name"/>
                            </div>
                            <div class="form-group">
                                <x-form.input name="model" :value="$accessory->model"/>
                            </div>
                            <div class="form-group">
                                <x-form.input name="serial_no" :value="$accessory->serial_no"
                                              formAttributes="required"/>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <x-form.input name="order_no" :value="$accessory->order_no"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <x-form.input name="purchased_cost" :value="$accessory->purchased_cost"
                                                  formAttributes="required"/>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" value="1" name="donated"
                                               id="donated" @if($accessory->donated == 1) checked @endif>
                                        <label class="form-check-label" for="donated">Donated</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <x-form.date name="purchased_date"
                                                 :value="\Carbon\Carbon::parse($accessory->purchased_date)->format('Y-m-d')"
                                                 formAttributes="required"/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <x-form.select name="manufacturer_id" :models="$manufacturers"
                                                   :selected="$accessory->manufacturer_id"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <x-form.select name="supplier_id" :models="$suppliers"
                                                   :selected="$accessory->supplier_id"/>
                                </div>

                                <div class="form-group col-md-4">
                                    <x-form.input name="warranty" :value="$accessory->warranty"/>
                                </div>

                            </div>
                            @php( $cat_array = [])
                            @foreach($accessory->category as $cc)
                                @php( $cat_array[] = $cc->id)
                            @endforeach
                            <div class="form-control h-auto p-4 mb-3 rounded">
                                <x-form.checkbox :models="$categories" name="category" :checked="$cat_array"/>
                            </div>
                            <div class="form-group">
                                <x-form.textarea name="notes" formAttributes="rows='10'" :value="$accessory->notes"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="w-100">
                                <x-admin.image-upload :model="$accessory"/>
                            </div>
                            <hr>

                            <div class="form-group col-md-12">
                                <x-form.select name="location_id" :models="$locations"
                                               :selected="$accessory->location->id"/>
                            </div>

                            <div class="form-group col-md-12">
                                <x-form.input name="room"/>
                            </div>
                            <div class="form-group col-md-12">
                                <x-form.select name="depreciation_id" :models="$depreciations"
                                               :selected="$accessory->depreciation_id"/>
                            </div>

                            <div class="form-group col-md-12">
                                <x-form.select name="status_id" :models="$statuses"
                                               :selected="$accessory->status_id"/>
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


