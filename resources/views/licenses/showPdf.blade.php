@extends('layouts.pdf-reports')

@section('title', 'Licenses Report')

@section('page', $license->name)

@section('user', $user->name)

@section('content')

    <div style="padding: 5%">
        <table class="table" width="100%">
            <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th colspan="2">License Information</th>
            </tr>
            </thead>
            <tr>
                <td width="30%">Name:</td>
                <td width="70%">{{ $license->name }}</td>
            </tr>
            <tr>
                <td>Location</td>
                <td>{{ $license->location->name ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <td>Date Created</td>
                <td>{{ \Carbon\Carbon::parse($license->created_at)->format('d-m-Y')}}</td>
            </tr>
            <tr>
                <td>Purchase Cost</td>
                <td>£{{number_format( (float) $license->purchased_cost, 2, '.', ',' ) ?? 'N/A'}}</td>
            </tr>
            <tr>
                <td>Expiry</td>
                <td>{{ $license->expiry ?? 'No Expiry'}}</td>
            </tr>
            <tr>
                <td>Contact</td>
                <td>{{ $license->contact ?? 'No Contact Email'}}</td>
            </tr>
        </table>

        <hr>

        @if($license->comment()->exists())
            <p>Comments</p>
            <table class="table ">
                <thead>
                <tr style="background-color: #454777; padding: 10px; color: #fff;">
                    <th>Recent Activity</th>
                </tr>
                </thead>
                <tbody>

                @foreach($license->comment as $comment)
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
