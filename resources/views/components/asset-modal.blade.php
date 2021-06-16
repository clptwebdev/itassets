@props(["asset"])
<section>
    <div class="row row-eq-height container m-auto ">
        <div class=" col-12 mb-4">
            <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid ;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold" style="">Asset Information</h6>
                </div>
                <div class="card-body">
                    <div class="row no-gutters">
                        <div class="col mr-2">
                            <div class="mb-1">
                                <p class="mb-4 ">Information regarding <strong
                                        class="font-weight-bold d-none d-sm-inline-block  btn-sm btn-primary shadow-sm padding-25-width">#{{ $asset->asset_tag }}</strong>
                                    , the asset that is currently being Viewed and any request information attached.</p>
                                <p> Asset Tag Number:#{{ $asset->asset_tag }}</p>
                                {{--<p>{{ $asset->asset_model->name }}</p>--}}
                                <p>Device Type:Ipad</p>
                                <p>Device Serial N0: {{ $asset->serial_no }}</p>
                                {{--<p>{{ $asset->status_id }}</p>--}}
                                <p>Device Status: Booked Out</p>
                                <p>Date of Purchase: {{ $asset->purchased_date }}</p>
                                <p>Supplier:{{ $asset->supplier_id}}</p>
                                <p>Order Num:{{ $asset->order_no }}</p>
                                <p>Purchase Cost:{{ $asset->purchased_cost }}</p>
                                <p>Warranty:{{ $asset->warranty}}</p>
                                <p>Who created this Asset:{{ $asset->user->name}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>



