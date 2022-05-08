<?php

namespace App\Http\Controllers;

use App\Exports\accessoryExport;
use App\Exports\AssetExport;
use App\Exports\ComponentsExport;
use App\Exports\consumableExport;
use App\Exports\miscellaneousExport;
use App\Jobs\RoleBoot;
use App\Jobs\SettingBoot;
use App\Models\Accessory;
use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Broadband;
use App\Models\Component;
use App\Models\Consumable;
use App\Models\Location;
use App\Models\Miscellanea;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use function PHPUnit\Framework\isEmpty;

class SettingsController extends Controller {

    public function index()
    {
        if(auth()->user()->cant('viewAll', Setting::class))
        {
            return ErrorController::forbidden('/dashboard', 'Unauthorised to View Settings.');

        }
        $categories = \App\Models\Category::with('assets', 'accessories', 'components', 'consumables', 'miscellanea')->get();
        $users = User::all();
        $settings = Setting::all();
        $assetModel = AssetModel::all();
        $statuses = \App\Models\Status::all();
        $assets = Asset::locationFilter(auth()->user()->locations->pluck('id'));
        $components = Component::locationFilter(auth()->user()->locations->pluck('id'));
        $accessories = Accessory::locationFilter(auth()->user()->locations->pluck('id'));
        $miscellaneous = Miscellanea::locationFilter(auth()->user()->locations->pluck('id'));
        $locations = Location::all();
        $models = $this->getModels();
        unset($models[array_search('Permission', $models)]);
        $roles = Role::significance(auth()->user());

        return view('settings.view', [
            "users" => $users,
            "settings" => $settings,
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

    public function update(Setting $setting, Request $request)
    {
        if(auth()->user()->cant('update', Setting::class))
        {
            return ErrorController::forbidden('/dashboard', 'Unauthorised to Update Settings.');

        }
        $setting->update([
            'name' => $request->name,
            'value' => $request->value,
            'priority' => $request->priority,
        ]);

        return to_route('settings.view')
            ->with('success_message', "You have Updated the Setting " . ucwords(str_replace(['_', '-'], ' ', $setting->name)));
    }

    public function create(Request $request)
    {
        if(auth()->user()->cant('create', Setting::class))
        {
            return ErrorController::forbidden('/dashboard', 'Unauthorised to Create Settings.');

        }

        $request->validate([
            'name' => 'required|string',
            'value' => 'required|integer',
            'priority' => 'required|integer',
        ]);

        Setting::create([
            'name' => $request->name,
            'value' => $request->value,
            'priority' => $request->priority,
        ]);

        return to_route('settings.view')
            ->with('success_message', "You have Created the Setting " . ucwords(str_replace(['_', '-'], ' ', $request->name) . '. Please Speak to your developer to implement this setting.'));
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
            return to_route('errors.forbidden', ['area', 'Accessory', 'export']);
        }
        if(! $accessory->isEmpty())
        {
            $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
            \Maatwebsite\Excel\Facades\Excel::store(new accessoryExport($accessory), "/public/csv/accessories-ex-{$date}.csv");
            $url = asset("storage/csv/accessories-ex-{$date}.csv");

            return to_route('settings.view')
                ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
                ->withInput();
        } else
        {
            return to_route('settings.view')
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
            return to_route('errors.forbidden', ['area', 'Asset', 'export']);
        }

        if(! $asset->isEmpty())
        {
            $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
            \Maatwebsite\Excel\Facades\Excel::store(new AssetExport($asset), "/public/csv/assets-ex-{$date}.csv");
            $url = asset("storage/csv/assets-ex-{$date}.csv");

            return to_route('settings.view')
                ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
                ->withInput();
        } else
        {
            return to_route('settings.view')
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
            return to_route('errors.forbidden', ['area', 'Component', 'export']);
        }
        if(! $component->isEmpty())
        {
            $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
            \Maatwebsite\Excel\Facades\Excel::store(new ComponentsExport($component), "/public/csv/components-ex-{$date}.csv");
            $url = asset("storage/csv/components-ex-{$date}.csv");

            return to_route('settings.view')
                ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
                ->withInput();
        } else
        {
            return to_route('settings.view')
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
            return to_route('errors.forbidden', ['area', 'miscellaneous', 'export']);
        }
        if(! $miscellanea->isEmpty())
        {
            $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
            \Maatwebsite\Excel\Facades\Excel::store(new miscellaneousExport($miscellanea), "/public/csv/miscellaneous-ex-{$date}.csv");
            $url = asset("storage/csv/miscellaneous-ex-{$date}.csv");

            return to_route('settings.view')
                ->with('success_message', "Your Export has been created successfully. Click Here to <a href='{$url}'>Download CSV</a>")
                ->withInput();
        } else
        {
            return to_route('settings.view')
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

    public function roleBoot()
    {
        RoleBoot::dispatch()->afterResponse();

        return to_route('settings.view')
            ->with('success_message', "Your Roles have been Synced please allow a few moments for this to take effect");
    }

    public function settingBoot()
    {
        SettingBoot::dispatch()->afterResponse();

        return to_route('settings.view')
            ->with('success_message', "Your Settings have been Synced please allow a few moments for this to take effect");
    }

    public function updateBusinessSettings(Request $request){
        $validation = $request->validate([
            'asset_threshold' => 'required',
            'default_depreciation' => 'required',
        ]);

        $settings = Setting::updateOrCreate(['name' => 'asset_threshold'], ['value' => $request->asset_threshold, 'priority' => 1]);
        $settings = Setting::updateOrCreate(['name' => 'default_depreciation'], ['value' => $request->default_depreciation, 'priority' => 1]);

        return to_route('settings.view')
            ->with('success_message', "You have successfully updated the Business Settings");
    }

}
