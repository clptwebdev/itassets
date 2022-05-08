<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\Asset;
use App\Models\Component;
use App\Models\Consumable;
use App\Models\FFE;
use App\Models\Machinery;
use App\Models\Miscellanea;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class OrderController extends Controller {

    public function index()
    {
        //Gets all Models With Order Numbers
        $assets = Asset::select('order_no', 'purchased_cost', 'purchased_date')->whereNotNull('order_no')->get();
        $FFE = FFE::select('order_no', 'purchased_cost', 'purchased_date')->whereNotNull('order_no')->get();
        $accessory = Accessory::select('order_no', 'purchased_cost', 'purchased_date')->whereNotNull('order_no')->get();
        $component = Component::select('order_no', 'purchased_cost', 'purchased_date')->whereNotNull('order_no')->get();
        $misc = Miscellanea::select('order_no', 'purchased_cost', 'purchased_date')->whereNotNull('order_no')->get();
        $consumable = Consumable::select('order_no', 'purchased_cost', 'purchased_date')->whereNotNull('order_no')->get();
        $merged = collect([$FFE, $accessory, $component, $misc, $consumable, $assets]);
        $single = Collection::empty();
        //foreach $model then Foreach $item Push to a single collection
        foreach($merged as $merge)
        {
            foreach($merge as $item)
            {
                $single->push($item);
            }
        }
        //apply on the single collection to stop duplication of order numbers if it doesn't exist create an array key else add a new value ana count for the amount of items with that key
        $orders = [];
        foreach($single as $asset)
        {
            if(array_key_exists($asset->order_no, $orders))
            {
                $orders[$asset->order_no]['purchased_date'] = $asset->purchased_date;
                $orders[$asset->order_no]['items']++;
                $orders[$asset->order_no]['value'] = $orders[$asset->order_no]['value'] + $asset->purchased_cost;
            } else
            {
                $array = ['items' => 1, 'value' => $asset->purchased_cost, 'purchased_date' => $asset->purchased_date];
                $orders[$asset->order_no] = $array;
            }
        }

        return view('orders.view', [
            "orders" => $orders,
        ]);
    }

    public function show(Request $request)
    {
        $assets = Asset::whereOrderNo($request->order)->get();
        $FFE = FFE::whereNotNull('order_no')->get();
        $accessory = Accessory::whereOrderNo($request->order)->get();
        $component = Component::whereOrderNo($request->order)->get();
        $misc = Miscellanea::whereOrderNo($request->order)->get();
        $consumable = Consumable::whereOrderNo($request->order)->get();
        $merged = collect([$FFE, $accessory, $component, $misc, $consumable, $assets]);
        $single = Collection::empty();
        //foreach $model then Foreach $item Push to a single collection
        foreach($merged as $merge)
        {
            foreach($merge as $item)
            {
                $single->push($item);
            }
        }

        return view('orders.show', [
            "assets" => $single,
            "order_no" => $request->order,
        ]);
    }

}
