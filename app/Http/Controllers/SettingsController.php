<?php

namespace App\Http\Controllers;

use App\Exports\accessoryExport;
use App\Exports\AssetExport;
use App\Exports\ComponentsExport;
use App\Exports\consumableExport;
use App\Exports\miscellaneousExport;
use App\Models\Accessory;
use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Component;
use App\Models\Consumable;
use App\Models\Location;
use App\Models\Miscellanea;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use function PHPUnit\Framework\isEmpty;

class SettingsController extends Controller {

    public function index()
    {

        $categories = \App\Models\Category::with('assets', 'accessories', 'components', 'consumables', 'miscellanea')->get();
        $users = User::all();
        $assetModel = AssetModel::all();
        $statuses = \App\Models\Status::all();
        $assets = Asset::locationFilter(auth()->user()->locations->pluck('id'));
        $components = Component::locationFilter(auth()->user()->locations->pluck('id'));
        $accessories = Accessory::locationFilter(auth()->user()->locations->pluck('id'));
        $miscellaneous = Miscellanea::locationFilter(auth()->user()->locations->pluck('id'));
        $locations = Location::all();
        $models = $this->getModels();
        unset($models[array_search('Permission', $models)]);
        unset($models[array_search('Archive', $models)]);
        unset($models[array_search('Report', $models)]);
        unset($models[array_search('Requests', $models)]);
        unset($models[array_search('Transfer', $models)]);
        $roles = Role::all();

        return view('settings.view', [
            "users" => $users,
            "assets" => $assets,
            "components" => $components,
            "accessories" => $accessories,
            "miscellaneous" => $miscellaneous,
            "locations" => $locations,
            "categories" => $categories,
            "statuses" => $statuses,
            "assetModel" => $assetModel,
            "models" => $models,
            "roles" => $roles,
        ]);
    }

    public function accessories(Request $request)
    {
        $accessories = Accessory::locationFilter(auth()->user()->locations->pluck('id'));
        if($request->status)
        {
            $accessories->statusFilter($request->status);
        }
        if($request->category)
        {
            $accessories->categoryFilter($request->category);
        }
        if($request->location)
        {
            $accessories->locationFilter($request->location);
        }
        $accessory = $accessories->with('supplier', 'location')->get();

        if(auth()->user()->cant('viewAll', Accessory::class))
        {
            return redirect(route('errors.forbidden', ['area', 'Accessory', 'export']));
        }
        if(! $accessory->isEmpty())
        {
            $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
            \Maatwebsite\Excel\Facades\Excel::store(new accessoryExport($accessory), "/public/csv/accessories-ex-{$date}.csv");
            $url = asset("storage/csv/accessories-ex-{$date}.csv");

            return redirect(route('settings.view'))
                ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
                ->withInput();
        } else
        {
            return redirect(route('settings.view'))
                ->with('danger_message', "There are no Assets found with this Filter! Please alter your query and try again.");

        }
    }

    public function assets(Request $request)
    {
        $assets = Asset::locationFilter(auth()->user()->locations->pluck('id'));
        if($request->model)
        {
            $assets->assetModelFilter($request->model);
        }
        if($request->status)
        {
            $assets->statusFilter($request->status);
        }
        if($request->category)
        {
            $assets->categoryFilter($request->category);
        }
        if($request->location)
        {
            $assets->locationFilter($request->location);
        }
        $asset = $assets->get();

        if(auth()->user()->cant('viewAll', Asset::class))
        {
            return redirect(route('errors.forbidden', ['area', 'Asset', 'export']));
        }

        if(! $asset->isEmpty())
        {
            $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
            \Maatwebsite\Excel\Facades\Excel::store(new AssetExport($asset), "/public/csv/assets-ex-{$date}.csv");
            $url = asset("storage/csv/assets-ex-{$date}.csv");

            return redirect(route('settings.view'))
                ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
                ->withInput();
        } else
        {
            return redirect(route('settings.view'))
                ->with('danger_message', "There are no Assets found with this Filter! Please alter your query and try again.");

        }
    }

    public function components(Request $request)
    {
        $components = Component::locationFilter(auth()->user()->locations->pluck('id'));
        if($request->status)
        {
            $components->statusFilter($request->status);
        }
        if($request->category)
        {
            $components->categoryFilter($request->category);
        }
        if($request->location)
        {
            $components->locationFilter($request->location);
        }
        $component = $components->with('supplier', 'location')->get();

        if(auth()->user()->cant('viewAll', Component::class))
        {
            return redirect(route('errors.forbidden', ['area', 'Component', 'export']));
        }
        if(! $component->isEmpty())
        {
            $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
            \Maatwebsite\Excel\Facades\Excel::store(new ComponentsExport($component), "/public/csv/components-ex-{$date}.csv");
            $url = asset("storage/csv/components-ex-{$date}.csv");

            return redirect(route('settings.view'))
                ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
                ->withInput();
        } else
        {
            return redirect(route('settings.view'))
                ->with('danger_message', "There are no Assets found with this Filter! Please alter your query and try again.");

        }
    }

    public function miscellaneous(Request $request)
    {
        $miscellaneous = Miscellanea::locationFilter(auth()->user()->locations->pluck('id'));
        if($request->status)
        {
            $miscellaneous->statusFilter($request->status);
        }
        if($request->category)
        {
            $miscellaneous->categoryFilter($request->category);
        }
        if($request->location)
        {
            $miscellaneous->locationFilter($request->location);
        }
        $miscellanea = $miscellaneous->with('supplier', 'location')->get();

        if(auth()->user()->cant('viewAny', Miscellanea::class))
        {
            return redirect(route('errors.forbidden', ['area', 'miscellaneous', 'export']));
        }
        if(! $miscellanea->isEmpty())
        {
            $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
            \Maatwebsite\Excel\Facades\Excel::store(new miscellaneousExport($miscellanea), "/public/csv/miscellaneous-ex-{$date}.csv");
            $url = asset("storage/csv/miscellaneous-ex-{$date}.csv");

            return redirect(route('settings.view'))
                ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
                ->withInput();
        } else
        {
            return redirect(route('settings.view'))
                ->with('danger_message', "There are no Assets found with this Filter! Please alter your query and try again.");

        }
    }

    public function getModels()
    {
        $path = app_path() . "/Models";
        $out = [];
        $results = scandir($path);
        foreach($results as $result)
        {
            if($result === '.' or $result === '..') continue;
            $filename = $path . '/' . $result;
            if(is_dir($filename))
            {
                $out = array_merge($out, getModels($filename));
            } else
            {
                $out[] = substr($filename, 0, -4);
            }
        }
        $models = str_replace($path . '/', '', $out);

        return $models;

    }

}
