@props(['route'=>'/' , 'templateLink'=>'https://clpt.sharepoint.com/sites/WebDevelopmentTeam/Shared%20Documents/Forms/AllItems.aspx?id=%2Fsites%2FWebDevelopmentTeam%2FShared%20Documents%2FSystems%2FSystems%2FImport%20Template%20Files&viewid=5add7e78%2D1a32%2D45b0%2D8e87%2Df6719cd7331d']){{--import modal--}}
<div class="modal fade bd-example-modal-lg" id="importManufacturerModal" tabindex="-1" role="dialog"
     aria-labelledby="importManufacturerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importManufacturerModalLabel">Importing Data</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{$route}}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p>Select "import" to add items to the system.</p>
                    <input id="importEmpty" class="form-control" type="file" placeholder="Upload here" name="csv"
                           accept=".csv">

                </div>

                <div class="modal-footer">
                    <x-handlers.alerts/>
                    <a href="https://clpt.sharepoint.com/:x:/s/documents/EcF-3FZ4n4NBpiC64ndDTK8BZ4RKoqdtypJPOxL5uIWFxw"
                       target="_blank" class="btn btn-blue">
                        Download Import Template
                    </a>
                    <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>

                    <button type="submit" class="btn btn-green" type="button" id="confirmBtnImport">
                        Import
                    </button>
                @csrf
            </form>
        </div>
    </div>
</div>
