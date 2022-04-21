@extends('layouts.app')

@section('title', 'Depreciation Models')



@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Depreciation</h1>
        <div>
            @can('create' , \App\Models\Depreciation::class)
                <x-buttons.add :toggle="'modal'" :target="'#addStatusModal'">Depreciation</x-buttons.add>
            @endcan
        </div>
    </div>

    <x-handlers.alerts/>

    <section>
        <p class="mb-4">Below are the different depreciation models. </p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="depTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-4"><small>Name</small></th>
                            <th class="col-1 text-center"><small>Months</small></th>
                            <th class="col-6 text-center">Models</th>
                            <th class="text-right col-1">Options</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="col-4"><small>Name</small></th>
                            <th class="col-1 text-center"><small>Months</small></th>
                            <th class="col-6 text-center"><small>Models</small></th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($depreciation as $dep)
                            <tr>
                                <td>{{ $dep->name }}</td>
                                <td class="text-center">{{$dep->years * 12}}</td>
                                <td class="text-center">
                                    {{$dep->models->count()}}
                                </td>
                                <td class="text-right">
                                    <x-wrappers.table-settings>
                                        @can('update', $fieldset)
                                            <x-buttons.dropdown-item :route="route('depreciation.update', $dep->id) ">
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
                                    <div class="dropdown no-arrow">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                           id="dropdownMenu{{$dep->id}}Link" data-bs-toggle="dropdown"
                                           aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div
                                            class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenu{{$dep->id}}Link">
                                            <div class="dropdown-header">Asset Options:</div>
                                            @can('update', $dep)
                                                <a href="#" class="dropdown-item updateBtn" data-id="{{$dep->id}}"
                                                   data-route="{{ route('depreciation.update', $dep->id)}}"
                                                   data-name="{{$dep->name}}" data-years="{{$dep->years}}">Edit</a>
                                            @endcan
                                            @can('delete', $dep)
                                                <a class="dropdown-item deleteBtn" href="#"
                                                   data-route="{{ route('depreciation.destroy', $dep->id)}}">Delete</a>
                                            @endcan
                                            <a class="dropdown-item showBtn" href="#" data-id="{{$dep->id}}"
                                               data-name="{{$dep->name}}"
                                               data-route="{{ route('depreciation.show', $dep->id)}}">View</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <x-paginate :model="$depreciation"/>
                </div>
            </div>
        </div>
        <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with Depreciation</h4>
                <p>Click <a href="{{route("documentation.index").'#collapseSixteenDepreciation'}}">here</a> for the
                   Documentation on Depreciation on Adding and Removing!</p>

            </div>
        </div>
    </section>

@endsection

@section('modals')
    <!-- Create Modal-->
    <div class="modal fade bd-example-modal-lg" id="addStatusModal" tabindex="-1" role="dialog"
         aria-labelledby="addStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStatusModalLabel">Create New Status </h5>
                    <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('depreciation.store')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <p>Please enter the name of your depreciation model.</p>
                            <input class="form-control" name="name" id="name" type="text" placeholder="Dep. Name">
                        </div>
                        <div class="form-group">
                            <label for="years">Depreciation Model Duration*:</label>
                            <input class="form-control" type="number" id="year" name="years" value="3">
                            <small class="text-info">This is amount of years the depreciation will be spread. For
                                                     example a 3 year depreciation model will deduct 33% of its
                                                     value each year.
                                                     Whereas a 4 year model will deduct 25% each calender year from
                                                     it's purchase date.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-grey" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-coral" type="button" id="confirmBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- update Modal-->
    <div class="modal fade bd-example-modal-lg" id="updateDepModal" tabindex="-1" role="dialog"
         aria-labelledby="updateDepModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateDepModalLabel">Update Depreciation Model</h5>
                    <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="updateForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="form-group">
                            <p>Please enter the name of your depreciation model.</p>
                            <input class="form-control" name="name" id="update_name" type="text"
                                   placeholder="Dep. Name">
                        </div>
                        <div class="form-group">
                            <label for="years">Depreciation Model Duration*:</label>
                            <input class="form-control" type="number" id="update_years" name="years" value="3">
                            <small class="text-info">This is amount of years the depreciation will be spread. For
                                                     example a 3 year depreciation model will deduct 33% of its
                                                     value each year.
                                                     Whereas a 4 year model will deduct 25% each calender year from
                                                     it's purchase date.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-grey" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-coral" type="button" id="confirmBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- show Modal-->
    <div class="modal fade bd-example-modal-lg" id="showDepModal" tabindex="-1" role="dialog"
         aria-labelledby="showDepModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showDepModalLabel"></h5>
                    <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="showDepModels"></div>
            </div>
        </div>
    </div>
    <x-modals.delete :archive="true"/>
@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}"></script>
    <script>
        const updateModal = new bootstrap.Modal(document.getElementById('updateDepModal'));
        const showDepModal = new bootstrap.Modal(document.getElementById('showDepModal'));
        const xhttp = new XMLHttpRequest();

        document.querySelectorAll(".updateBtn").forEach(elem => elem.addEventListener("click", (e) => {
            e.preventDefault();
            let val = elem.getAttribute('data-id');
            let name = elem.getAttribute('data-name');
            let route = elem.getAttribute('data-route');
            let years = elem.getAttribute('data-years');
            document.querySelector('#update_name').value = name;
            document.querySelector('#update_years').value = years;
            document.querySelector('#updateForm').action = route;
            updateModal.show();
        }));
        //get deprecation
        document.querySelectorAll(".showBtn").forEach(elem => elem.addEventListener("click", (e) => {
            e.preventDefault();
            let name = elem.getAttribute('data-name');
            let route = elem.getAttribute('data-route');
            document.querySelector('#showDepModalLabel').innerHTML = name;


            xhttp.onload = function () {
                let html = "";
                Object.entries(JSON.parse(xhttp.responseText)).forEach(entry => {
                    const [key, value] = entry;
                    let string = `<p class='d-flex justify-content-center bg-blue rounded p-1 m-2 text-white'>${value['name']}</p>`
                    html = html.concat(string);
                });
                document.querySelector('#showDepModels').innerHTML = html;
                showDepModal.show();

            }
            xhttp.open("GET", route);
            xhttp.send();

        }));
    </script>
@endsection
