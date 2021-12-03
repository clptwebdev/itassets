<!-- import-->
<div class="modal fade bd-example-modal-xl" id="exportModal" tabindex="-1" role="dialog"
     aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header mb-4">
                <h5 class="modal-title" id="exportModalLabel">Choose Options</h5>
                <button class="btn btn-light" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <ul class="nav nav-tabs flex-column flex-sm-row justify-content-center">
                <li class="nav-item ml-2">
                    <a class="nav-link active list-group-item" aria-current="page" href="#" id="all-tab" data-toggle="tab"
                       data-target="#all"
                       role="tab" aria-controls="all" aria-selected="true">By Accessories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link list-group-item" href="#" id="year-tab" data-toggle="tab" data-target="#year" role="tab"
                       aria-controls="year" aria-selected="false">By Assets</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link list-group-item" href="#" id="form-tab" data-toggle="tab" data-target="#form" role="tab"
                       aria-controls="form" aria-selected="false">By Components</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link list-group-item" href="#" id="teacher-tab" data-toggle="tab" data-target="#teacher"
                       role="tab" aria-controls="teacher" aria-selected="false">By Miscellaneous</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active p-4" id="all" role="tabpanel" aria-labelledby="all-tab">
                    <form action="{{route("settings.accessories")}}" method="POST" enctype="multipart/form-data">
{{--                        accessories--}}
                        @csrf
                        <div class="row m-auto d-flex justify-content-start align-items-center">
                            <h3 class="text-center text-sm-center d-inline-block col-12 mb-2">Status</h3>

                        @foreach($statuses as $status)
                                <div class="col-2">
                                    <input type="checkbox" value="{{ $status->id }}" name="status[]"
                                           id="status{{$status->name}}">
                                    <label for="status{{$status->name}}">{{ $status->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <div class="row m-auto d-flex justify-content-start align-items-center">
                                <h3 class="text-center text-sm-center d-inline-block col-12 mb-2">Category</h3>
                            @foreach($categories as $category)
                                <div class="col-2">
                                    <input type="checkbox" value="{{ $category->id }}" name="category[]"
                                           id="category{{$category->name}}">
                                    <label for="category{{$category->name}}">{{ $category->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                            <div class="row m-auto d-flex justify-content-start align-items-center">
                                <h3 class="text-center text-sm-center d-inline-block col-12 mb-2">location</h3>
                                @foreach($locations as $location)
                                <div class="col-2">
                                    <input type="checkbox" value="{{ $location->id }}" name="location[]"
                                           id="location{{$location->name}}">
                                    <label for="location{{$location->name}}">{{ $location->name }}</label>
                                </div>
                                @endforeach
                            </div>

                        <div class="d-flex justify-content-center align-items-center g-3 m-auto mt-4">
                            <button type="submit" class="btn btn-primary m-2"
                                    type="button">
                                Get Accessories
                            </button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade p-4" id="year" role="tabpanel" aria-labelledby="year-tab">
                    <form action="{{route("settings.assets")}}" method="POST" enctype="multipart/form-data">
{{--                        assets--}}
                        @csrf
                        <div class="row m-auto d-flex justify-content-start align-items-center">
                            <h3 class="text-center text-sm-center d-inline-block col-12 mb-2">Asset Models</h3>

                            @foreach($assetModel as $model)
                                <div class="col-2">
                                    <input type="checkbox" value="{{ $model->id }}" name="model[]"
                                           id="model{{$model->name}}">
                                    <label for="model{{$model->name}}">{{ $model->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <hr>
                        <div class="row m-auto d-flex justify-content-start align-items-center">
                            <h3 class="text-center text-sm-center d-inline-block col-12 mb-2">Status</h3>

                            @foreach($statuses as $status)
                                <div class="col-2">
                                    <input type="checkbox" value="{{ $status->id }}" name="status[]"
                                           id="status{{$status->name}}">
                                    <label for="status{{$status->name}}">{{ $status->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <div class="row m-auto d-flex justify-content-start align-items-center">
                            <h3 class="text-center text-sm-center d-inline-block col-12 mb-2">Category</h3>
                            @foreach($categories as $category)
                                <div class="col-2">
                                    <input type="checkbox" value="{{ $category->id }}" name="category[]"
                                           id="category{{$category->name}}">
                                    <label for="category{{$category->name}}">{{ $category->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <div class="row m-auto d-flex justify-content-start align-items-center">
                            <h3 class="text-center text-sm-center d-inline-block col-12 mb-2">location</h3>
                            @foreach($locations as $location)
                                <div class="col-2">
                                    <input type="checkbox" value="{{ $location->id }}" name="location[]"
                                           id="location{{$location->name}}">
                                    <label for="location{{$location->name}}">{{ $location->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center align-items-center g-3 m-auto mt-4">
                            <button type="submit" class="btn btn-primary m-2"
                                    type="button">
                                Get Assets
                            </button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade p-4" id="form" role="tabpanel" aria-labelledby="form-tab">
                    <form action="{{route("settings.components")}}" method="POST" enctype="multipart/form-data">
{{--                        components--}}
                        @csrf
                        <div class="row m-auto d-flex justify-content-start align-items-center">
                            <h3 class="text-center text-sm-center d-inline-block col-12 mb-2">Status</h3>

                            @foreach($statuses as $status)
                                <div class="col-2">
                                    <input type="checkbox" value="{{ $status->id }}" name="status[]"
                                           id="status{{$status->name}}">
                                    <label for="status{{$status->name}}">{{ $status->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <div class="row m-auto d-flex justify-content-start align-items-center">
                            <h3 class="text-center text-sm-center d-inline-block col-12 mb-2">Category</h3>
                            @foreach($categories as $category)
                                <div class="col-2">
                                    <input type="checkbox" value="{{ $category->id }}" name="category[]"
                                           id="category{{$category->name}}">
                                    <label for="category{{$category->name}}">{{ $category->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <div class="row m-auto d-flex justify-content-start align-items-center">
                            <h3 class="text-center text-sm-center d-inline-block col-12 mb-2">location</h3>
                            @foreach($locations as $location)
                                <div class="col-2">
                                    <input type="checkbox" value="{{ $location->id }}" name="location[]"
                                           id="location{{$location->name}}">
                                    <label for="location{{$location->name}}">{{ $location->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center align-items-center g-3 m-auto mt-4">
                            <button type="submit" class="btn btn-primary m-2"
                                    type="button">
                                Get Components
                            </button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade p-4" id="teacher" role="tabpanel" aria-labelledby="teachers-tab">
                    <form action="{{route("settings.miscellaneous")}}" method="POST" enctype="multipart/form-data">
{{--                        misc--}}
                        @csrf
                        <div class="row m-auto d-flex justify-content-start align-items-center">
                            <h3 class="text-center text-sm-center d-inline-block col-12 mb-2">Status</h3>

                            @foreach($statuses as $status)
                                <div class="col-2">
                                    <input type="checkbox" value="{{ $status->id }}" name="status[]"
                                           id="status{{$status->name}}">
                                    <label for="status{{$status->name}}">{{ $status->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <div class="row m-auto d-flex justify-content-start align-items-center">
                            <h3 class="text-center text-sm-center d-inline-block col-12 mb-2">Category</h3>
                            @foreach($categories as $category)
                                <div class="col-2">
                                    <input type="checkbox" value="{{ $category->id }}" name="category[]"
                                           id="category{{$category->name}}">
                                    <label for="category{{$category->name}}">{{ $category->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <div class="row m-auto d-flex justify-content-start align-items-center">
                            <h3 class="text-center text-sm-center d-inline-block col-12 mb-2">location</h3>
                            @foreach($locations as $location)
                                <div class="col-2">
                                    <input type="checkbox" value="{{ $location->id }}" name="location[]"
                                           id="location{{$location->name}}">
                                    <label for="location{{$location->name}}">{{ $location->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center align-items-center g-3 m-auto mt-4">
                            <button type="submit" class="btn btn-primary m-2"
                                    type="button">
                                Get Miscellaneous
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


