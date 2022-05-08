@props(['route'=>"#" , 'model'=>null , 'title' => ''])
<!-- Status Model Modal-->
<div class="modal fade bd-example-modal-lg" id="managerModal" tabindex="-1" role="dialog"
     aria-labelledby="managerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="managerModalLabel">Change A Users Manager</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{$route}}" method="post">
                <div class="modal-body">
                    @csrf
                    <input id='currentUser' type='hidden' value='' name='selectedUser'/>
                    <x-form.select name="manager_id" :models="$model"/>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-grey" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-green" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
