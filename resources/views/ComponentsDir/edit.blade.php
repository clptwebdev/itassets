@extends('layouts.app')

@section('title', 'Edit Component')

@section('css')

@endsection

@section('content')
    <x-form.layout :action="route('components.update', $data->id)" method="PATCH">
        <x-wrappers.nav title="Edit components">
            <x-buttons.return :route="route('components.index')">Components</x-buttons.return>
            <x-buttons.help :route=" route('documentation.index').'#collapseNineComponent'"></x-buttons.help>
            <x-buttons.submit>Save</x-buttons.submit>
        </x-wrappers.nav>

        <x-form.errors/>
        <section>
            <p class="mb-4">Edit {{ $data->name}}, Component stored in the Apollo Asset Management System. Change
                the information
                and
                click the 'Save' button. Or click the 'Back' button
                to return the Components page.
            </p>
            <div class="row row-eq-height">
                <div class="col-12 col-md-8 col-lg-9">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="form-group">
                                <?php $name = $data->name;  ?>
                                <x-form.input name="name" formAttributes="required" :value="$name"/>
                            </div>
                            <div class="form-group">
                                <?php $serial_no = $data->serial_no;  ?>
                                <x-form.input name="serial_no" formAttributes="required" :value="$serial_no"/>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <?php $order_no = $data->order_no;  ?>
                                    <x-form.input name="order_no" formAttributes="required" :value="$order_no"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <?php $cost = $data->purchased_cost;  ?>
                                    <x-form.input name="purchased_cost" formAttributes="required" :value="$cost"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <?php  $date = \Carbon\Carbon::parse($data->purchased_date)->format('Y-m-d')?>
                                    <x-form.date name="purchased_date" formAttributes="required" :value="$date"/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <?php $selected = $data->supplier->id; ?>
                                    <x-form.select name="supplier_id" formAttributes="required" :models="$suppliers"
                                                   :selected="$selected"/>
                                </div>
                                <div class="form-group col-md-6">
                                    <?php $selected = $data->status->id; ?>
                                    <x-form.select name="status_id" formAttributes="required" :models="$statuses"
                                                   :selected="$selected"/>
                                </div>
                            </div>
                            @php( $cat_array = [])
                            @foreach($data->category as $cc)
                                @php( $cat_array[] = $cc->id)
                            @endforeach
                            <div class="form-control h-auto p-4 mb-3 rounded">
                                <x-form.checkbox :models="$categories" name="category" :checked="$cat_array"/>
                            </div>
                            <div class="form-group">
                                <?php $notes = $data->notes; ?>
                                <x-form.textarea name="notes" formAttributes="rows='10'" :value="$notes"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <x-admin.image-upload :model="$data"/>
                            <hr>
                            <div class="form-group col-md-12">
                                <?php $selected = $data->location_id; ?>
                                <x-form.select name="location_id" formAttributes="required" :models="$locations"
                                               :selected="$selected"/>
                            </div>

                            <div class="form-group col-md-12">
                                <?php $warranty = $data->warranty  ?>
                                <x-form.input name="warranty" :value="$warranty"/>
                            </div>
                            <div class="form-group col-md-12">
                                <?php $selected = $data->manufacturer->id; ?>
                                <x-form.select name="manufacturer_id" :models="$manufacturers" :selected="$selected"/>
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

@section('js')
@endsection
