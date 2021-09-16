@props(["miscellaneou"])
<div class="col-12 col-lg-4 mb-4">
    <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid ;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold" style="">Purchase Information</h6>
        </div>
        <div class="card-body">
            <div class="row no-gutters">
                <div class="mb-1">
                    <p class="mb-4 ">Information regarding <strong
                            class="font-weight-bold d-inline-block btn-sm btn-grey shadow-sm p-1"><small>{{$miscellaneou->name }}</small></strong>
                        purchase order, you find information about the purchase and the supplier.</p>

                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <td class="col-4">Order N<sup>o</sup>:</td>
                            <td class="col-8"> {{$miscellaneou->order_no }}</td>
                        </tr>
                        <tr>
                            <td>Supplier:</td>
                            <td>
                                @if($miscellaneou->supplier()->exists())
                                {{$miscellaneou->supplier->name}}<br>
                                {{$miscellaneou->supplier->address_1 }}<br>
                                {{$miscellaneou->supplier->address_2 }}<br>
                                {{$miscellaneou->supplier->city }}<br>
                                {{$miscellaneou->supplier->county }}<br>
                                {{$miscellaneou->supplier->postcode }}<br>
                                @else
                                 <p>No Supplier Details</p>
                                 @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Date of Purchase:</td>
                            <td>
                                <?php $purchase_date = \Carbon\Carbon::parse($miscellaneou->purchased_date);?>
                                {{ $purchase_date->format('d/m/Y')}}
                            </td>
                        </tr>
                        <tr>
                            <td>Warranty</td>
                            <td>
                                <?php $warranty_end = \Carbon\Carbon::parse($miscellaneou->purchased_date)->addMonths($miscellaneou->warranty);?>
                                {{$miscellaneou->warranty }} Month(s) - <strong>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>Purchase Cost:</td>
                            <td>£{{$miscellaneou->purchased_cost }}</td>
                        </tr>
                    </table>
                    @if($miscellaneou->supplier &&$miscellaneou->supplier->email != "")
                        <a href="mailto:{{$miscellaneou->supplier->email}}"><button class="btn btn-sm btn-blue"><i class="far fa-envelope"></i> Email Supplier</button></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
