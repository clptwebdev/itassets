<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;
use App\Models\Asset;
use App\Models\User;

use Illuminate\Auth\Access\Response;

class AssetPolicy
{
    use HandlesAuthorization;

    protected $super = [1];
    protected $admin = [1,2];
    protected $technician = [1,3];
    protected $manager = [1,2,3,4];
    protected $all = [1,2,3,4,5];


    public function viewAll(User $user)
    {
        return in_array($user->role_id, $this->all);
    }

    public function view(User $user, Asset $asset)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->all) && in_array($asset->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function create(User $user)
    {
        return in_array($user->role_id, $this->manager);
    }

    public function update(User $user, Asset $asset)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->manager) && in_array($asset->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function delete(User $user, Asset $asset)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->admin) && in_array($asset->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function recycleBin(User $user)
    {
        return in_array($user->role_id, $this->admin);
    }

    public function generatePDF(User $user)
    {
        return in_array($user->role_id, $this->all);
    }

    public function generateAssetPDF(User $user, Asset $asset)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->all) && in_array($asset->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function restore(User $user, Asset $asset)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->admin) && in_array($asset->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function forceDelete(User $user, Asset $asset)
    {
        $locations = $user->locations->pluck('id')->toArray();
        if(in_array($user->role_id, $this->super) && in_array($asset->location_id, $locations)){
            return true;
        }else{
            return false;
        }
    }

    public function transfer(User $user, Asset $asset){
        $locations = $user->locations->pluck('id')->toArray();
        return in_array($user->role_id, $this->technician) && in_array($asset->location_id, $locations);
    }

    public function dispose(User $user, Asset $asset){
        $locations = $user->locations->pluck('id')->toArray();
        return in_array($user->role_id, $this->technician) && in_array($asset->location_id, $locations);
    }

    public function disposeAll(User $user){
        return in_array($user->role_id, $this->super);
    }
}
