@extends('layouts.app')

@section('title', 'Dashboard')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')
    <!-- session messages -->
    <x-handlers.alerts/>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>
    <div class="p-2 border-danger">
       
    </div>

    @if(auth()->user()->role_id == 0)
        <!-- Asset stats -->
        <x-admin.asset-info />
        {{-- <x-categories_status_info :statuses="$statuses" :category="$category"/> --}}
    @else
        <x-admin.request-access/>
    @endif

@endsection

@section('js')

    <script type="text/javascript">
    
        const totalCount = document.querySelector('#total_count');
        const totalCost = document.querySelector('#total_cost');
        const totalDep = document.querySelector('#total_dep');
        const assetsCount = document.querySelector('#assets_count');
        const assetsCost = document.querySelector('#assets_cost');
        const assetsDep = document.querySelector('#assets_dep');
        const accessoryCount = document.querySelector('#accessory_count');
        const accessoryCost = document.querySelector('#accessory_cost');
        const accessoryDep = document.querySelector('#accessory_dep');
        const componentsCount = document.querySelector('#components_count');
        const componentsCost = document.querySelector('#components_cost');
        const consumablesCount = document.querySelector('#consumables_count');
        const consumablesCost = document.querySelector('#consumables_cost');
        const miscCount = document.querySelector('#miscellanea_count');
        const miscCost = document.querySelector('#miscellanea_cost');

        const loader = document.querySelectorAll('.stats_loading');

        // How long you want the animation to take, in ms
        const animationDuration = 2000;
        // Calculate how long each ‘frame’ should last if we want to update the animation 60 times per second
        const frameDuration = 1000 / 60;
        // Use that to calculate how many frames we need to complete the animation
        const totalFrames = Math.round( animationDuration / frameDuration );
        // An ease-out function that slows the count as it progresses
        const easeOutQuad = t => t * ( 2 - t );

        // The animation function, which takes an Element
        const animateCountUp = el => {
            let frame = 0;
            const countTo = parseInt( el.innerHTML, 10 );
            // Start the animation running 60 times per second
            const counter = setInterval( () => {
                frame++;
                // Calculate our progress as a value between 0 and 1
                // Pass that value to our easing function to get our
                // progress on a curve
                const progress = easeOutQuad( frame / totalFrames );
                // Use the progress value to calculate the current count
                const currentCount = Math.round( countTo * progress );

                // If the current count has changed, update the element
                if ( parseInt( el.innerHTML, 10 ) !== currentCount ) {
                    el.innerHTML = currentCount;
                }

                // If we’ve reached our last frame, stop the animation
                if ( frame === totalFrames ) {
                    clearInterval( counter );
                }
            }, frameDuration );
        };

        // Run the animation on all elements with a class of ‘countup’
        const runAnimations = () => {
            const countupEls = document.querySelectorAll( '.countup' );
            countupEls.forEach( animateCountUp );
        };

        const xhttp = new XMLHttpRequest();

        xhttp.onload = function(){
            loader.forEach(function(el) {
                el.classList.remove('d-flex');
                el.classList.add('d-none');
            });
            //Fetch the return JSON Object
            const obj = JSON.parse(xhttp.responseText);
            //Asset
            assetsCount.innerHTML = obj.asset.count;
            assetsCost.innerHTML = obj.asset.cost;
            assetsDep.innerHTML = obj.asset.dep;
            //Accessory
            accessoryCount.innerHTML = obj.accessories.count;
            accessoryCost.innerHTML = obj.accessories.cost;
            accessoryDep.innerHTML = obj.accessories.dep;

            componentsCount.innerHTML = obj.components.count;
            componentsCost.innerHTML = obj.components.cost;

            consumablesCount.innerHTML = obj.consumables.count;
            consumablesCost.innerHTML = obj.consumables.cost;

            miscCount.innerHTML = obj.miscellaneous.count;
            miscCost.innerHTML = obj.miscellaneous.cost;

            totalCount.innerHTML = obj.asset.count + obj.accessories.count;
            totalCost.innerHTML = obj.asset.cost + obj.accessories.cost;
            totalDep.innerHTML = obj.asset.dep + obj.accessories.dep;
            runAnimations();
        }

        xhttp.open("GET", "/statistics");
        xhttp.send(); 

        function addListenener(xhr){
            xhr.addListenener('loadstart', function(){
                assetsCount.innerHTML = `<div class="spinner-border text-secondary" role="status">
                                        <span class="sr-only">Loading...</span>
                                        </div>`;
            });
        }

    </script>

@endsection
