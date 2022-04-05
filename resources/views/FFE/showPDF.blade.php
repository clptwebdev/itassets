@extends('layouts.pdf-reports')

@section('title', 'FFE Report')

@section('page', $ffe->name)

@section('user', $user->name)

@section('content')

<div style="padding: 5%">
    <table class="table" width="100%">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th colspan="2">Furniture, Fixtures and Equipment Information</th>
            </tr>
        </thead>
        <tr>
            <td width="30%">Name:</td>
            <td width="70%">{{ $ffe->name }}</td>
        </tr>
        <tr>
            <td>Serial No:</td>
            <td>{{ $ffe->serial_no }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>{{$ffe->status->name}}</td>
        </tr>
        <tr>
            <td>Purchased Date</td>
            <td>{{\Carbon\Carbon::parse($ffe->purchased_date)->format("d/m/Y")}}</td>
        </tr>
        <tr>
            <td>Purchased Cost</td>
            <td> £{{number_format($ffe->purchased_cost, 2, '.', ',')}}</td>
        </tr>
        <tr>
            <td>Donated</td>
            <td>@if($ffe->donated == 1) Yes @else No @endif</td>
        </tr>
        <tr>
            <td>Order No</td>
            <td>{{$ffe->order_no ?? 'N/A'}}</td>
        </tr>
        <tr>
            <td>Manufacturer</td>
            <td>{{$ffe->manufacturer->name ?? 'N/A'}}</td>
        </tr>
        <tr>
            <td>Supplier</td>
            <td>{{$ffe->supplier->name ?? 'N/A'}}</td>
        </tr>
        <tr>
            <td>Warranty (Months)</td>
            <td>{{$ffe->warranty ?? 'N/A'}}</td>
        </tr>
        <tr>
            <td>Depreciation</td>
            <td>{{ $ffe->depreciation_id}} Years</td>
        </tr>
        <tr style="background-color: #454777; padding: 10px; color: #fff;">
            <th colspan="2">Finance</th>
        </tr>
        
        <?php
            //If Date is > 1 September the Year is this Year else Year = Last Year

            $now = \Carbon\Carbon::now();
            $startDate = \Carbon\Carbon::parse('09/01/'.$now->format('Y'));
            $nextYear = \Carbon\Carbon::now()->addYear()->format('Y');
            $nextStartDate = \Carbon\Carbon::parse('09/01/'.\Carbon\Carbon::now()->addYear()->format('Y'));
            $endDate = \Carbon\Carbon::parse('08/31/'.$nextYear);
            if(!$startDate->isPast()){
                $startDate->subYear();
                $endDate->subYear();
                $nextStartDate->subYear();
            }
            $bf = $ffe->depreciation_value_by_date($startDate);
            $cf = $ffe->depreciation_value_by_date($nextStartDate);
        ?>
        <tr>
            <td>Cost B/Fwd (01/09/{{$startDate->format('Y')}}):</td>
            <td>£{{ number_format( (float) $bf, 2, '.', ',' )}}</td>
        </tr>
        <tr>
            <td>Cost C/Fwd (31/08/{{$endDate->format('Y')}}):</td>
            <td>£{{ number_format( (float) $cf, 2, '.', ',' )}}</td>
        </tr>
        <tr>
            <td>Depreciation B/Fwd (01/09/{{$startDate->format('Y')}}):</td>
            <td>£{{number_format( (float) $ffe->purchased_cost - $bf, 2, '.', ',' )}}</td>
        </tr>
        <tr>
            <td>Depreciation Charge:</td>
            <td>£{{number_format( (float) $bf - $cf, 2, '.', ',' )}}</td>
        </tr>
        <tr>
            <td>Depreciation C/Fwd (31/08/{{$endDate->format('Y')}}):</td>
            <td>£{{number_format( (float) $ffe->purchased_cost - $cf, 2, '.', ',' )}}</td>
        </tr>
        <?php $prevYear = $startDate->subYear();?>
        @if($prevYear >= $ffe->purchased_date)
        <tr>
            <td>NBV {{$prevYear->format('Y')}}</td>
            <td>£{{number_format( (float) $ffe->depreciation_value_by_date($prevYear), 2, '.', ',' )}}</td>
        </tr>
        <?php $prevYear = $startDate->subYear();?>
        <tr>
            <td>NBV {{$prevYear->format('Y')}}</td>
            <td>£{{number_format( (float) $ffe->depreciation_value_by_date($prevYear), 2, '.', ',' )}}</td>
        </tr> 
        @endif               
    </table>

    <hr>

    @if($ffe->comment()->exists())
        <p>Comments</p>
        <table class="table ">
            <thead>
                <tr style="background-color: #454777; padding: 10px; color: #fff;"><th>Recent Actvity</th></tr>    
            </thead>                      
            <tbody>
                
                @foreach($ffe->comment as $comment)
                <tr>
                    <td class="text-left"><strong>{{$comment->title}}</strong><br>{{ $comment->comment }}<br><span class="text-info">{{ $comment->user->name }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at, 'Europe/London');}}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
        
@endsection