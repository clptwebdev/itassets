<div class="card shadow mt-4">
    <div class="card-header bg-primary-blue text-white">Information</div>
    <div class="card-body">
        <p>There are currently {{ \App\Models\AssetModel::all()->count() }} Asset Models on the System</p>
    </div>

</div>
