<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;
use App\Models\Asset;
use App\Models\User;

use Illuminate\Auth\Access\Response;

class AssetPolicy {

    use HandlesAuthorization;

    private $model;
    private $request;

    public function __construct()
    {
        $this->model = auth()->user()->role->permissions->where('model', ' = ', 'Asset')->first();
        $this->request = auth()->user()->role->permissions->where('model', ' = ', 'Requests')->first();
    }

    public function viewAll(User $user)
    {
        return $this->model->view;

    }

    public function view(User $user, Asset $asset)
    {
        return $this->model->view && in_array($asset->location_id, $user->locationsArray());
    }

    public function create(User $user)
    {
        return $this->model->create;
    }

    public function update(User $user, Asset $asset)
    {
        return $this->model->update && in_array($asset->location_id, $user->locationsArray());

    }

    public function delete(User $user, Asset $asset)
    {
        return $this->model->archive && in_array($asset->location_id, $user->locationsArray());

    }

    public function recycleBin(User $user)
    {
        return $this->model->view;

    }

    public function generatePDF(User $user)
    {
        return $this->model->view;
    }

    public function generateAssetPDF(User $user, Asset $asset)
    {
        return $this->model->view && in_array($asset->location_id, $user->locationsArray());

    }

    public function restore(User $user, Asset $asset)
    {
        return $this->model->delete && in_array($asset->location_id, $user->locationsArray());
    }

    public function forceDelete(User $user, Asset $asset)
    {
        return $this->model->delete && in_array($asset->location_id, $user->locationsArray());
    }

    public function transfer(User $user, Asset $asset)
    {
        return $this->model->transfer && in_array($asset->location_id, $user->locationsArray());
    }

    public function bypass_transfer(User $user){
        return $this->request->request;
    }

    public function dispose(User $user, Asset $asset)
    {
        return $this->model->delete && in_array($asset->location_id, $user->locationsArray());
    }

    public function disposeAll(User $user)
    {

        return $this->model->delete;
    }

    public function request()
    {
        return $this->model->request;
    }

    public function transferAll(User $user)
    {
        return $this->model->transfer;
    }

}
