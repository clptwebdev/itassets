@extends('layouts.app')

@section('title', 'View Categories')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Categories</h1>
        @can('create',\App\Models\Category::class)
            <div>
                <x-buttons.add :toggle="'modal'" :target="'#addCategoryModal'">Category</x-buttons.add>
            </div>
        @endcan
    </div>

    <x-handlers.alerts/>

    <section>
        <p class="mb-4">Below are the different categories of all the different assets stored in the management system.
                        Each has
                        displays the amount of different assets that are assigned the category.</p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="categoryTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-6"><small>Name</small></th>
                            <th class="text-center col-1"><small>Assets</small></th>
                            <th class="text-center col-1"><small>Accessories</small></th>
                            <th class="text-center col-1"><small>Components</small></th>
                            <th class="text-center col-1"><small>Consumables</small></th>
                            <th class="text-center col-1"><small>Miscellaneous</small></th>
                            <th class="text-center col-1"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th><small>Name</small></th>
                            <th class="text-center"><small>Assets</small></th>
                            <th class="text-center"><small>Accessories</small></th>
                            <th class="text-center"><small>Components</small></th>
                            <th class="text-center"><small>Consumables</small></th>
                            <th class="text-center"><small>Miscellaneous</small></th>
                            <th class="text-center"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td class="text-center">
                                    {{ $category->assets()->locationFilter($locations->pluck('id'))->count()}}
                                </td>
                                <td class="text-center">
                                    {{ $category->accessories()->locationFilter($locations->pluck('id'))->count()}}
                                </td>
                                <td class="text-center">
                                    {{ $category->components()->locationFilter($locations->pluck('id'))->count()}}
                                </td>
                                <td class="text-center">
                                    {{ $category->consumables()->locationFilter($locations->pluck('id'))->count()}}
                                </td>
                                <td class="text-center">
                                    {{ $category->miscellanea()->locationFilter($locations->pluck('id'))->count()}}
                                </td>
                                <td class="text-right">
                                    <div class="dropdown no-arrow">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                           id="dropdownMenu{{$category->id}}Link" data-bs-toggle="dropdown"
                                           aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div
                                            class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenu{{$category->id}}Link">
                                            <div class="dropdown-header">Category Options:</div>
                                            @can('update', $category)
                                                <a href="#" class="dropdown-item updateBtn" data-id="{{$category->id}}"
                                                   data-name="{{ $category->name}}"
                                                   data-route="{{ route('category.update', $category->id)}}">Edit</a>
                                            @endcan
                                            @can('delete', $category)

                                                <x-form.layout method="DELETE" :id="'form'.$category->id"
                                                               :action="route('category.destroy', $category->id)">
                                                    <x-buttons.dropdown-item class="deleteBtn" :data="$category->id">
                                                        Delete
                                                    </x-buttons.dropdown-item>
                                                </x-form.layout>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <x-paginate :model="$categories"/>
                </div>
            </div>
        </div>
        <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with Category's</h4>
                <p>Click <a href="{{route("documentation.index").'#collapseSeventeenCategories'}}">here</a> for the
                   Documentation on Categories on Adding and Removing!</p>
            </div>
        </div>
    </section>

@endsection

@section('modals')
    <!-- Create Modal-->
    <div class="modal fade bd-example-modal-lg" id="addCategoryModal" tabindex="-1" role="dialog"
         aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Create New Category </h5>
                    <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('category.store')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input id="supplier-id" type="hidden" value="">
                        <p>Please enter the name of your category.</p>
                        <input class="form-control" name="name" id="name" type="text" placeholder="Category Name">
                        <small class="text-info">**You will be able to assign categories to any assets on the system.
                                                 These can act as a filter.</small>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-grey" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-coral" type="button" id="confirmBtnStore">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- update Modal-->
    <div class="modal fade bd-example-modal-lg" id="updateCategoryModal" tabindex="-1" role="dialog"
         aria-labelledby="updateCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateCategoryModalLabel">Change Category Name </h5>
                    <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="updateForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <input id="supplier-id" type="hidden" value="">
                        <p>Please enter the name of your category.</p>
                        <input class="form-control" name="name" id="name" type="text" value="">
                        <small class="text-info">**You will be able to assign categories to any assets on the system.
                                                 These
                                                 can act as a filter.</small>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-grey" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-coral" type="button" id="updateFormButton">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-modals.delete :archive="true">Category</x-modals.delete>
@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}"></script>
    <script>
        const updateModal = new bootstrap.Modal(document.getElementById('updateCategoryModal'));
        document.querySelectorAll(".updateBtn").forEach(elem => elem.addEventListener("click", (e) => {
            e.preventDefault();
            let val = elem.getAttribute('data-id');
            let name = elem.getAttribute('data-name');
            let route = elem.getAttribute('data-route');
            document.querySelector('[name="name"]').value = name;
            document.querySelector('#updateForm').action = route;
            updateModal.show();
        }));
    </script>
@endsection
