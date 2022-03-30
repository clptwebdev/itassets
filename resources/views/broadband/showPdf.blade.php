@extends('layouts.pdf-reports')

@section('title', 'Broadband Report')

@section('page', $broadband->name)

@section('user', $user->name)

@section('content')

    <div style="padding: 5%">
        <table class="table" width="100%">
            <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th colspan="2">Broadband Information</th>
            </tr>
            </thead>
            <tr>
                <td width="30%">Name:</td>
                <td width="70%">{{ $broadband->name }}</td>
            </tr>
            <tr>
                <td>Location</td>
                <td>{{ $auc->location->name ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <td>Date Created</td>
                <td>{{ \Carbon\Carbon::parse($broadband->created_at)->format('d-m-Y')}}</td>
            </tr>
            <tr>
                <td>Date Purchased</td>
                <td>{{ \Carbon\Carbon::parse($broadband->purchased_date)->format('d-m-Y')}}</td>
            </tr>
            <tr>
                <td>Purchase Cost</td>
                <td>£{{number_format( (float) $broadband->purchased_cost, 2, '.', ',' ) ?? 'N/A'}}</td>
            </tr>
            <tr>
                <td>Renewal Date</td>
                <td>{{ $broadband->renewal_date}}</td>
            </tr>
            <tr>
                <td>Package</td>
                <td>{{ $broadband->package}}</td>
            </tr>
        </table>

        <hr>

        @if($broadband->comment()->exists())
            <p>Comments</p>
            <table class="table ">
                <thead>
                <tr style="background-color: #454777; padding: 10px; color: #fff;">
                    <th>Recent Activity</th>
                </tr>
                </thead>
                <tbody>

                @foreach($broadband->comment as $comment)
                    <tr>
                        <td class="text-left"><strong>{{$comment->title}}</strong><br>{{ $comment->comment }}<br><span
                                class="text-info">{{ $comment->user->name }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at, 'Europe/London')}}</span>
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
