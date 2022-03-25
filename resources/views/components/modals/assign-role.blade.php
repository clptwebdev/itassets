@props(['roles'=>\App\Models\Role::all() , 'user'=>\App\Models\User::all()])
<!-- concern-log Modal-->
<div class="modal fade bd-example-modal-lg" id="roleSyncModal" tabindex="-1" role="dialog"
     aria-labelledby="roleSyncModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-blue" id="roleSyncModalLabel">Sync a Role to a User:</h5>
                <button class="btn-light btn" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <x-form.layout :action="route('role.sync')">
                <div class="modal-body">
                    <p>Please select the User below to Assign a Role to a User.</p>
                    <div class="form-group">
                        <x-form.select name='user' :models="$user"/>
                        {{--get value on user and get role and place in selected (js)--}}
                    </div>
                    <div class="form-group">
                        <x-form.select name='role' :models="$roles"/>
                    </div>
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

