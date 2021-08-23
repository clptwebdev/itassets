@props(["component"])
<div class="col-12 col-lg-4 mb-4">
    <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid ;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold" style="">Purchase Information</h6>
        </div>
        <div class="card-body">
            <div class="row no-gutters">
                <div class="mb-1">
                    <p class="mb-4 ">Information regarding <strong
                            class="font-weight-bold d-inline-block btn-sm btn-secondary shadow-sm p-1"><small>{{ $component->name }}</small></strong>
                        purchase order, you find information about the purchase and the supplier.</p>
                    
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <td class="col-4">Order N<sup>o</sup>:</td>
                            <td class="col-8"> {{$component->order_no }}</td>
                        </tr>
                        <tr>
                            <td>Supplier:</td>
                            <td>
                                {{ $component->supplier->name}}<br>
                                {{ $component->supplier->address_1 }}<br>
                                {{ $component->supplier->address_2 }}<br>
                                {{ $component->supplier->city }}<br>
                                {{ $component->supplier->county }}<br>
                                {{ $component->supplier->postcode }}<br>
                            </td>
                        </tr>
                        <tr>
                            <td>Date of Purchase:</td>
                            <td>
                                <?php $purchase_date = \Carbon\Carbon::parse($component->purchased_date);?>
                                {{ $purchase_date->format('d/m/Y')}}
                            </td>
                        </tr>
                        <tr>
                            <td>Warranty</td>
                            <td>
                                <?php $warranty_end = \Carbon\Carbon::parse($component->purchased_date)->addMonths($component->warranty);?>
                                {{ $component->warranty }} Month(s) - <strong>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>Purchase Cost:</td>
                            <td>£{{ $component->purchased_cost }}</td>
                        </tr>
                    </table>
                    @if($component->supplier && $component->supplier->email != "")
                    <a href="mailto:{{$component->supplier->email}}"><button class="btn btn-sm btn-primary"><i class="far fa-envelope"></i> Email Supplier</button></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



