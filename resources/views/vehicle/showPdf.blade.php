@extends('layouts.pdf-reports')

@section('title', 'Vehicle Report')

@section('page', $vehicle->name)

@section('user', $user->name)

@section('content')

    <div style="padding: 5%">
        <table class="table" width="100%">
            <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th colspan="2">Vehicle Information</th>
            </tr>
            </thead>
            <tr>
                <td width="30%">Name:</td>
                <td width="70%">{{ $vehicle->name }}</td>
            </tr>
            <tr>
                <td>Registration</td>
                <td>{{ $vehicle->registration ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <td>Location</td>
                <td>{{ $vehicle->location->name ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <td>Date Created</td>
                <td>{{ \Carbon\Carbon::parse($vehicle->created_at)->format('d-m-Y')}}</td>
            </tr>
            <tr>
                <td>Date Purchased</td>
                <td>{{ \Carbon\Carbon::parse($vehicle->purchased_date)->format('d-m-Y')}}</td>
            </tr>
            <tr>
                <td>Purchase Cost</td>
                <td>£{{number_format( (float) $vehicle->purchased_cost, 2, '.', ',' ) ?? 'N/A'}}</td>
            </tr>
            <tr>
                <td>Depreciation</td>
                <td>{{ $vehicle->depreciation}} Years</td>
            </tr>
        </table>

        <hr>

        @if($vehicle->comment()->exists())
            <p>Comments</p>
            <table class="table ">
                <thead>
                <tr style="background-color: #454777; padding: 10px; color: #fff;">
                    <th>Recent Activity</th>
                </tr>
                </thead>
                <tbody>

                @foreach($vehicle->comment as $comment)
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
