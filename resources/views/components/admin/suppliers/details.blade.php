<div class="card shadow mt-4">
    <div class="card-header bg-primary-blue text-white">Information</div>
    <div class="card-body">
        <p>There currently are {{ \App\Models\Supplier::all()->count() }} Suppliers on the System</p>
    </div>

</div>
