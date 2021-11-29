<div class="row">
    <div class="col-6">
        <div class="card shadow mb-4 h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Categories</h6>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th class="text-center"><i class="fas fa-fw fa-tablet-alt"></i> <small
                                class="d-none d-lg-inline-block">Assets</small></th>
                        <th class="text-center"><i class="fas fa-fw fa-keyboard"></i> <small
                                class="d-none d-lg-inline-block">Accessories</small></th>
                        <th class="text-center"><i class="fas fa-fw fa-hdd"></i> <small
                                class="d-none d-lg-inline-block">Components</small></th>
                        <th class="text-center"><i class="fas fa-fw fa-tint"></i> <small
                                class="d-none d-lg-inline-block">Consumables</small></th>
                        <th class="text-center"><i class="fas fa-fw fa-question"></i> <small
                                class="d-none d-lg-inline-block">Miscellanea</small></th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <td colspan="6" class="text-center"><a href="{{ route('status.index')}}" class="btn btn-green">View Statuses</a></td>
                    </tr>
                    </tfoot>
                    <tbody>
                    {{--                    add where top 6 largest--}}
                    @foreach($category as $cat)
                        <tr>
                        <tr>
                            <td>{{ $cat->name }}</td>
                            <td class="text-center">{{$cat->assets->count()}}</td>
                            <td class="text-center">{{$cat->accessories->count()}}</td>
                            <td class="text-center">{{$cat->components->count()}}</td>
                            <td class="text-center">{{$cat->consumables->count()}}</td>
                            <td class="text-center">{{$cat->miscellanea->count()}}</td>
                        </tr>
                        </tr>
                    </tbody>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card shadow mb-4 h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Status</h6>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th class="text-center"><i class="fas fa-fw fa-tablet-alt"></i> <small
                                class="d-none d-lg-inline-block">Assets</small></th>
                        <th class="text-center"><i class="fas fa-fw fa-keyboard"></i> <small
                                class="d-none d-lg-inline-block">Accessories</small></th>
                        <th class="text-center"><i class="fas fa-fw fa-hdd"></i> <small
                                class="d-none d-lg-inline-block">Components</small></th>
                        <th class="text-center"><i class="fas fa-fw fa-tint"></i> <small
                                class="d-none d-lg-inline-block">Consumables</small></th>
                        <th class="text-center"><i class="fas fa-fw fa-question"></i> <small
                                class="d-none d-lg-inline-block">Miscellanea</small></th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <td colspan="6" class="text-center"><a href="{{ route('status.index')}}" class="btn btn-green">View Statuses</a></td>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($statuses as $status)
                        <tr>
                            <td>{{ $status->name }}</td>
                            <td class="text-center">{{$status->assets->count()}}</td>
                            <td class="text-center">{{$status->accessory->count()}}</td>
                            <td class="text-center">{{$status->components->count()}}</td>
                            <td class="text-center">{{$status->consumable->count()}}</td>
                            <td class="text-center">{{$status->miscellanea->count()}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
