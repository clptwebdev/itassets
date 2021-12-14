@extends('layouts.app')

@section('title', 'New Component')

@section('css')

@endsection

@section('content')
    <x-form.layout :action="route('components.store')">
        <x-wrappers.nav title="Create Components">
            <x-buttons.return :route="route('components.index')">Components</x-buttons.return>
            <x-buttons.help :route=" route('documentation.index').'#collapseNineComponent'"></x-buttons.help>
            <x-buttons.submit>Save</x-buttons.submit>
        </x-wrappers.nav>
        <section>
            <p class="mb-4">Adding a new Component to the asset management system. Enter in the following information and
                click the 'Save' button. Or click the 'Back' button to return the Components page.
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
                                <x-form.input name="serial_no" formAttributes="required"/>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <x-form.input name="order_no" formAttributes="required"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <x-form.input name="purchased_cost" formAttributes="required"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <x-form.date name="purchased_date" formAttributes="required "/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <x-form.select name="supplier_id" formAttributes="required" :models="$suppliers"/>
                                </div>
                                <div class="form-group col-md-6">
                                    <x-form.select name="status_id" formAttributes="required" :models="$statuses"/>
                                </div>
                            </div>
                            <div class="form-control h-auto p-4 mb-3 rounded">
                                <x-form.checkbox name="category" :models="$categories" formAttributes=""/>
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
                                <x-form.select formAttributes="required" name="location_id" :models="$locations"/>
                            </div>
                            <div class="form-group col-md-12">
                                <x-form.input name="warranty"/>
                            </div>
                            <div class="form-group col-md-12">
                                <x-form.select name="manufacturer_id" :models="$manufacturers"/>
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

