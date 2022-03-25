@extends('layouts.app')

@section('title', 'Asset Statuses')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Status Fields</h1>
        <div>
            @can('create' , \App\Models\Status::class)
                <x-buttons.add :toggle="'modal'" :target="'#addStatusModal'">Status</x-buttons.add>
            @endcan

        </div>
    </div>
    <x-handlers.alerts/>
    <x-form.errors/>

    <section>
        <p class="mb-4">Below are the different categories of all the different assets statuses stored in the management
                        system. Each
                        has displays the amount of different assets that are assigned the status.</p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="categoryTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-4"><small>Name</small></th>
                            <th><small>Deployable</small></th>
                            <th><small>Assets</small></th>
                            <th><small>Accessories</small></th>
                            <th><small>Components</small></th>
                            <th><small>Consumables</small></th>
                            <th><small>Miscellaneous</small></th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="col-4"><small>Name</small></th>
                            <th><small>Deployable</small></th>
                            <th><small>Assets</small></th>
                            <th><small>Accessories</small></th>
                            <th><small>Components</small></th>
                            <th><small>Consumables</small></th>
                            <th><small>Miscellaneous</small></th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        <?php $statuses = App\Models\Status::all();?>
                        @foreach($statuses as $status)
                            <tr>
                                <td><i class="{{$status->icon}}"
                                       style="color: {{$status->colour}};"></i> {{ $status->name }}</td>
                                <td class="text-center">@if($status->deployable == 1){!! '<i class="fas fa-check text-success"></i>'!!}@else{!!'<i class="fas fa-times text-danger"></i>'!!}@endif</td>
                                <td class="text-center">
                                    @php
                                        $assets = auth()->user()->location_assets()->statusFilter([$status->id]);
                                    @endphp
                                    {{ $assets->count() }}
                                </td>
                                <td class="text-center">
                                    @php

                                        $accessories = auth()->user()->location_accessories()->statusFilter([$status->id]);

                                    @endphp
                                    {{ $accessories->count() }}
                                </td>
                                <td class="text-center">
                                    @php
                                        $components = auth()->user()->location_components()->statusFilter([$status->id]);
                                    @endphp
                                    {{ $components->count() }}
                                </td>
                                <td class="text-center">
                                    @php

                                        $consumables = auth()->user()->location_consumables()->statusFilter([$status->id]);

                                    @endphp
                                    {{ $consumables->count() }}
                                </td>
                                <td class="text-center">
                                    @php
                                        $miscellaneous = auth()->user()->location_miscellaneous()->statusFilter([$status->id]);
                                    @endphp
                                    {{ $miscellaneous->count() }}
                                </td>
                                <td class="text-right">
                                    <div class="dropdown no-arrow">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                           id="dropdownMenu{{$status->id}}Link" data-bs-toggle="dropdown"
                                           aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div
                                            class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenu{{$status->id}}Link">
                                            <div class="dropdown-header">Asset Options:</div>
                                            <a href="{{ route('status.show', $status->id) }}"
                                               class="dropdown-item">View</a>
                                            <a class="dropdown-item updateBtn" data-id="{{$status->id}}"
                                               data-name="{{ $status->name}}"
                                               data-route="{{ route('status.update', $status->id)}}"
                                               data-deploy="{{$status->deployable}}" data-icon="{{$status->icon}}"
                                               data-colour="{{$status->colour}}">Edit</a>
                                            <form id="form{{$status->id}}"
                                                  action="{{route("status.destroy",$status->id)}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <a class="dropdown-item deleteBtn" data-id="{{$status->id}}">Delete</a>
                                            </form>
                                        </div>
                                    </div>


                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with Status's</h4>
                <p>Click <a href="{{route("documentation.index").'#collapseFourStatus'}}">here</a> for the Documentation
                   on Status's on Adding and Removing!</p>

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
                <form action="{{ route('status.store')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <p>Please enter the name of your category.</p>
                            <input class="form-control" name="name" id="name" type="text" placeholder="Category Name">
                        </div>
                        <div class="form-group">
                            <p>Will the assets that have this status be deployable?</p>
                            <input type="radio" id="deployable_yes" name="deployable" value="1">
                            <label for="deployable_yes">Yes</label><br>
                            <input type="radio" id="deployable_no" name="deployable" value="0">
                            <label for="deployable_no">No</label>
                        </div>
                        <div class="form-group">
                            <label for="colour">Icon Colour</label>
                            <input type="color" name="colour" value="#666">
                        </div>
                        <div class="form-group">
                            <label for="icon">Icon</label>
                            <select name="icon" class="form-control">
                                <option value="far fa-circle"><i class="far fa-circle"></i> Doughnut</option>
                                <option value="fas fa-circle"><i class="fas fa-circle"></i> Circle</option>
                                <option value="fas fa-check"><i class="fas fa-check"></i> Tick</option>
                                <option value="fas fa-times"><i class="fas fa-times"></i> Times</option>
                                <option value="fas fa-skull-crossbones"><i class="fas fa-skull-crossbones"></i> Cross
                                                                                                                Bones
                                </option>
                                <option value="fas fa-tools"><i class="fas fa-tools"></i> Tools</option>
                            </select>
                        </div>
                        <small class="text-info">**You will be able to assign this Status to any assets on the system.
                                                 These
                                                 can act as a filter.</small>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-grey" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-green" type="button">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- update Modal-->
    <div class="modal fade bd-example-modal-lg" id="updateStatusModal" tabindex="-1" role="dialog"
         aria-labelledby="updateStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateCategoryModalLabel">Change Status</h5>
                    <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="updateForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="form-group">

                            <p>Please enter the name of your category.</p>
                            <input class="form-control" name="name" id="update_name" type="text" value="">
                        </div>
                        <div class="form-group">
                            <p>Will the assets that have this status be deployable?</p>
                            <input type="radio" id="update_deployable_yes" name="deployable" value="1">
                            <label for="deployable_yes">Yes</label><br>
                            <input type="radio" id="update_deployable_no" name="deployable" value="0">
                            <label for="deployable_no">No</label>
                        </div>
                        <div class="form-group">
                            <label for="colour">Icon Colour</label>
                            <input type="color" name="colour" value="#666" id="update_colour">
                        </div>
                        <div class="form-group">
                            <label for="icon">Icon</label>
                            <select name="icon" class="form-control" id="update_icon">
                                <option value="far fa-circle"><i class="far fa-circle"></i> Doughnut</option>
                                <option value="fas fa-circle"><i class="fas fa-circle"></i> Circle</option>
                                <option value="fas fa-check"><i class="fas fa-check"></i> Tick</option>
                                <option value="fas fa-times"><i class="fas fa-times"></i> Times</option>
                                <option value="fas fa-skull-crossbones"><i class="fas fa-skull-crossbones"></i> Cross
                                                                                                                Bones
                                </option>
                                <option value="fas fa-tools"><i class="fas fa-tools"></i> Tools</option>
                            </select>
                        </div>
                        <small class="text-info">**You will be able to assign categories to any assets on the system.
                                                 These
                                                 can act as a filter.</small>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-grey" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-green" type="button">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-modals.delete :archive="true"/>
@endsection

@section('js')
    <script src="{{ asset('js/delete.js') }}"></script>
    <script>
        const updateModal = new bootstrap.Modal(document.getElementById('updateStatusModal'));

        document.querySelectorAll('.updateBtn').forEach(elem => elem.addEventListener("click", (event) => {
            let val = elem.getAttribute('data-id');
            let deployable = elem.getAttribute('data-deploy');
            if (deployable == 1) {
                document.getElementById("update_deployable_yes").checked = true;
            } else {
                document.getElementById("update_deployable_no").checked = true;
            }

            let name = elem.getAttribute('data-name');
            let route = elem.getAttribute('data-route');
            let colour = elem.getAttribute('data-colour');
            let icon = elem.getAttribute('data-icon');
            document.querySelector('#update_name').value = name;
            document.querySelector('#update_colour').value = colour;
            document.querySelector('#update_icon').value = icon;
            document.querySelector('#updateForm').action = route;
            updateModal.show();
        }));
    </script>
@endsection
