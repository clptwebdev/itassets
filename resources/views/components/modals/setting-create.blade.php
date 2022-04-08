@props(['route'=>'/' ]){{--import modal--}}
<div class="modal fade bd-example-modal-lg" id="settingModal" tabindex="-1" role="dialog"
     aria-labelledby="settingModalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="settingModalModalLabel">Adding a new Setting</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{route('settings.create')}}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p>Fill Out the Fields To add a new setting to speak to your developer to Implement this
                       feature.</p>
                    <div class='form-group'>
                        <x-form.input name="name" formAttributes="required"/>
                    </div>
                    <div class='form-group'>
                        <x-form.input name="value" formAttributes="required"/>
                    </div>
                    <div class='form-group'>
                        <x-form.input name="priority" formAttributes="required"/>
                    </div>
                </div>

                <div class="modal-footer">

                    <button class="btn btn-grey" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-green">
                        Save
                    </button>
                @csrf
            </form>
        </div>
    </div>
</div>
