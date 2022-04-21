@extends('layouts.app')

@section('title', 'Asset Model Fieldsets')

@section('content')

    <x-wrappers.nav title="Asset Model Custom Fieldsets">
        <x-buttons.return :route="route('dashboard')">Dashboard</x-buttons.return>
        <x-buttons.add :route="route('fieldsets.create')">Custom Fieldset</x-buttons.add>
    </x-wrappers.nav>

    <x-handlers.alerts/>

    <section>
        <p class="mb-4">Below are the different suppliers of the assets stored in the management system. Each has
                        different options and locations can created, updated, and deleted.</p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="fieldsetTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-3"><small>Name</small></th>
                            <th class="col-1"><small>Fields</small></th>
                            <th class="col-7"><small>Assets</small></th>
                            <th class="col-1 text-right"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="col-3"><small>Name</small></th>
                            <th class="col-1"><small>Fields</small></th>
                            <th class="col-7"><small>Assets</small></th>
                            <th class="col-1 text-right"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($fieldsets as $fieldset)
                            <tr>
                                <td>{{ $fieldset->name }}</td>
                                <td>{{ $fieldset->fields->count()}}</td>
                                <td class='m-2 p-2'>
                                    @foreach($fieldset->models->take(10) as $model)
                                        <small class="p-1 bg-secondary rounded text-white">{{ $model->name }}</small>
                                    @endforeach
                                    @if($fieldset->models->count() > 10)
                                        <small
                                            class="p-1 bg-secondary rounded text-white">...{{$fieldset->models->count() - 10}}
                                                                                        +</small>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <x-wrappers.table-settings>
                                        @can('update', $fieldset)
                                            <x-buttons.dropdown-item :route="route('fieldsets.edit', $fieldset->id) ">
                                                Edit
                                            </x-buttons.dropdown-item>
                                        @endcan
                                        @can('delete', $fieldset)
                                            <x-form.layout method="DELETE" :id="'form'.$fieldset->id"
                                                           :action="route('fieldsets.destroy', $fieldset->id)">
                                                <x-buttons.dropdown-item class="deleteBtn" :data="$fieldset->id">
                                                    Delete
                                                </x-buttons.dropdown-item>
                                            </x-form.layout>
                                        @endcan
                                    </x-wrappers.table-settings>

                                </td>
                            </tr>
                        @endforeach</tbody>
                    </table>
                    <x-paginate :model="$fieldsets"/>
                </div>
            </div>
        </div>
        <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with Fieldsets</h4>
                <p>Click <a href="{{route("documentation.index").'#collapseEighteenFieldsets'}}">here</a> for the
                   Documentation on FieldSets on Adding and Removing!</p>

            </div>
        </div>
    </section>

@endsection

@section('modals')
    <x-modals.delete :archive="true"/>

@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}"></script>

@endsection
