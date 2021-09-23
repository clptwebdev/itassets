@props(["asset"])
<div class="col-12 col-lg-4 mb-4">
    <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid ;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold" style="">Purchase Information</h6>
        </div>
        <div class="card-body">
            <div class="row no-gutters">
                <div class="mb-1">
                    <p class="mb-4 ">Information regarding <strong
                            class="font-weight-bold d-inline-block btn-sm btn-grey shadow-sm p-1"><small>{{$asset->name }}</small></strong>
                        purchase order, you find information about the purchase and the supplier.</p>
                    
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <td class="col-4">Order N<sup>o</sup>:</td>
                            <td class="col-8"> {{$asset->order_no ?? 'No Order Information Available'}}</td>
                        </tr>
                        <tr>
                            <td>Supplier:</td>
                            <td>
                                @if($asset->supplier()->exists())
                                {{ $asset->supplier->name}}<br>
                                {{ $asset->supplier->address_1 }}<br>
                                {{ $asset->supplier->address_2 }}<br>
                                {{ $asset->supplier->city }}<br>
                                {{ $asset->supplier->county }}<br>
                                {{ $asset->supplier->postcode }}<br>
                                @else
                                    <p>No Supplier Information</p>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Date of Purchase:</td>
                            <td>
                                <?php $purchase_date = \Carbon\Carbon::parse($asset->purchased_date);?>
                                {{ $purchase_date->format('d/m/Y')}}
                            </td>
                        </tr>
                        <tr>
                            <td>Warranty</td>
                            <td>
                                @php $warranty_end = \Carbon\Carbon::parse($asset->purchased_date)->addMonths($asset->warranty);@endphp
                                @if(\Carbon\Carbon::parse($warranty_end)->isPast())
                                        {{ $asset->warranty }} Month(s) - <strong class="text-coral">{{ 'Expired' }}</strong>
                                    @else
                                    {{ $asset->warranty }} Month(s) - <strong>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }}
                                        Remaining</strong>
                                    @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Purchase Cost:</td>
                            @if($asset->model()->exist() && $asset->model()->depreciation()->exists())
                                <?php 
                                $age = Carbon\Carbon::now()->floatDiffInYears($asset->purchased_date); 
                                $percent = 100 / $asset->model->depreciation->years;
                                $percentage = floor($age)*$percent;
                                $dep = $asset->purchased_cost * ((100 - $percentage) / 100);
                                ?>
                            @else
                                @php($dep = 0)
                            @endif
                            <td>£{{ $asset->purchased_cost }} - (Current Value*: £{{ number_format($dep, 2)}})<br>
                            <small>*Calculated using Depreciation Model:</small><br><strong
                                class="font-weight-bold d-inline-block btn-sm btn-secondary shadow-sm p-1"><small>Laptop and Tablet</small></strong></p></td>
                        </tr>
                    </table>
                    @if($asset->supplier && $asset->supplier->email != "")
                    <a href="mailto:{{$asset->supplier->email}}"><button class="btn btn-sm btn-blue"><i class="far fa-envelope"></i> Email Supplier</button></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



