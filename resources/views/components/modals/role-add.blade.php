@props(['models'=>null])
<!-- concern-log Modal-->
<div class="modal fade bd-example-modal-lg" id="roleAddModal" tabindex="-1" role="dialog"
     aria-labelledby="roleAddModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-blue" id="roleAddModalLabel">Add a Role:</h5>
                <button class="btn-light btn" type="button" data-dismiss="modal" aria-label="Close">
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
                        @php unset($models[array_search('Permission' , $models)]); @endphp
                        {{--                        @php unset($models[array_search('Archive' , $models)]); @endphp--}}
                        @php unset($models[array_search('Consumable' , $models)]); @endphp
                        {{--                        @php unset($models[array_search('Requests' , $models)]); @endphp--}}
                        @foreach($models as $model)


                            <tr>
                                <th scope="row">{{$model}}
                                    <x-form.input :value="$model" name="models[]" :label="false" type="hidden"/>
                                </th>
                                <td><input type="checkbox" value='{{$model}}' name="create[]"></td>
                                <td><input type="checkbox" value='{{$model}}' name="read[]"></td>
                                <td><input type="checkbox" value='{{$model}}' name="update[]"></td>
                                <td><input type="checkbox" value='{{$model}}' name="delete[]"></td>
                                <td><input type="checkbox" value='{{$model}}' name="archive[]"></td>
                                <td><input type="checkbox" value='{{$model}}' name="transfer[]"></td>
                                <td><input type="checkbox" value='{{$model}}' name="request[]"></td>
                                <td><input type="checkbox" value='{{$model}}' name="spec_reports[]"></td>
                                <td><input type="checkbox" value='{{$model}}' name="fin_reports[]"></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <a href='#' class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"
                       data-dismiss='modal'><i class="fas fa-undo-alt fa-sm pl-1 pr-1"></i> Cancel</a>
                    <button type='submit' class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                            class="fas fa-undo-alt fa-sm pl-1 pr-1"></i> Submit
                    </button>
                </div>
            </x-form.layout>
        </div>
    </div>
</div>
