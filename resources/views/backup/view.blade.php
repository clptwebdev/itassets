@extends('layouts.app')

@section('title', 'Backups')

@section('css')

@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Database Backups</h1>
        <div>
            @can('view' , \App\Models\Backup::class)
                <a href="{{route("backupdb.create")}}" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"
                   id="backup1"><i class="fas fa-plus fa-sm text-white-50"></i> Create a Database BackUp</a>
                <a href="{{route("backup.create")}}" class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm"
                   id="backup2"><i class="fas fa-plus fa-sm text-white-50"></i> Create a Full BackUp</a>
                <a href="{{route("backup.clean")}}" class="d-none d-sm-inline-block btn btn-sm btn-coral shadow-sm"
                   id="backup3"><i class="fas fa-trash fa-sm text-white-50"></i>Clear the BackUp Folder</a>
            @endcan

        </div>
    </div>

    @if(session('danger_message'))
        <div class="alert alert-danger"> {{ session('danger_message')}} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {{ session('success_message')}} </div>
    @endif

    <section>
        <p class="mb-4">Below are different tiles, one for each Backup stored in the management system. Each tile
                        has different Date that can be downloaded.These are taken daily and have dates linked to when
                        they were
                        created.</p>

        <div class="row">
            @foreach($files as $file_name)
                <div class="col-xl-3 col-md-4 mb-4">
                    <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid;">
                        <div class="card-body">
                            <div class="row no-gutters">
                                <div class="col mr-2">
                                    <div class="mb-1">
                                        <h6 class="m-0 font-weight-bold mb-3">{{$file_name}}</h6>
                                        <a href="{{ asset('/storage/backups/Apollo-backup/'.$file_name)}}"
                                           class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm backup">Download
                                                                                                                  This
                                                                                                                  Zip</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

@endsection

@section('modals')
    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
         aria-hidden="true" id="backupModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <button class="btn btn-primary" type="button" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('.backup').click(function () {
            //showModal
            $('#backupModal').modal('show')
        });
    </script>
@endsection
