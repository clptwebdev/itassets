@extends('layouts.email')

@section('welcome', 'Financial Report Completed')

@section('image')

    <img align="center" border="0" src="{{ asset('images\svg\report.svg')}}" alt="Apollo | Report Completed"
         title="Created user"
         style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 100%;max-width: 460px;"
         width="460"/>
@endsection

@section('title')
    Your report for {{$location->name}} is ready!
@endsection

@section('content')
    <p>Hi {{$user->name}}</p>
    <p>Your business report for {{$location->name}} is now ready. A copy of the report has been attached to the email. The Report is located at the link below and is available for 30 days
    only. After this it will be removed from the system.</p>
    <p><a href="{{asset($route)}}">{{asset($route)}}</a></p>
    <p>If you have any problems please contact the Development Team at <a href="mailto:helpdesk@clpt.co.uk">helpdesk@clpt.co.uk</a></p>
    <p>Kind Regards</p>
    <p>Apollo Asset Manager</p>
@endsection
