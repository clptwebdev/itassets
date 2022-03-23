@props(['models'=>null])
<!-- concern-log Modal-->
<div class="modal fade bd-example-modal-lg" id="roleAddModal" tabindex="-1" role="dialog"
     aria-labelledby="roleAddModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-blue" id="roleAddModalLabel">Add a Role:</h5>
                <button class="btn-light btn" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <x-form.layout action="{{route('role.create')}}">
                <div>
                    <div class="modal-body">
                        <p>Please fill in the details below to add a Role</p>
                        <x-form.input formAttributes="required" name="name" title="Name of this Role?"/>
                    </div>
                </div>
                <div class='container-sm card card-body '>
                    <table class="table ">
                        <thead class="thead-dark bg-grey text-white">
                        <tr>
                            <th scope="col">Model</th>
                            <th scope="col">Create</th>
                            <th scope="col">Read</th>
                            <th scope="col">Update</th>
                            <th scope="col">delete</th>
                            <th scope="col">Archive</th>
                            <th scope="col">Transfer</th>
                            <th scope="col">Request</th>
                            <th scope="col">Spec_reports</th>
                            <th scope="col">Fin_reports</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr class='table-bordered '>
                            <th scope="row" class='text-primary'>Bulk Selections</th>
                            <td><input type="checkbox" onclick='createTag()' id="createToggle"></td>
                            <td><input type="checkbox" onclick='readTag()' id="readToggle"></td>
                            <td><input type="checkbox" onclick='updateTag()' id="updateToggle"></td>
                            <td><input type="checkbox" onclick='deleteTag()' id="deleteToggle"></td>
                            <td><input type="checkbox" onclick='archiveTag()' id="archiveToggle"></td>
                            <td><input type="checkbox" onclick='transferTag()' id="transferToggle"></td>
                            <td><input type="checkbox" onclick='requestTag()' id="requestToggle"></td>
                            <td><input type="checkbox" onclick='specTag()' id="spec_reportsToggle"></td>
                            <td><input type="checkbox" onclick='finTag()' id="fin_reportsToggle"></td>
                        </tr>
                        @foreach($models as $model)
                            <tr>
                                <th scope="row">{{$model}}
                                    <x-form.input :value="$model" name="models[]" :label="false" type="hidden"/>
                                </th>
                                <td><input type="checkbox" value='{{$model}}' name="create[]" id='create'></td>
                                <td><input type="checkbox" value='{{$model}}' name="read[]" id='read'></td>
                                <td><input type="checkbox" value='{{$model}}' name="update[]" id='update'></td>
                                <td><input type="checkbox" value='{{$model}}' name="delete[]" id='delete'></td>
                                <td><input type="checkbox" value='{{$model}}' name="archive[]" id='archive'></td>
                                <td><input type="checkbox" value='{{$model}}' name="transfer[]" id='transfer'></td>
                                <td><input type="checkbox" value='{{$model}}' name="request[]" id='request'></td>
                                <td><input type="checkbox" value='{{$model}}' name="spec_reports[]" id='spec_reports'>
                                </td>
                                <td><input type="checkbox" value='{{$model}}' name="fin_reports[]" id='fin_reports'>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <a href='#' class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"
                       data-bs-dismiss='modal'><i class="fas fa-undo-alt fa-sm pl-1 pr-1"></i> Cancel</a>
                    <button type='submit' class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                            class="fas fa-undo-alt fa-sm pl-1 pr-1"></i> Submit
                    </button>
                </div>
            </x-form.layout>
        </div>
    </div>
</div>
