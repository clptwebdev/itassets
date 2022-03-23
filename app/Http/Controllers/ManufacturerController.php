<?php

namespace App\Http\Controllers;

use App\Exports\ManufacturerExport;
use App\Imports\ManufacturerImport;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use phpDocumentor\Reflection\Location;
use PDF;
use App\Jobs\ManufacturersPdf;
use App\Jobs\ManufacturerPdf;
use App\Models\Report;

class ManufacturerController extends Controller {

    public function index()
    {
        if(auth()->user()->cant('viewAny', Manufacturer::class))
        {
            return ErrorController::forbidden(to_route('manufacturers.index'), 'Unauthorised to View Manufacturer.');
        }
        $manufacturers = Manufacturer::orderBy('name')->paginate(12);

        return view('Manufacturers.view', [
            "manufacturers" => $manufacturers,
        ]);

    }

    public function clearFilter()
    {
        session()->forget(['log_search']);

        return to_route('manufacturers.index');
    }

    public function filter(Request $request)
    {
        $filtered = Manufacturer::select();
        if($request->isMethod('post'))
        {
            if($request->search !== null)
            {
                \Session::put('manufacturer_search', $request->search);
            }
        }
        if(session('manufacturer_search'))
        {
            $results = $filtered->manufacturerFilter(session('manufacturer_search'));
        }
        if($results->count() == 0)
        {
            session()->flash('danger_message', "<strong>" . request("manufacturer_search") . "</strong>" . ' could not be found! Please search for something else!');

            return view("Manufacturers.view", [
                'manufacturers' => Manufacturer::latest()->paginate(),

            ]);
        } else
        {
            return view("Manufacturers.view", [
                'manufacturers' => $results->latest()->paginate(),

            ]);
        }

    }

    public function show(Manufacturer $manufacturer)
    {

        if(auth()->user()->cant('view', $manufacturer))
        {
            return ErrorController::forbidden(to_route('manufacturers.index'), 'Unauthorised to Show Manufacturer.');

        }

        return view('Manufacturers.show', compact('manufacturer'));
    }

    public function create()
    {
        if(auth()->user()->cant('create', Manufacturer::class))
        {
            return ErrorController::forbidden(to_route('manufacturers.index'), 'Unauthorised to Create Manufacturer.');

        }

        return view("Manufacturers.create", [
            "manufacturerAmount" => count(Manufacturer::all()),
        ]);

    }

    public function edit(Manufacturer $manufacturer)
    {
        if(auth()->user()->cant('update', Manufacturer::class))
        {
            return ErrorController::forbidden(to_route('manufacturers.index'), 'Unauthorised to Edit Manufacturer.');

        }

        return view('Manufacturers.edit', [
            "manufacturer" => $manufacturer,
        ]);

    }

    public function update(Manufacturer $manufacturer, Request $request)
    {

        $request->validate([
            "name" => "required|max:255",
            "supportPhone" => "required|max:14",
            'supportEmail' => [\Illuminate\Validation\Rule::unique('manufacturers')->ignore($manufacturer->id)],
            "PhotoId" => "nullable",
        ]);

        $manufacturer->fill([
            "name" => request("name"),
            "supportPhone" => request("supportPhone"),
            "supportUrl" => request("supportUrl"),
            "supportEmail" => request("supportEmail"),
            "photoId" => request("photoId")])->save();

        session()->flash('success_message', request("name") . ' has been updated successfully');

        return to_route('manufacturers.index');
    }

    public function store()
    {
        if(auth()->user()->cant('create', Manufacturer::class))
        {
            return ErrorController::forbidden(to_route('manufacturers.index'), 'Unauthorised to Store Manufacturer.');

        }
        request()->validate([
            "name" => "required|unique:manufacturers,name|max:255",
            "supportPhone" => "max:14",
            "supportEmail" => 'sometimes|nullable|unique:manufacturers,supportEmail|email:rfc,dns,filter',
            "PhotoId" => "nullable",
        ]);
        Manufacturer::create([
            "name" => request("name"),
            "supportPhone" => request("supportPhone"),
            "supportUrl" => request("supportUrl"),
            "supportEmail" => request("supportEmail"),
            "photoId" => request("photoId"),
            session()->flash('success_message', request("name") . ' has been created successfully'),
        ]);

        return to_route('manufacturers.index');
    }

    public function ajaxMany(Request $request)
    {

        $validation = Validator::make($request->all(), [
            "name.*" => "required|unique:manufacturers,name|max:255",
            "supportPhone.*" => "max:14",
            "supportUrl.*" => "required",
            "supportEmail.*" => 'required|unique:manufacturers,supportEmail|email:rfc,dns,spoof,filter',
        ]);

        if($validation->fails())
        {
            return $validation->errors();
        } else
        {
            for($i = 0; $i < count($request->name); $i++)
            {
                Manufacturer::Create([
                    "name" => $request->name[$i],
                    "supportPhone" => $request->supportPhone[$i],
                    "supportUrl" => $request->supportUrl[$i],
                    "supportEmail" => $request->supportEmail[$i],

                ]);
            }
            session()->flash('success_message', 'You can successfully added the Manufacturers');

            return 'Success';
        }
    }

    public function destroy(Manufacturer $manufacturer)
    {
        if(auth()->user()->cant('create', $manufacturer))
        {
            return ErrorController::forbidden(to_route('manufacturers.show', $manufacturer->id), 'Unauthorised to Archive Manufacturers.');

        }
        $name = $manufacturer->name;
        $manufacturer->delete();
        session()->flash('danger_message', $name . ' was deleted from the system');

        return to_route("manufacturers.index");
    }

    public function export(Manufacturer $manufacturer)
    {
        if(auth()->user()->cant('viewAny', Manufacturer::class))
        {
            return ErrorController::forbidden(to_route('manufacturers.index'), 'Unauthorised to Export Manufacturer.');

        }

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        \Maatwebsite\Excel\Facades\Excel::store(new ManufacturerExport, "/public/csv/manufacturers-ex-{$date}.xlsx");
        $url = asset("storage/csv/manufacturers-ex-{$date}.xlsx");

        return to_route('manufacturers.index')
            ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
            ->withInput();
    }

    public function import(Request $request)
    {
        if(auth()->user()->cant('create', Manufacturer::class))
        {
            return ErrorController::forbidden(to_route('manufacturers.index'), 'Unauthorised to Import Manufacturer.');

        }

        //headings incorrect start
        $column = (new HeadingRowImport)->toArray($request->file("csv"));
        $columnPopped = array_pop($column);
        $values = array_flip(array_pop($columnPopped));
        if(isset($values['name']) && isset($values['supporturl']) && isset($values['supportphone']) && isset($values['supportemail']))
        {

        } else
        {
            return to_route('manufacturers.index')->with('danger_message', "CSV Heading's Incorrect Please amend and try again!");
        }
        //headings incorrect end

        $extensions = array("csv");
        $result = array($request->file('csv')->getClientOriginalExtension());

        if(in_array($result[0], $extensions))
        {
            $path = $request->file("csv")->getRealPath();
            $import = new ManufacturerImport;
            $import->import($path, null, \Maatwebsite\Excel\Excel::CSV);
            $errors = $import->failures();
            $row = [];
            $attributes = [];
            $errors = [];
            $values = [];
            $results = $import->failures();
            $importErrors = [];

            foreach($results->all() as $result)
            {
                $row[] = $result->row();
                $attributes[] = $result->attribute();
                $errors[] = $result->errors();
                $values[] = $result->values();
                $importErrors[] = [

                    "row" => $result->row(),
                    "attributes" => $result->attribute(),
                    "errors" => $result->errors(),
                    "value" => $result->values(),
                ];
            }

            if(! empty($importErrors))
            {
                $errorArray = [];
                $valueArray = [];
                $errorValues = [];

                foreach($importErrors as $error)
                {
                    if(array_key_exists($error['row'], $errorArray))
                    {
                        $errorArray[$error['row']] = $errorArray[$error['row']] . ',' . $error['attributes'];
                    } else
                    {
                        $errorArray[$error['row']] = $error['attributes'];
                    }
                    $valueArray[$error['row']] = $error['value'];

                    if(array_key_exists($error['row'], $errorValues))
                    {
                        $array = $errorValues[$error['row']];
                    } else
                    {
                        $array = [];
                    }

                    foreach($error['errors'] as $e)
                    {
                        $array[$error['attributes']] = $e;
                    }
                    $errorValues[$error['row']] = $array;

                }

                return view('Manufacturers.import-errors', [
                    "errorArray" => $errorArray,
                    "valueArray" => $valueArray,
                    "errorValues" => $errorValues,
                ]);

            } else
            {
                return to_route('manufacturers.index')->with('success_message', 'All Manufacturers were added correctly!');
            }
        } else
        {
            session()->flash('danger_message', 'Sorry! This File type is not allowed Please try a ".CSV"!');

            return to_route('manufacturers.index');
        }

    }

    public function downloadPDF()
    {
        if(auth()->user()->cant('viewAny', Manufacturer::class))
        {
            return ErrorController::forbidden(to_route('manufacturers.index'), 'Unauthorised to Download Manufacturer.');

        }

        $found = Manufacturer::all();
        $manufacturers = array();

        foreach($found as $f)
        {
            $array = array();
            $array['name'] = $f->name;
            $array['url'] = $f->supportUrl ?? '#666';
            $array['email'] = $f->supportEmail ?? 'N/A';
            $array['telephone'] = $f->supportPhone ?? 'N/A';
            $total = 0;
            foreach($f->assetModel as $assetModel)
            {
                $total += $assetModel->assets->count();
            }
            $array['asset'] = $total;
            $array['accessory'] = $f->accessory->count() ?? 'N/A';
            $array['component'] = $f->component->count() ?? 'N/A';
            $array['consumable'] = $f->consumable->count() ?? 'N/A';
            $array['miscellaneous'] = $f->miscellanea->count() ?? 'N/A';
            $manufacturers[] = $array;
        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'manufacturers-' . $date;

        dispatch(new ManufacturersPdf($manufacturers, $user, $path))->afterResponse();
        //Create Report

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('manufacturer.pdf')
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();

    }

    public function downloadShowPDF(Manufacturer $manufacturer)
    {
        if(auth()->user()->cant('view', $manufacturer))
        {
            return ErrorController::forbidden(to_route('manufacturers.index'), 'Unauthorised to Download Manufacturer.');

        }

        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = str_replace(' ', '-', $manufacturer->name) . '-' . $date;

        dispatch(new ManufacturerPdf($manufacturer, $user, $path))->afterResponse();

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('manufacturers.show', $manufacturer->id)
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

}
