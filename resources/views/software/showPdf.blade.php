@extends('layouts.pdf-reports')

@section('title', 'Software Report')

@section('page', $software->name)

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
                <td width="70%">{{ $software->name }}</td>
            </tr>
            <tr>
                <td>Purchased Date</td>
                <td>{{\Carbon\Carbon::parse($software->purchased_date)->format("d/m/Y")}}</td>
            </tr>
            <tr>
                <td>Purchased Cost</td>
                <td> £{{number_format($software->purchased_cost, 2, '.', ',')}}</td>
            </tr>{{-- 
            <tr>
                <td>Order No</td>
                <td>{{$software->order_no ?? 'N/A'}}</td>
            </tr> --}}
            <tr>
                <td>Manufacturer</td>
                <td>{{$software->manufacturer->name ?? 'N/A'}}</td>
            </tr>
            <tr>
                <td>Supplier</td>
                <td>{{$software->supplier->name ?? 'N/A'}}</td>
            </tr>{{-- 
            <tr>
                <td>Warranty (Months)</td>
                <td>{{$software->warranty ?? 'N/A'}}</td>
            </tr> --}}
            <tr>
                <td>Depreciation</td>
                <td>{{ $software->depreciation}} Years</td>
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
                $bf = $software->depreciation_value_by_date($startDate);
                $cf = $software->depreciation_value_by_date($nextStartDate);
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
                <td>£{{number_format( (float) $software->purchased_cost - $bf, 2, '.', ',' )}}</td>
            </tr>
            <tr>
                <td>Depreciation Charge:</td>
                <td>£{{number_format( (float) $bf - $cf, 2, '.', ',' )}}</td>
            </tr>
            <tr>
                <td>Depreciation C/Fwd (31/08/{{$endDate->format('Y')}}):</td>
                <td>£{{number_format( (float) $software->purchased_cost - $cf, 2, '.', ',' )}}</td>
            </tr>
            <?php $prevYear = $startDate->subYear();?>
            @if($prevYear >= $software->purchased_date)
            <tr>
                <td>NBV {{$prevYear->format('Y')}}</td>
                <td>£{{number_format( (float) $software->depreciation_value_by_date($prevYear), 2, '.', ',' )}}</td>
            </tr>
            <?php $prevYear = $startDate->subYear();?>
            <tr>
                <td>NBV {{$prevYear->format('Y')}}</td>
                <td>£{{number_format( (float) $software->depreciation_value_by_date($prevYear), 2, '.', ',' )}}</td>
            </tr> 
            @endif               
        </table>

        <hr>

        @if($software->comment()->exists())
            <p>Comments</p>
            <table class="table ">
                <thead>
                <tr style="background-color: #454777; padding: 10px; color: #fff;">
                    <th>Recent Activity</th>
                </tr>
                </thead>
                <tbody>

                @foreach($software->comment as $comment)
                    <tr>
                        <td class="text-left"><strong>{{$comment->title}}</strong><br>{{ $comment->comment }}<br><span
                                class="text-info">{{ $comment->user->name }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at, 'Europe/London');}}</span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <table class="table ">
                <thead>
                <tr style="background-color: #454777; padding: 10px; color: #fff;">
                    <th>Recent Activity</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-center"><strong>No Comments Created</strong></td>
                </tr>

                </tbody>
            </table>
        @endif
    </div>

@endsection
