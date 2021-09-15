@extends('layouts.app')

@section('title', 'View Components')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>

@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Help</h1>
        <div>
            <a href="/" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50 fa-text-width"></i> Back to Dashboard</a>
        </div>
    </div>

    @if(session('danger_message'))
        <div class="alert alert-danger"> {!! session('danger_message')!!} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {!! session('success_message')!!} </div>
    @endif

    <section>
        <div id="accordion">
            <p class="mb-4">Below are different Buttons, one for each Item in the management system. Each tile
                has different information that can Help you understand how to use this system. If you require Anymore
                assistance please email <strong>apollo@clpt.co.uk</strong></p>
            {{--      heading one      --}}
            <div id="headingOne" class="slide">
                <button class="btn btn-link collapsed slide" data-toggle="collapse" data-target="#collapseOne"
                        aria-expanded="false" aria-controls="collapseOne">Exports
                </button>
            </div>
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    <h2 class="text-info">How do I Export the data i need?</h2>

                    <p class="text-gray-700">Most Interfaces have in the top right hand corner have a Button which once
                        clicked will return all results of your current page to a <strong>'.csv'</strong> file.</p>
                    <div class="d-sm-flex align-items-center ">
                        <button class="btn btn-sm btn-warning shadow-sm m-2">Export</button>
                        <p class="text-gray-600 mt-3">This is an example export button</p>
                    </div>
                    <h4 class="text-info">Items Below Which can be exported:</h4>
                    <ul>
                        <li>Assets</li>
                        <li>Miscellaneous</li>
                        <li>Components</li>
                        <li>Users</li>
                        <li>Locations</li>
                        <li>Manufacturers</li>
                        <li>Suppliers</li>
                    </ul>
                    <p class="text-info">All Imports Have the option to export the errors Which you encounter</p>
                </div>
            </div>
            {{--      heading one end      --}}
            {{--     heading two       --}}
            <div id="headingTwoo" class="slide">
                <button class="btn btn-link collapsed slide" data-toggle="collapse" data-target="#collapseTwoo"
                        aria-expanded="false" aria-controls="collapseTwoo">Passwords
                </button>
            </div>
            <div id="collapseTwoo" class="collapse" aria-labelledby="headingTwoo" data-parent="#accordion">
                <div class="card-body">
                    <h2 class="text-info">I've Lost my Password What can i do?</h2>
                    <p class="text-gray-700">If You have Forgot your password you can click <a href="{{route("forgot.my.password")}}" class="text-capitalize">here</a> to reset your password by your email! else please contact your Admin and they will reset this for you!</p>
                    <h2 class="text-info mt-2">I know my password How can i reset this?</h2>
                    <p class="text-gray-700">If you know your password you can navigate through the profile link in the top right or Click  <a href="{{route("user.details")}}" class="text-capitalize">here</a>.</p>
                    <h4 class="text-info">Steps to reset:</h4>
                    <ol>
                        <li>Find Out your password from your original sign up email.</li>
                        <li>Navigate to the link above and click reset password</li>
                        <li>Input your current password to the top box</li>
                        <li>Think of a new password and match this in both Boxes <strong class="text-danger">(Capitals are included!)</strong></li>
                    </ol>
                </div>
            </div>
            {{--     heading two end       --}}
            {{--     heading three       --}}
            <div id="headingThree" class="slide">
                <button class="btn btn-link collapsed slide" data-toggle="collapse" data-target="#collapseThree"
                        aria-expanded="false" aria-controls="collapseThree">Assets
                </button>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                <div class="card-body">
                    <h2 class="text-info">Where can i find All of my Devices?</h2>
                    <p class="text-gray-700">Assets can be located within the left hand side menu Labeled 'Assets' Can't find it? Click<a href="{{route("assets.index")}}" class="text-capitalize"> here</a></p>
                    <h4 class="text-info">I need to find all the devices at my current School How can i do this?</h4>
                    <p class="text-gray-700">Our Asset Area has a Search Helper.This allows for easy searching. On the top right of the page a filter button can be found where you can select all sorts of Columns to see only The Selected data you need.</p>
                    <p class="text-info">You can use the filter function included with the export function to only get specified data to your <strong>Csv's</strong> and <strong>PDf</strong> documents!</p>
                    <h4 class="text-info">How do i add new Devices/Assets?</h4>
                    <p class="text-gray-700">All Computers should be added to assets as all other Products have there related columns <strong class="text-info">Not sure where to Put an un-related Item Use Miscellaneous!</strong> At the Top of the screen you will find a button which takes you to adding a new asset.<a class="text-warning" href="{{route("assets.create")}}"> Stuck? Click here to add a new Asset</a></p>
                    <h4 class="text-info">Guide to adding new Assets:</h4>
                    <ol>
                        <li>Firstly, Does Your asset have a name? if so fill this in in the first box.</li>
                        <li>Secondly, Fill out your order number from the Device purchased.</li>
                        <li>Thirdly,When Adding the Asset to the system you will need a <strong>Unique</strong> asset tag number which is <strong>not</strong> already in the system.</li>
                        <li>Fourth,Please select when the device where purchased.</li>
                        <li>Fifth,You will need to a serial number corresponding to the device your adding.</li>
                        <li>Sixth,a purchase cost will be needed <strong class="text-info">(No need to enter a '£' sign this will be done automatically)</strong></li>
                        <li>Seventh,Which school is this located to ? Assign this here on the dropdown menu.</li>
                        <li>Eighth,Who is the supplier of this asset? fill this in here in the select menu.</li>
                        <li>Ninth,Does this need a check up in a few month's? Place a date in the box.<strong class="text-info">(You will be reminded when this device need's Auditing!)</strong></li>
                        <li>Tenth,select a date for how long of a warranty you have left on this device!</li>
                        <li>Eleventh,Is This a specific type of Device E.g<strong class="text-info">(HP ProDesk ,Optiplex 9020 ,EliteDesktop 800 G2)</strong></li>
                        <li>Finally,Click <strong>save</strong> in the top Right corner and see if this all saves correctly. If you have any errors this will be displayed in a red box at the top fo the screen like below!</li>
                    </ol>
                    <div class="d-sm-flex align-items-center ">
                        <div class="text-gray-700">Example Errors Box: </div>
                            <div class="alert alert-danger m-5 d-inline">red box </div>
                    </div>
                    <h4 class="text-info mt-3">How to import Assets:</h4>
                    <p class="text-info">A Few quick Notes before we begin ,</p>
                    <ul class="">
                        <li class="text-danger">There is a template provided which needs to be used No other templates can be used!</li>
                        <li class="text-danger">Always remember to upload a file before clicking import!</li>
                        <li class="text-danger">Once you have added all your data to the excel spreadsheet Try uploading this! This input only accepts<strong>.Csv</strong> files. </li>
                        <li class="text-danger">Errors can always occur when uploading files This is why any rows which has no issues will be pushed through and added for you. Your next option after this is to 1.export all the errors back to <strong>.Csv </strong>and then correct the current issues and Re-Upload your new file. </br> 2.Once your <strong>.Csv</strong> file has been uploaded there is a friendly user interface which allows you to manually edit all the rows which are incorrect with tool tips to help you across the way!</li>
                    </ul>
                    <ol>
                        <li>Firstly, Download the specified template, this can be found once you click import the button then the <strong class="text-info">'Download import template'</strong></li>
                        <li>Secondly, Fill in all the data in the correct columns for your Csv file and <strong class="text-info">save</strong> this file!</li>
                        <li>Thirdly, Click the import button like before but this time click<strong class="text-info"> browse </strong> on the blue box and select your <strong class="text-info">new</strong> Csv file.</li>
                        <li>Fourth, Click <strong class="text-info">import</strong> in the bottom right corner and let the system process your file.</li>
                        <li>Fifth,If you don't have any errors this will return with a <strong class="text-success">success message</strong> which tell you all of your data fields were added. Else, Your will be moved to a new page with the <strong class="text-danger">incorrect </strong> fields which have errors to change your errors. Click <strong class="text-info">save</strong> or <strong class="text-info">export</strong> all of your errors to CSv and Re-Uploaded this after you have fixed your changes.</li>
                        <li>Sixth,If this has all complete correctly you will be returned once again with a <strong class="text-success">success message</strong> that your Items have been added!</li>
                    </ol>

                </div>
            </div>
            {{--     heading three end       --}}
            {{--     headingFour       --}}
            <div id="headingFour" class="slide">
                <button class="btn btn-link collapsed slide" data-toggle="collapse" data-target="#collapseFour"
                        aria-expanded="false" aria-controls="collapseFour">Status
                </button>
            </div>
            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
            {{--     headingFour end       --}}
            {{--     headingFive       --}}
            <div id="headingFive" class="slide">
                <button class="btn btn-link collapsed slide" data-toggle="collapse" data-target="#collapseFive"
                        aria-expanded="false" aria-controls="collapseFive">Location
                </button>
            </div>
            <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
            {{--     headingFive end       --}}
            {{--     headingSix       --}}
            <div id="headingSix" class="slide">
                <button class="btn btn-link collapsed slide" data-toggle="collapse" data-target="#collapseSix"
                        aria-expanded="false" aria-controls="collapseSix">Recycle Bin
                </button>
            </div>
            <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
            {{--     headingSix end       --}}
            {{--     headingSeven       --}}
            <div id="headingSeven" class="slide">
                <button class="btn btn-link collapsed slide" data-toggle="collapse" data-target="#collapseSeven"
                        aria-expanded="false" aria-controls="collapseSeven">Import
                </button>
            </div>
            <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
            {{--     headingSeven end       --}}
            {{--     headingEight       --}}
            <div id="headingEight" class="slide">
                <button class="btn btn-link collapsed slide" data-toggle="collapse" data-target="#collapseEight"
                        aria-expanded="false" aria-controls="collapseEight">Accessory
                </button>
            </div>
            <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
            {{--     headingEight end       --}}
            {{--     headingNine       --}}
            <div id="headingNine" class="slide">
                <button class="btn btn-link collapsed slide" data-toggle="collapse" data-target="#collapseNine"
                        aria-expanded="false" aria-controls="collapseNine">Component
                </button>
            </div>
            <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
            {{--     headingNine end       --}}
            {{--     headingTen       --}}
            <div id="headingTen" class="slide">
                <button class="btn btn-link collapsed slide" data-toggle="collapse" data-target="#collapseTen"
                        aria-expanded="false" aria-controls="collapseTen">Miscellaneous
                </button>
            </div>
            <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
            {{--     headingTen end       --}}
            {{--     headingEleven       --}}
            <div id="headingEleven" class="slide">
                <button class="btn btn-link collapsed slide" data-toggle="collapse" data-target="#collapseEleven"
                        aria-expanded="false" aria-controls="collapseEleven">Users
                </button>
            </div>
            <div id="collapseEleven" class="collapse" aria-labelledby="headingEleven" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
            {{--     headingEleven end       --}}
            {{--     headingTwelve       --}}
            <div id="headingTwelve" class="slide">
                <button class="btn btn-link collapsed slide" data-toggle="collapse" data-target="#collapseTwelve"
                        aria-expanded="false" aria-controls="collapseTwelve">Permissions
                </button>
            </div>
            <div id="collapseTwelve" class="collapse" aria-labelledby="headingTwelve" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
            {{--     headingTwelve end       --}}
            {{--     headingThirteen       --}}
            <div id="headingThirteen" class="slide">
                <button class="btn btn-link collapsed slide" data-toggle="collapse" data-target="#collapseThirteen"
                        aria-expanded="false" aria-controls="collapseThirteen">Manufacturers
                </button>
            </div>
            <div id="collapseThirteen" class="collapse" aria-labelledby="headingThirteen" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
            {{--     headingThirteen end       --}}
            {{--     headingFourteen       --}}
            <div id="headingFourteen" class="slide">
                <button class="btn btn-link collapsed slide" data-toggle="collapse" data-target="#collapseFourteen"
                        aria-expanded="false" aria-controls="collapseFourteen">Suppliers
                </button>
            </div>
            <div id="collapseFourteen" class="collapse" aria-labelledby="headingFourteen" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
            {{--     headingFourteen end       --}}
            {{--     headingFithteen       --}}
            <div id="headingFithteen" class="slide">
                <button class="btn btn-link collapsed slide" data-toggle="collapse" data-target="#collapseFithteen"
                        aria-expanded="false" aria-controls="collapseFithteen">Asset Models
                </button>
            </div>
            <div id="collapseFithteen" class="collapse" aria-labelledby="headingFithteen" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
            {{--     headingFithteen end       --}}
            {{--     headingSixteen       --}}
            <div id="headingSixteen" class="slide">
                <button class="btn btn-link collapsed slide" data-toggle="collapse" data-target="#collapseSixteen"
                        aria-expanded="false" aria-controls="collapseSixteen">Depreciation
                </button>
            </div>
            <div id="collapseSixteen" class="collapse" aria-labelledby="headingSixteen" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
            {{--     headingSixteen end       --}}
            {{--     headingSeventeen       --}}
            <div id="headingSeventeen" class="slide">
                <button class="btn btn-link collapsed slide" data-toggle="collapse" data-target="#collapseSeventeen"
                        aria-expanded="false" aria-controls="collapseSeventeen">Categories
                </button>
            </div>
            <div id="collapseSeventeen" class="collapse" aria-labelledby="headingSeventeen" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
            {{--     headingSeventeen end       --}}
            {{--     headingEighteen       --}}
            <div id="headingEighteen" class="slide">
                <button class="btn btn-link collapsed slide" data-toggle="collapse" data-target="#collapseEighteen"
                        aria-expanded="false" aria-controls="collapseEighteen">Fieldsets
                </button>
            </div>
            <div id="collapseEighteen" class="collapse" aria-labelledby="headingEighteen" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
            {{--     headingEighteen end       --}}
            {{--     headingNineteen       --}}
            <div id="headingNineteen" class="slide">
                <button class="btn btn-link collapsed slide" data-toggle="collapse" data-target="#collapseNineteen"
                        aria-expanded="false" aria-controls="collapseNineteen">Status Fields
                </button>
            </div>
            <div id="collapseNineteen" class="collapse" aria-labelledby="headingNineteen" data-parent="#accordion">
                <div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                    labore sustainable VHS.
                </div>
            </div>
            {{--     headingNineteen end       --}}
        </div>
    </section>




@endsection

@section('modals')

@endsection

@section('js')
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
        $(function () {
            $("#accordion").accordion();
        });
    </script>

@endsection
