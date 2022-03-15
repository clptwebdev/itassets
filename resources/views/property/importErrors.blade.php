@extends('layouts.app')

@section('title', 'Accessory Import Errors')


@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')
{{--    <form action="/components/create/import" method="POST">--}}

        <div class="d-sm-flex align-items-center justify-content-between mb-4"><?php  ?>
            <h1 class="h3 mb-0 text-gray-800">Import Failures</h1>
            @php $errorRows = '';foreach($errorArray as $id => $key){ $errorRows = !empty($errorRows)? $errorRows.', '.$id:$id;}  @endphp

            <div>
                <form action="{{route('componentexport.import')}}" method="POST" class="d-inline">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" class="form-control " name="name"
                               id="name" placeholder="" value="{{htmlspecialchars(json_encode($valueArray))}}">
                    </div>
                    <button type="submit" class="d-inline-block btn btn-sm btn-yellow shadow-sm loading"><i
                            class="far fa-save fa-sm text-dark-50"></i> Download Errors
                    </button>
                </form>
                <a href="{{ route('accessories.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                        class="fas fa-chevron-left fa-sm te
                    xt-white-50"></i> Back to Consumables</a>
                <a id="import" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"><i
                        class="fas fa-download fa-sm text-white-50 fa-text-width"></i> Importing Help</a>
                <a onclick="javscript:checkErrors(this);" class="d-inline-block btn btn-sm btn-green shadow-sm"><i
                        class="far fa-save fa-sm text-white-50"></i> Save
                </a>
            </div>
        </div>

        <div class="m-3 alert alert-danger">You have several errors Within your Import in rows
            <div class="col-md-12">
                <div id="summary">
                    <p class="collapse" id="collapseSummary">{{$errorRows}}</p>
                    <a class="collapsed" data-toggle="collapse" href="#collapseSummary" aria-expanded="false"
                       aria-controls="collapseSummary"></a>
                </div>
            </div>
        </div>

        <x-handlers.alerts />

        <section>
            <p class="mb-4">There were some errors in your csv file that you have tried to upload to the system. Any rows that had valid information will have already been
                uploaded. If there a lot of returned errors you can choose to download them all in excel format allowing you to retry with only the failed rows. 
            </p>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">

                        <table id="categoryTable" class="table table-bordered">
                            <thead>
                            <tr>
                                <th><small>Name</small></th>
                                <th><small>Type</small></th>
                                <th><small>Location</small></th>
                                <th><small>Date</small></th>
                                <th><small>Cost</small></th>
                                <th><small>Depreciation</small></th>
                            </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th><small>Name</small></th>
                                    <th><small>Type</small></th>
                                    <th><small>Location</small></th>
                                    <th><small>Date</small></th>
                                    <th><small>Cost</small></th>
                                    <th><small>Depreciation</small></th>
                                </tr>
                            </tfoot>

                            @csrf
                            @php($line = 0)
                            @foreach($errorArray as $row =>$error)
                                <?php $errors = explode(",", $error); ?>
                                <tr>
                                    <td>
                                        <span id="name{{$line}}" class="tooltip-danger">
                                            <input type="text"
                                               class="import-control @if(in_array('name', $errors)){{ 'border-bottom border-danger'}}@endif" name="name[]"
                                               value="{{ $valueArray[$row]['name'] }}"
                                               placeholder="This Row is Empty Please Fill!" required data-container='#name{{$line}}' data-placement='top'
                                               @if(array_key_exists('name', $errorValues[$row])) {!! "data-toggle='tooltip' title='{$errorValues[$row]['name']}'" !!}@endif>
                                        </span>
                                    </td>
                                    <td>
                                        <span id="type{{$line}}" class="tooltip-danger">
                                            <input type="text"
                                               class="import-control @if(in_array('type', $errors)){{ 'border-bottom border-danger'}}@endif" name="model[]"
                                               value="{{ $valueArray[$row]['type'] }}"
                                               placeholder="This Row is Empty Please Fill!" required data-container='#type{{$line}}' data-placement='top'
                                               @if(array_key_exists('type', $errorValues[$row])) {!! "data-toggle='tooltip' title='{$errorValues[$row]['type']}'" !!}@endif>
                                        </span>
                                    </td>
                                    <td>
                                        <span id="location_id{{$line}}" class="tooltip-danger">
                                        <select type="dropdown" class="import-control @if(in_array('location', $errors)){{ 'border-bottom border-danger'}}@endif" name="location[]" required
                                        data-container='#location{{$line}}' data-placement='top'
                                        @if(array_key_exists('location_id', $errorValues[$row])) {!! "data-toggle='tooltip' title='{$errorValues[$row]['location_id']} - {$valueArray[$row]['location_id']}'" !!}@endif
                                        >
                                            <option value="0" @if($valueArray[$row]['location_id'] == ''){{'selected'}}@endif>Please Select a Location</option>
                                            @foreach($locations as $location)
                                                <option value="{{ $location->id  }}" @if( $valueArray[$row]['location_id'] == $location->name){{'selected'}}@endif>{{ $location->name }}</option>
                                            @endforeach
                                        </select>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        try {
                                            $date = \Carbon\Carbon::parse(str_replace('/', '-', $valueArray[$row]['purchased_date']))->format('Y-m-d');
                                        } catch (\Exception $e) {
                                            $date = 'dd/mm/yyyy';
                                        }
                                        ?>
                                        <span id="purchased_date{{$line}}" class="tooltip-danger">
                                        <input type="date"
                                               class="import-control @if(in_array('purchased_date', $errors)){{ 'border-bottom border-danger'}}@endif" name="purchased_date[]"
                                               id="purchased_date" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $date }}" required data-container='#purchased_date{{$line}}' data-placement='top'
                                               @if(array_key_exists('purchased_date', $errorValues[$row])) {!! "data-toggle='tooltip' title='{$errorValues[$row]['purchased_date']}'" !!}
                                               @endif
                                               >
                                        </span>

                                    </td>
                                    <td>
                                        <span id="purchased_cost{{$line}}" class="tooltip-danger">
                                        <input type="text"
                                               class="import-control @if(in_array('purchased_cost', $errors)){{'border-bottom border-danger'}}@endif"
                                               name="purchased_cost[]"
                                               id="purchased_cost" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['purchased_cost'] }}" required data-container='#purchased_cost{{$line}}' data-placement='top'
                                               @if(array_key_exists('purchased_cost', $errorValues[$row])) {!! "data-toggle='tooltip'  title='{$errorValues[$row]['purchased_cost']}'" !!}@endif
                                        >
                                        </span>
                                    </td>
                                    <td>
                                        <span id="depreciation{{$line}}" class="tooltip-danger">
                                        <input type="text" class="import-control @if(in_array('depreciation', $errors)){{ 'border-bottom border-danger'}}@endif" name="depreciation[]" id="order_no" placeholder="This Row is Empty Please Fill!"
                                            value="{{ $valueArray[$row]['depreciation'] }}" required data-container='#depreciation{{$line}}' data-placement='top'
                                            @if(array_key_exists('depreciation', $errorValues[$row])) {!! "data-toggle='tooltip'  title='{$errorValues[$row]['depreciation']}'" !!}@endif
                                        >
                                        </span>
                                    </td>
                                    
                                </tr>
                                @php($line++)
        @endforeach
{{--    </form>--}}
    </tbody>
    </table>
    </div>
    </div>
    </div>

    </section>

@endsection

@section('modals')
    <div class="modal fade bd-example-modal-lg" id="importHelpModal" tabindex="-1" role="dialog"
         aria-labelledby="importHelpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importHelpModalLabel">Importing Data Help</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="/importmanufacturer" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <h2 class="h3 mb-0 text-gray-800">Requirements needed to finish your import</h2>
                       <ol>
                           <li>The Required fields are: Name, Supplier,Location and serial num.</li>
                           <li>All Correct rows skip this page and import straight to the database so please don't re-import your file!</li>
                           <li>Struggling to Pass this stage are all your data fields in the correct format?</li>
                           <li>Need More help? Click <a href="{{route("documentation.index").'#collapseSevenImport'}}">here</a> to be redirected to the Documentation on Importing!</li>

                       </ol>
                    </div>
                    <div class="modal-footer">
                        <p>For Anymore information please email Apollo@clpt.co.uk</p>
                        <a href="https://clpt.sharepoint.com/:x:/s/WebDevelopmentTeam/ERgeo9FOFaRIvmBuTRVcvycBkiTnqHf3aowELiOt8Hoi1Q?e=CXfTdb" target="_blank" class="btn btn-blue" >
                            Download Import Template
                        </a>
                        <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                    @csrf
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
        

        $('#import').click(function () {
            $('#importManufacturerModal').modal('show')
        })

        //validation
        function checkErrors(obj){

            var token = $("[name='_token']").val();
            var data = new FormData();
            data.append('_token', token);

            //Names
            var inputs = $("input[name='name[]']").get();
            inputs.forEach(element => {
                data.append('name[]', element.value);
            });

            //Names
            var mdInputs = $("input[name='name[]']").get();
            mdInputs.forEach(element => {
                data.append('model[]', element.value);
            });

            //status
            var stInputs = $("select[name='status_id[]']").get();
            stInputs.forEach(element => {
                data.append('status_id[]', element.value);
            });

            //Phone
            var supInputs = $("select[name='supplier_id[]']").get();
            supInputs.forEach(element => {
                data.append('supplier_id[]', element.value);
            });

            //Email
            var maInputs = $("select[name='manufacturer_id[]']").get();
                maInputs.forEach(element => {
                data.append('manufacturer_id[]', element.value);
            });

            var loInputs = $("select[name='location_id[]']").get();
                loInputs.forEach(element => {
                data.append('location_id[]', element.value);
            });

            var roInputs = $("input[name='room[]']").get();
                roInputs.forEach(element => {
                data.append('room[]', element.value);
            });

            var orInputs = $("input[name='order_no[]']").get();
                orInputs.forEach(element => {
                data.append('order_no[]', element.value);
            });

            var seInputs = $("input[name='serial_no[]']").get();
                seInputs.forEach(element => {
                data.append('serial_no[]', element.value);
            });

            var pcInputs = $("input[name='purchased_cost[]']").get();
                pcInputs.forEach(element => {
                data.append('purchased_cost[]', element.value);
            });

            var doInputs = $("select[name='donated[]']").get();
                doInputs.forEach(element => {
                data.append('donated[]', element.value);
            });

            var pdInputs = $("input[name='purchased_date[]']").get();
                pdInputs.forEach(element => {
                data.append('purchased_date[]', element.value);
            });

            var dpInputs = $("select[name='depreciation_id[]']").get();
                dpInputs.forEach(element => {
                data.append('depreciation_id[]', element.value);
            });

            var waInputs = $("input[name='warranty[]']").get();
                waInputs.forEach(element => {
                data.append('warranty[]', element.value);
            });

            var noInputs = $("input[name='notes[]']").get();
                noInputs.forEach(element => {
                data.append('notes[]', element.value);
            });

            $.ajax({
                url: '/accessories/create/ajax',
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                success: function(response){
                    if(response === 'Success'){
                        window.location.href = '/accessories';
                    }else{
                        $('.import-control').removeClass('border-danger');
                        $('.import-control').removeClass('border-bottom');
                        $('.import-control').tooltip('dispose');
                        var i = 0;
                        Object.entries(response).forEach(entry => {
                            const [key, value] = entry;
                            res = key.split('.');
                            const error = value.toString().replace(key, res[0]);
                            $(`[name='${res[0]}[]']:eq(${res[1]})`).addClass('border-bottom');
                            $(`[name='${res[0]}[]']:eq(${res[1]})`).addClass('border-danger');
                            $(`[name='${res[0]}[]']:eq(${res[1]})`).attr('data-toggle', 'tooltip');
                            $(`[name='${res[0]}[]']:eq(${res[1]})`).attr('title', error);
                            $(`[name='${res[0]}[]']:eq(${res[1]})`).tooltip();
                            i++;
                        });
                        $('.alert.alert-danger').html(`There were ${i} errors in the following rows`);
                    }
                },
            });
        }

    </script>
@endsection
