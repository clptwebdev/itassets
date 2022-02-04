@extends('layouts.app')

@section('title', "View Property")

@section('css')

@endsection

@section('content')
    <x-wrappers.nav title="Show Misc" >
        {{-- <x-buttons.return :route="route('miscellaneous.index')" > Miscellaneous</x-buttons.return >
        <x-buttons.reports :route="route('miscellaneous.showPdf', $miscellaneou->id)" />
        <x-buttons.edit :route="route('miscellaneous.edit',$miscellaneou->id)" />
        <x-form.layout method="DELETE" class="d-sm-inline-block"
                       :id="'form'.$miscellaneou->id"
                       :action="route('miscellaneous.destroy', $miscellaneou->id)" >
            <x-buttons.delete formAttributes="data-id='{{$miscellaneou->id}}'" /> 
        </x-form.layout >--}}
    </x-wrappers.nav >

    <x-handlers.alerts />
    
    <div class="container card">
        <div class="card-body">
            <div class="card-title">
                {{$property->name}}
            </div>
            <p>Value (At Time of Purchase): {{$property->value}}</p>

            <?php 

            $eol = \Carbon\Carbon::parse($property->date)->addYears($property->depreciation);
            if($eol->isPast()){
                $dep = 0;
            }else{
                $age = \Carbon\Carbon::now()->floatDiffInYears($property->date);
                $percent = 100 / $property->depreciation;
                $percentage = floor($age)*$percent;
                $dep = $property->value * ((100 - $percentage) / 100);
            }
            
            
            ?>

            <p>Current Value: {{$dep}}</p>

            <?php
                //If Date is > 1 September the Year is this Year else Year = Last Year

                $now = \Carbon\Carbon::now();
                $startDate = \Carbon\Carbon::parse('09/01/'.$now->format('Y'));
                $endDate = \Carbon\Carbon::parse('09/01/'.\Carbon\Carbon::now()->addYear()->format('Y'));
                if(!$startDate->isPast()){
                    $startDate->subYear();
                    $endDate->subYear();
                }

                $age = $startDate->floatDiffInYears($property->date);
                $percent = 100 / $property->depreciation;
                $percentage = floor($age)*$percent;
                $bf = $property->value * ((100 - $percentage) / 100);

                $age = $endDate->floatDiffInYears($property->date);
                $percent = 100 / $property->depreciation;
                $percentage = floor($age)*$percent;
                $cf = $property->value * ((100 - $percentage) / 100);
            ?>

            <p>Depreciation B/Forward: {{$bf}}</p>
            <p>Depreciation C/Forward: {{$cf}}</p>


            <p>Depreciation Charge: {{ $property->value / $property->depreciation}}</p>
        </div>
        

    </div>


@endsection

@section('modals')
    

@endsection

@section('js')
    

@endsection
