@extends('layouts.app')

@section('css')

@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">View Asset</h1>
        <div>
            <a href="{{ route('assets.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                    class="fas fa-chevron-left fa-sm text-white-50"></i> Back</a>
            <a href="{{ route('asset.showPdf', $asset->id)}}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                        class="fas fa-file-pdf fa-sm text-white-50"></i> Generate Report</button>
            <a href="{{ route('assets.edit', $asset->id)}}"
               class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm"><i
                    class="fas fa-plus fa-sm text-white-50"></i> Edit</a>
            <form class="d-inline-block" id="form{{$asset->id}}" action="{{ route('assets.destroy', $asset->id) }}"
                method="POST">
            @csrf
            @method('DELETE')
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm deleteBtn" data-id="{{$asset->id}}"><i
                    class="fas fa-trash fa-sm text-white-50"></i> Delete</a>
            </form>  
        </div>          
    </div>

    @if(session('danger_message'))
        <div class="alert alert-danger"> {{ session('danger_message')}} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {{ session('success_message')}} </div>
    @endif

    <div class="row row-eq-height">
        <x-assets.asset-modal :asset="$asset" />
        <x-assets.asset-purchase :asset="$asset" />
    </div>
    
    <div class="row row-eq-height ">
        <x-locations.location-modal :asset="$asset"/>
        <x-manufacturers.manufacturer-modal :asset="$asset"/>
        <x-assets.asset-log :asset="$asset"/>
    </div>

@endsection

@section('modals')

    <!-- User Delete Modal-->
    <div class="modal fade bd-example-modal-lg" id="removeassetModal" tabindex="-1" role="dialog"
         aria-labelledby="removeassetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeassetModalLabel">Are you sure you want to delete this asset?
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="asset-id" type="hidden" value="">
                    <p>Select "Delete" to remove this asset from the system.</p>
                    <small class="text-danger">**Warning this is permanent. All assets assigned to this asset will be
                        set to Null.</small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" type="button" id="confirmBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
        $('.deleteBtn').click(function () {
            $('#asset-id').val($(this).data('id'))
            //showModal
            $('#removeassetModal').modal('show')
        });

        $('#confirmBtn').click(function () {
            var form = '#' + 'form' + $('#asset-id').val();
            $(form).submit();
        });

        $(document).ready(function () {
            $('#assetsTable').DataTable({
                "columnDefs": [{
                    "targets": [0, 5],
                    "orderable": false,
                }],
                "order": [[1, "asc"]]
            });
        });
    </script>

@endsection
