@extends('layouts.pdf-reports')

@section('title', 'Propery Report')

@section('page', $property->name)

@section('user', $user->name)

@section('content')

<div style="padding: 5%">
    <table class="table" width="100%">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th colspan="2">Property Information</th>
            </tr>
        </thead>
        <tr>
            <td width="30%">Name:</td>
            <td width="70%">{{ $property->name }}</td>
        </tr>
        <tr>
            <td>Type</td>
            <td>{{$property->getType()}}</td>
        </tr>
        <tr>
            <td>Location</td>
            <td>{{ $property->location->name ?? 'Unknown' }}</td>
        </tr>
        <tr>
            <td>Date Acquired</td>
            <td>{{ \Carbon\Carbon::parse($property->purchased_date)->format('d-m-Y')}}</td>
        </tr>
        <tr>
            <td>Cost</td>
            <td>£{{number_format( (float) $property->purchased_cost, 2, '.', ',' ) ?? 'N/A'}}</td>
        </tr>
        <tr>
            <td>Depreciation</td>
            <td>{{ $property->depreciation}} Years</td>
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
            $bf = $property->depreciation_value_by_date($startDate);
            $cf = $property->depreciation_value_by_date($nextStartDate);
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
            <td>£{{number_format( (float) $property->purchased_cost - $bf, 2, '.', ',' )}}</td>
        </tr>
        <tr>
            <td>Depreciation Charge:</td>
            <td>£{{number_format( (float) $bf - $cf, 2, '.', ',' )}}</td>
        </tr>
        <tr>
            <td>Depreciation C/Fwd (31/08/{{$endDate->format('Y')}}):</td>
            <td>£{{number_format( (float) $property->purchased_cost - $cf, 2, '.', ',' )}}</td>
        </tr>
        <?php $prevYear = $startDate->subYear();?>
        @if($prevYear >= $property->purchased_date)
        <tr>
            <td>NBV {{$prevYear->format('Y')}}</td>
            <td>£{{number_format( (float) $property->depreciation_value_by_date($prevYear), 2, '.', ',' )}}</td>
        </tr>
        <?php $prevYear = $startDate->subYear();?>
        <tr>
            <td>NBV {{$prevYear->format('Y')}}</td>
            <td>£{{number_format( (float) $property->depreciation_value_by_date($prevYear), 2, '.', ',' )}}</td>
        </tr> 
        @endif               
    </table>

    <hr>

    @if($property->comment()->exists())
        <p>Comments</p>
        <table class="table ">
            <thead>
                <tr style="background-color: #454777; padding: 10px; color: #fff;"><th>Recent Actvity</th></tr>    
            </thead>                      
            <tbody>
                
                @foreach($property->comment as $comment)
                <tr>
                    <td class="text-left"><strong>{{$comment->title}}</strong><br>{{ $comment->comment }}<br><span class="text-info">{{ $comment->user->name }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at, 'Europe/London');}}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
        
@endsection