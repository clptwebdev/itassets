@extends('layouts.app')

@section('title', 'Asset Model Fieldsets')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Asset Model Custom Fieldsets</h1>
        <div>
            @can('create' ,\App\Models\Fieldset::class)

                <a href="{{ route('fieldsets.create')}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm"><i
                        class="fas fa-plus fa-sm text-white-50"></i> Add New Custom Fieldset</a>
            @endcan
            <x-buttons.add :route="route('fieldsets.create')">Custom Fieldset</x-buttons.add>

        </div>
    </div>

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
                                <td>
                                    @foreach($fieldset->models as $model)
                                        <small class="p-1 bg-secondary rounded text-white">{{ $model->name }}</small>
                                    @endforeach
                                </td>
                                <td class="text-right">
                                    <div class="dropdown no-arrow">
                                        <a class="btn btn-lilac dropdown-toggle" href="#" role="button"
                                           id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div
                                            class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Asset Options:</div>
                                            @can('update', $fieldset)
                                                <a href="{{ route('fieldsets.edit', $fieldset->id) }}"
                                                   class="dropdown-item">Edit</a>
                                            @endcan
                                            @can('delete', $fieldset)
                                                <a class="dropdown-item deleteBtn" href="#"
                                                   data-route="{{ route('fieldsets.destroy', $fieldset->id)}}">Delete</a>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
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
