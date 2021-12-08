@props(["accessory"])
<div class="col-12 col-lg-4 mb-4">
    <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid ;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold" style="">Purchase Information</h6>
        </div>
        <div class="card-body">
            <div class="row no-gutters">
                <div class="mb-1">
                    <p class="mb-4 ">Information regarding <strong
                            class="font-weight-bold d-inline-block btn-sm btn-grey shadow-sm p-1"><small>{{ $accessory->name }}</small></strong>
                        purchase order, you find information about the purchase and the supplier.</p>
                    
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <td class="col-4">Order N<sup>o</sup>:</td>
                            <td class="col-8"> {{$accessory->order_no ?? 'N/A'}}</td>
                        </tr>
                        <tr>
                            <td>Supplier:</td>
                            <td>
                                @if($accessory->supplier()->exists())
                                {{ $accessory->supplier->name}}<br>
                                {{ $accessory->supplier->address_1 }}<br>
                                {{ $accessory->supplier->address_2 }}<br>
                                {{ $accessory->supplier->city }}<br>
                                {{ $accessory->supplier->county }}<br>
                                {{ $accessory->supplier->postcode }}<br>
                                @else
                                    {{ 'No Supplier Information'}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Date of Purchase:</td>
                            <td>
                                <?php $purchase_date = \Carbon\Carbon::parse($accessory->purchased_date);?>
                                {{ $purchase_date->format('d/m/Y')}}
                            </td>
                        </tr>
                        <tr>
                            <td>Warranty</td>
                            <td>
                                <?php $warranty_end = \Carbon\Carbon::parse($accessory->purchased_date)->addMonths($accessory->warranty);?>
                                {{ $accessory->warranty }} Month(s)<br>
                                @if(\Carbon\Carbon::parse($warranty_end)->isPast())
                                        <span class="text-coral">{{ 'Expired' }}</span>
                                @else
                                <small class="text-success">{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Month(s) Remaining</small>
                                @endif
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td>Purchase Cost:</td>
                            <td>£{{$accessory->purchased_cost}}</td>
                        </tr>
                        @if($accessory->depreciation()->exists())
                            <tr>
                                <td>Current Value</td>
                                <td>
                                    @php
                                        $eol = Carbon\Carbon::parse($accessory->purchased_date)->addYears($accessory->depreciation->years);
                                        if($eol->isPast()){
                                            $dep = 0;
                                        }else{

                                            $age = Carbon\Carbon::now()->floatDiffInYears($accessory->purchased_date);
                                            $percent = 100 / $accessory->depreciation->years;
                                            $percentage = floor($age)*$percent;
                                            $dep = $accessory->purchased_cost * ((100 - $percentage) / 100);
                                        }
                                    @endphp
                                    £{{ number_format($dep, 2)}}*<br>
                                    <small>*Calculated using the Depreciation Model [{{$accessory->depreciation->name}}]</small>
                                </td>
                            </tr>
                        @endif
                    </table>
                    @if($accessory->supplier && $accessory->supplier->email != "")
                    <a href="mailto:{{$accessory->supplier->email}}"><button class="btn btn-sm btn-blue"><i class="far fa-envelope"></i> Email Supplier</button></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



