<?php

namespace App\Http\Controllers;

use App\Exports\LocationsExport;
use App\Models\Location;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Storage;
use App\Jobs\LocationsPdf;
use App\Jobs\LocationPdf;
use App\Models\Report;


class LocationController extends Controller
{
    
    public function index()
    {
        if (auth()->user()->cant('viewAny', Location::class)) {
            return redirect(route('errors.forbidden', ['area', 'Locations', 'view']));
        }

        if(auth()->user()->role_id==1){
            $locations =Location::all();
        }else{
            $locations = auth()->user()->locations;
        }
        return view('locations.view', ['locations'=>$locations]);
    }

    public function create()
    {
        if (auth()->user()->cant('create', Location::class)) {
            return redirect(route('errors.forbidden', ['area', 'Locations', 'create']));
        }

        return view('locations.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->cant('create', Location::class)) {
            return redirect(route('errors.forbidden', ['area', 'Locations', 'create']));
        }

        $validated=$request->validate([
            'name'=>'required|max:255',
            'address_1'=>'required',
            'city'=>'required',
            'county'=>'required',
            'postcode'=>'required',
            'email'=>'required|unique:locations|email',
            'telephone'=>'required|max:14',
        ]);

        Location::create($request->only('name', 'address_1', 'address_2', 'city', 'county', 'postcode', 'email', 'telephone', 'photo_id', 'icon'))->save();
        session()->flash('success_message', $request->name.' has been created successfully');
        return redirect(route('location.index'));
    }
  
    public function show(Location $location)
    {
        if (auth()->user()->cant('view', $location)) {
            return redirect(route('errors.forbidden', ['locations', $location->id, 'view']));
        }

        return view('locations.show', compact('location'));
    }
 
    public function edit(Location $location)
    {
        if (auth()->user()->cant('update', $location)) {
            return redirect(route('errors.forbidden', ['locations', $location->id, 'edit']));
        }

        if(auth()->user()->role_id==1){
            $locations =Location::all();
        }else{
            $locations = auth()->user()->locations;
        }

        return view('locations.edit', compact('location','locations'));
    }

    public function update(Request $request, Location $location)
    {
        if (auth()->user()->cant('update', $location)) {
            return redirect(route('errors.forbidden', ['locations', $location->id, 'edit']));
        }

        $validated=$request->validate([
            'name'=>'required|max:255',
            'address_1'=>'required',
            'city'=>'required',
            'county'=>'required',
            'postcode'=>'required',
            'email'=>['required', \Illuminate\Validation\Rule::unique('locations')->ignore($location->id), 'email'],
            'telephone'=>'required|max:14',
        ]);

        $location->fill($request->only('name', 'address_1', 'address_2', 'city', 'county', 'postcode', 'email', 'telephone', 'photo_id', 'icon'))->save();
        session()->flash('success_message', $location->name . ' has been updated successfully');
        return redirect(route('location.index'));
    }
   
    public function destroy(Location $location)
    {
        if (auth()->user()->cant('delete', $location)) {
            return redirect(route('errors.forbidden', ['locations', $location->id, 'delete']));
        }

        $name=$location->name;
        $location->delete();
        session()->flash('danger_message', $name . ' was deleted from the system');
        return redirect(route('location.index'));
    }

    public function export(Location $location)
    {
        if (auth()->user()->cant('viewAny', Location::class)) {
            return redirect(route('errors.forbidden', ['area', 'locations', 'export']));
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new LocationsExport, 'Location.csv');
    }

    public function downloadPDF(Request $request)
    {
        if (auth()->user()->cant('viewAny', Location::class)) {
            return redirect(route('errors.forbidden', ['area', 'Location', 'View PDF']));
        }

        if(auth()->user()->role_id==1){
            $found =Location::all();
        }else{
            $found = auth()->user()->locations;
        }

        $locations = array();

        foreach($found as $f){
            $array = array();
            $array['name'] = $f->name;
            $array['color'] = $f->icon ?? '#666';
            $array['line1'] = $f->address_1 ?? 'N/A';
            $array['line2'] = $f->address_2 ?? 'N/A';
            $array['city'] = $f->city ?? 'N/A';
            $array['county'] = $f->county ?? 'N/A';
            $array['postcode'] = $f->postcode ?? 'N/A';
            $array['asset'] = $f->asset->count() ?? 'N/A';
            $array['accessory'] = $f->accessory->count() ?? 'N/A';
            $array['component'] = $f->component->count() ?? 'N/A';
            $array['consumable'] = $f->consumable->count() ?? 'N/A';
            $array['miscellaneous'] = $f->miscellanea->count() ?? 'N/A';
            $locations[] = $array;
        }

        $user = auth()->user();
        
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'locations-'.$date;

        dispatch(new LocationsPdf($locations, $user, $path))->afterResponse();
        //Create Report
        
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report'=> $url, 'user_id'=> $user->id]);

        return redirect(route('location.index'))
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    public function downloadShowPDF(Location $location)
    {
        if (auth()->user()->cant('view', $location)) {
            return redirect(route('errors.forbidden', ['locations', $location->id, 'View PDF']));
        }
        
        $user = auth()->user();
        
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = str_replace(' ', '-', $location->name).'-'.$date;

        dispatch(new LocationPdf($location, $user, $path))->afterResponse();

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report'=> $url, 'user_id'=> $user->id]);

        return redirect(route('location.show', $location->id))
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    
    public function search(Request $request){
        $locations = Location::where('name', 'LIKE', '%' . $request->search . "%")->take(3)->get()->unique('name');
        $output = "<ul id='locationSelect' class='list-group'>";
        foreach($locations as $location){
            $output .=" <li class='list-group-item d-flex justify-content-between align-items-center pointer' data-id='{$location->id}' data-name=\"{$location->name}\">
                            {$location->name}
                            <span class='badge badge-primary badge-pill'>1</span>
                        </li>";
        }
        $output .= "</ul>";
        return Response($output);
    }

    public function preview(Request $request){
        if($location = Location::find($request->id)){
            if($location->photo()->exists() && $src = asset($location->photo->path)){
                 
            }else{
                $src = asset('images/svg/location-image.svg');
            }
            $output = " <div class='model_title text-center h4 mb-3'>{$location->name}</div>
                        <div class='model_image p-4 d-flex justify-content-center'>
                            <img id='profileImage' src='{$src}' height='200px'
                                alt='Select Profile Picture'>
                        </div>";
            if($location->address_1 != ''){
                $output .= "<div class='model_no py-2 px-4 text-center'>
                            Address: {$location->address_1}, {$location->city}, {$location->postcode}
                        </div>";
            }
                        
            $output .= "<div class='model_no py-2 px-4 text-center'>
                            Website: {$location->url}
                        </div>
                        <div class='model_no py-2 px-4 text-center'>
                            Email: {$location->email}
                        </div>";
            return $output;
        }
    }

}
