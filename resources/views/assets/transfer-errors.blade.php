@extends('layouts.app')@section('title', 'View Transfer Errors')
@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4"><?php  ?>
        <h1 class="h3 mb-0 text-gray-800">Transfer Import Failures</h1>
        <div>
            <form action="{{ route('export.transfer.errors')}}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="hidden" class="import-control " name="assets" id="assets" placeholder=""
                           value="{{htmlspecialchars(json_encode($valueArray))}}">
                </div>
                @can('viewAll' ,\App\Models\Asset::class)
                    <a href="{{ route('assets.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                            class="fas fa-chevron-left fa-sm text-white-50"></i> Back to assets</a>
                @endcan
                <button type="submit" class="d-inline-block btn btn-sm btn-green shadow-sm"><i
                        class="far fa-save fa-sm text-white-50"></i> Download Errors
                </button>
            </form>
        </div>
    </div>

    @php $errorRows = '';foreach($errorArray as $id => $key){ $errorRows = !empty($errorRows)? $errorRows.', '.$id:$id;}  @endphp
    <div class="m-3 alert alert-danger">
        You
        have @if(count($errorArray) > 1){{ count($errorArray).' errors'}}@else {{  count($errorArray).' error' }}@endif
        within your Assets Import. You have errors
        on rows {{ $errorRows}}

    </div>

    {{-- Error and Success Messages --}}
    <x-handlers.alerts/>

    <section>
        <p class="mb-4">You have some errors on your transfer import. They are all displayed below, you cannot edit the
                        errors here but instead have to download the errors
                        and import the failed rows. Click the download errors in the top right hand corner, this only
                        downloads the failed row. All the rows that have been success will
                        have been submitted already. </p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="categoryTable" class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center"><small>ID</small></th>
                            <th class="text-center"><small>Asset Tag</small></th>
                            <th class="text-center"><small>New Tag</small></th>
                            <th class="text-center"><small>Serial Num</small></th>
                            <th class="text-center"><small>Location From</small></th>
                            <th class="text-center"><small>Location To</small></th>
                            <th class="text-center"><small>Date</small></th>
                            <th class="text-center"><small>Notes</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="text-center"><small>ID</small></th>
                            <th class="text-center"><small>Asset Tag</small></th>
                            <th class="text-center"><small>New Tag</small></th>
                            <th class="text-center"><small>Serial Num</small></th>
                            <th class="text-center"><small>Location From</small></th>
                            <th class="text-center"><small>Location To</small></th>
                            <th class="text-center"><small>Date</small></th>
                            <th class="text-center"><small>Notes</small></th>
                        </tr>
                        </tfoot>

                        @csrf
                        @php($line = 0)
                        @foreach($errorArray as $row =>$error)
                            <?php $errors = explode(",", $error); ?>
                            <tr>
                                <td>
                                    <span id="id{{$line}}" class="tooltip-danger">
                                        <input type="text" maxlength="11"
                                               class="import-control <?php if (in_array('id', $errors)) {?>border-bottom border-danger<?php }?>"
                                               value="{{ $valueArray[$row]['id'] }}" placeholder="" required
                                               data-container='#id{{$line}}' data-placement='top'
                                           @if(array_key_exists('id', $errorValues[$row])) {!! "data-toggle='tooltip' title='{$errorValues[$row]['id']}'" !!}@endif>
                                    </span>
                                </td>
                                <td>
                                    <span id="asset_tag{{$line}}" class="tooltip-danger">
                                        <input type="text" maxlength="11"
                                               class="import-control <?php if (in_array('asset_tag', $errors)) {?>border-bottom border-danger<?php }?>"
                                               value="{{ $valueArray[$row]['asset_tag'] }}" placeholder="" required
                                               data-container='#asset_tag{{$line}}' data-placement='top'
                                           @if(array_key_exists('asset_tag', $errorValues[$row])) {!! "data-toggle='tooltip' title='{$errorValues[$row]['asset_tag']}'" !!}@endif>
                                    </span>
                                </td>
                                <td>
                                    <span id="new_tag{{$line}}" class="tooltip-danger">
                                        <input type="text" maxlength="11"
                                               class="import-control <?php if (in_array('new_tag', $errors)) {?>border-bottom border-danger<?php }?>"
                                               value="{{ $valueArray[$row]['new_tag'] }}" placeholder="" required
                                               data-container='#new_tag{{$line}}' data-placement='top'
                                           @if(array_key_exists('new_tag', $errorValues[$row])) {!! "data-toggle='tooltip' title='{$errorValues[$row]['new_tag']}'" !!}@endif>
                                    </span>
                                </td>
                                <td>
                                    <span id="serial_no{{$line}}" class="tooltip-danger">
                                        <input type="text"
                                               class="import-control <?php if (in_array('serial_no', $errors)) {?>border-bottom border-danger<?php }?>"
                                               value="{{ $valueArray[$row]['serial_no'] }}" placeholder="" required
                                               data-container='#serial_no{{$line}}' data-placement='top'
                                           @if(array_key_exists('serial_no', $errorValues[$row])) {!! "data-toggle='tooltip' title='{$errorValues[$row]['serial_no']}'" !!}@endif>
                                    </span>
                                </td>
                                <td>
                                    <span id="location_from{{$line}}" class="tooltip-danger">
                                        <input type="text"
                                               class="import-control <?php if (in_array('location_from', $errors)) {?>border-bottom border-danger<?php }?>"
                                               value="{{ $valueArray[$row]['location_from'] }}" placeholder="" required
                                               data-container='#location_from{{$line}}' data-placement='top'
                                           @if(array_key_exists('location_from', $errorValues[$row])) {!! "data-toggle='tooltip' title='{$errorValues[$row]['location_from']}'" !!}@endif>
                                    </span>
                                </td>
                                <td>
                                    <span id="location_to{{$line}}" class="tooltip-danger">
                                        <input type="text"
                                               class="import-control <?php if (in_array('location_to', $errors)) {?>border-bottom border-danger<?php }?>"
                                               value="{{ $valueArray[$row]['location_to'] }}" placeholder="" required
                                               data-container='#location_to{{$line}}' data-placement='top'
                                           @if(array_key_exists('location_to', $errorValues[$row])) {!! "data-toggle='tooltip' title='{$errorValues[$row]['location_to']}'" !!}@endif>
                                    </span>
                                </td>
                                <td>
                                    <span id="date{{$line}}" class="tooltip-danger">
                                        <input type="text"
                                               class="import-control <?php if (in_array('date', $errors)) {?>border-bottom border-danger<?php }?>"
                                               value="{{ $valueArray[$row]['date'] }}" placeholder="" required
                                               data-container='#date{{$line}}' data-placement='top'
                                           @if(array_key_exists('date', $errorValues[$row])) {!! "data-toggle='tooltip' title='{$errorValues[$row]['date']}'" !!}@endif>
                                    </span>
                                </td>
                                <td>
                                    <span id="notes{{$line}}" class="tooltip-danger">
                                        <input type="text"
                                               class="import-control <?php if (in_array('notes', $errors)) {?>border-bottom border-danger<?php }?>"
                                               value="{{ $valueArray[$row]['notes'] }}" placeholder="" required
                                               data-container='#notes{{$line}}' data-placement='top'
                                           @if(array_key_exists('notes', $errorValues[$row])) {!! "data-toggle='tooltip' title='{$errorValues[$row]['notes']}'" !!}@endif>
                                    </span>
                                </td>
                            </tr>
                            @php($line++)
                        @endforeach

                    </table>
                </div>
            </div>
        </div>

    </section>

@endsection

@section('modals')
@endsection

@section('js')
@endsection
